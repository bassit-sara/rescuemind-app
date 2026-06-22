<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiCompanionController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        $apiKey = config('services.gemini.key');
        
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'AI Provider API Key is not configured.'
            ], 500);
        }

        // Prepare context for Gemini
        // Get Context
        $user = auth()->user();
        $userContext = "ผู้ใช้ยังไม่ได้ล็อกอิน";
        if ($user) {
            $userContext = "ชื่อผู้ใช้: " . $user->name . "\n" .
                           "พิกัดปัจจุบัน/จังหวัด: " . ($user->province ?? 'ไม่ระบุ') . "\n" .
                           "เบอร์โทร: " . ($user->phone ?? 'ไม่ระบุ');
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
                      "1. ระบบแจ้งเหตุฉุกเฉิน (SOS): ผู้ใช้กดขอความช่วยเหลือได้ตลอดเวลา ระบบจะใช้ AI ประเมินความรุนแรง (Triage) จากข้อมูลผู้สูงอายุ/ผู้ป่วย/คนท้อง เจ้าหน้าที่จะเข้าถึงพิกัดเพื่อช่วยเหลือทันที\n" .
                      "2. ระบบรายงานจุดอันตราย (Hazard Reports): แสดงแผนที่จุดเสี่ยงภัยต่างๆ (เช่น น้ำท่วมลึก ถนนขาด) ให้ประชาชนหลีกเลี่ยง\n" .
                      "3. ระบบประสานงานทรัพยากร (Resources) และศูนย์พักพิง (Shelters): สำหรับขอเสบียงและหาที่พักพิงปลอดภัย\n" .
                      "4. ระบบเยียวยาจิตใจ (Mental Health): มีบทความสุขภาพจิตจากนักจิตวิทยาเพื่อดูแลสภาพจิตใจผู้ประสบภัย\n" .
                      "5. แดชบอร์ด (Dashboard): แสดงการแจ้งเตือนภัยพิบัติฉุกเฉิน (Alerts) และสรุปสถานการณ์แบบเรียลไทม์";

        $systemInstruction = "คุณคือ 'RescueMind AI' ผู้เชี่ยวชาญด้านภัยพิบัติ การเอาชีวิตรอด และเป็นผู้ช่วยอัจฉริยะประจำระบบ RescueMind อย่างเป็นทางการ\n\n" .
                             "--- ข้อมูลระบบ RescueMind ---\n" .
                             "$systemInfo\n\n" .
                             "--- กฎในการตอบของคุณ ---\n" .
                             "1. ตอบคำถามอย่างเป็นธรรมชาติ สุภาพ เห็นอกเห็นใจ และให้กำลังใจผู้ประสบภัย\n" .
                             "2. ให้คำแนะนำที่กระชับ ปฏิบัติตามได้จริงเพื่อความปลอดภัยในชีวิต\n" .
                             "3. แนะนำการใช้งานฟีเจอร์ของระบบ RescueMind ให้ตรงกับสิ่งที่ผู้ใช้ต้องการ (เช่น แนะนำให้อ่านบทความสุขภาพจิตหากผู้ใช้เครียด)\n" .
                             "4. หากผู้ใช้กำลังตกอยู่ในอันตราย ให้บอกให้เขากดปุ่ม 'แจ้งเหตุฉุกเฉิน SOS' ในระบบทันที\n" .
                             "5. หากผู้ใช้สอบถามเรื่องสภาพอากาศหรือพยากรณ์อากาศ ให้ตอบว่า 'คุณสามารถตรวจสอบข้อมูลสภาพอากาศและเรดาร์ฝนแบบเรียลไทม์ได้ที่หน้าแดชบอร์ด (Dashboard) ของระบบ RescueMind ค่ะ/ครับ' โดยไม่ต้องพยากรณ์อากาศเอง\n\n" .
                             "--- ข้อมูลบริบทปัจจุบัน ---\n" .
                             "[ข้อมูลผู้ใช้]\n$userContext\n\n" .
                             "[สถานการณ์ภัยพิบัติปัจจุบัน]\n$alertContext\n" .
                             "--------------------------\n" .
                             "กรุณาใช้ข้อมูลทั้งหมดนี้ในการวิเคราะห์และตอบคำถามผู้ใช้ให้เกิดประโยชน์สูงสุด";

        $messages = [];
        
        // Add System Instruction
        $messages[] = [
            'role' => 'system',
            'content' => $systemInstruction
        ];
        
        // Add history
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['content']
            ];
        }
        
        // Add current message
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        $payload = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => $messages,
            'temperature' => 0.7
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.groq.com/openai/v1/chat/completions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'ขออภัย เกิดข้อผิดพลาดในการประมวลผลคำตอบ';
                
                return response()->json([
                    'success' => true,
                    'reply' => $reply
                ]);
            } else {
                $err = $response->json();
                // If API Key is invalid, provide a fallback response for demo purposes
                if (isset($err['error']['status']) && $err['error']['status'] === 'INVALID_ARGUMENT' && strpos($err['error']['message'], 'API key') !== false) {
                    return response()->json([
                        'success' => true,
                        'reply' => "⚠️ (ระบบจำลอง) ขณะนี้ API Key ของ Gemini ไม่ถูกต้องหรือหมดอายุครับ \n\nแต่เบื้องต้นหากคุณถามเรื่องสภาพอากาศ ตอนนี้ระบบเรามีการดึงข้อมูลแจ้งเตือนพายุอัตโนมัติแล้ว กรุณาตรวจสอบที่หน้าแดชบอร์ดครับ"
                    ]);
                }

                $errorMessage = 'Failed to connect to AI provider.';
                if (isset($err['error']['message'])) {
                    $errorMessage = 'Google API Error: ' . $err['error']['message'];
                }
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $err
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'System Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
