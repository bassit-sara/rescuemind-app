<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LineWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Verify Signature
        $channelSecret = config('services.line.channel_secret');
        $signature = $request->header('x-line-signature');
        $body = $request->getContent();

        $hash = hash_hmac('sha256', $body, $channelSecret, true);
        $expectedSignature = base64_encode($hash);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('LINE Webhook Signature mismatch.', [
                'expected' => $expectedSignature,
                'actual' => $signature
            ]);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // 2. Parse Events
        $events = $request->input('events', []);

        foreach ($events as $event) {
            // Process only 'message' events of type 'text'
            if ($event['type'] === 'message' && $event['message']['type'] === 'text') {
                $userText = $event['message']['text'];
                $replyToken = $event['replyToken'];

                // 3. Get AI Reply
                $aiReplyText = $this->getAiReply($userText);

                // 4. Send Reply back to LINE
                $this->sendLineReply($replyToken, $aiReplyText);
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function getAiReply($userMessage)
    {
        $apiKey = config('services.gemini.key');
        
        if (empty($apiKey)) {
            return "ขออภัยครับ ขณะนี้ระบบประมวลผล AI ยังไม่พร้อมใช้งาน (API Key ใช้งานไม่ได้)";
        }

        $activeAlerts = \App\Models\Alert::query()->where('is_active', true)->get();
        $alertContext = "ขณะนี้ไม่มีการแจ้งเตือนภัยพิบัติฉุกเฉินในระบบ";
        if ($activeAlerts->count() > 0) {
            $alertContext = "🚨 **การแจ้งเตือนฉุกเฉินระดับประเทศขณะนี้:**\n";
            foreach ($activeAlerts as $alert) {
                $alertContext .= "- พื้นที่: {$alert->province} | ภัย: {$alert->disaster_type} | ระดับความรุนแรง: {$alert->severity_level} | รายละเอียด: {$alert->message}\n";
            }
        }

        $systemInfo = "ระบบ RescueMind คือ แพลตฟอร์มจัดการภัยพิบัติและช่วยเหลือฉุกเฉินครบวงจร มีฟีเจอร์หลักดังนี้:\n" .
                      "1. ระบบแจ้งเหตุฉุกเฉิน (SOS): ผู้ใช้กดขอความช่วยเหลือได้ตลอดเวลา ระบบจะใช้ AI ประเมินความรุนแรง (Triage)\n" .
                      "2. ระบบรายงานจุดอันตราย (Hazard Reports): แสดงแผนที่จุดเสี่ยงภัยต่างๆ\n" .
                      "3. ระบบประสานงานทรัพยากร (Resources) และศูนย์พักพิง (Shelters)\n" .
                      "4. ระบบเยียวยาจิตใจ (Mental Health): มีบทความสุขภาพจิตเพื่อดูแลสภาพจิตใจ\n" .
                      "5. แดชบอร์ด (Dashboard): แสดงการแจ้งเตือนภัยพิบัติฉุกเฉิน (Alerts)";

        $systemInstruction = "คุณคือ 'RescueMind AI' ผู้เชี่ยวชาญด้านภัยพิบัติ การเอาชีวิตรอด และเป็นผู้ช่วยอัจฉริยะประจำระบบ RescueMind อย่างเป็นทางการ\n\n" .
                             "--- ข้อมูลระบบ RescueMind ---\n" .
                             "$systemInfo\n\n" .
                             "--- กฎในการตอบของคุณ ---\n" .
                             "1. ตอบคำถามอย่างเป็นธรรมชาติ สุภาพ เห็นอกเห็นใจ และให้กำลังใจผู้ประสบภัย\n" .
                             "2. ให้คำแนะนำที่กระชับ ปฏิบัติตามได้จริงเพื่อความปลอดภัยในชีวิต\n" .
                             "3. แนะนำการใช้งานฟีเจอร์ของระบบ RescueMind ให้ตรงกับสิ่งที่ผู้ใช้ต้องการ (ช่องทาง: เว็บไซต์ RescueMind)\n" .
                             "4. หากผู้ใช้กำลังตกอยู่ในอันตราย ให้บอกให้เขากดปุ่ม 'แจ้งเหตุฉุกเฉิน SOS' ในระบบเว็บทันที\n" .
                             "5. ตอบเป็นข้อความที่อ่านง่ายผ่าน LINE (รองรับ Emoji แต่ไม่ต้องใช้ Markdown เยอะเกินไป)\n\n" .
                             "--- ข้อมูลบริบทปัจจุบัน ---\n" .
                             "[สถานการณ์ภัยพิบัติปัจจุบัน]\n$alertContext\n" .
                             "--------------------------\n" .
                             "กรุณาใช้ข้อมูลทั้งหมดนี้ในการวิเคราะห์และตอบคำถามผู้ใช้ให้เกิดประโยชน์สูงสุด";

        $payload = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'system', 'content' => $systemInstruction],
                ['role' => 'user', 'content' => $userMessage]
            ],
            'temperature' => 0.7
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.groq.com/openai/v1/chat/completions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'ขออภัย เกิดข้อผิดพลาดในการประมวลผลคำตอบ';
            } else {
                Log::error('AI Error', ['error' => $response->json()]);
                return "เกิดข้อผิดพลาดในการเชื่อมต่อกับ AI Provider ครับ";
            }
        } catch (\Exception $e) {
            Log::error('AI Exception', ['exception' => $e->getMessage()]);
            return "ระบบ AI มีปัญหาขัดข้องชั่วคราว: " . $e->getMessage();
        }
    }

    private function sendLineReply($replyToken, $text)
    {
        $channelAccessToken = config('services.line.channel_access_token');
        
        $url = 'https://api.line.me/v2/bot/message/reply';
        
        $data = [
            'replyToken' => $replyToken,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $text
                ]
            ]
        ];

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $channelAccessToken,
            'Content-Type' => 'application/json',
        ])->post($url, $data);
    }
}
