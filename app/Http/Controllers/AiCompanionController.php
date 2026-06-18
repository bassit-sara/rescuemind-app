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

        $systemInstruction = "คุณคือ 'RescueMind AI' ผู้เชี่ยวชาญด้านภัยพิบัติ และการเอาชีวิตรอด คุณทำหน้าที่เป็นผู้ช่วยฉุกเฉิน 24 ชั่วโมงในระบบ RescueMind. กฎในการตอบของคุณคือ: 1. ให้คำแนะนำที่กระชับ ปฏิบัติตามได้จริงเพื่อความปลอดภัยในชีวิต 2. หากมีความเสี่ยงอันตรายถึงชีวิต ให้แนะนำให้ผู้ใช้กดปุ่ม SOS ในระบบ 3. ตอบด้วยความเห็นอกเห็นใจ สุภาพ และให้กำลังใจ 4. หากคุณได้รับข้อมูลสถานะผู้ใช้ หรือการแจ้งเตือนภัย คุณต้องนำข้อมูลเหล่านั้นมาประกอบการให้คำแนะนำด้วย\n\n" .
                             "--- ข้อมูลบริบทปัจจุบัน ---\n" .
                             "[ข้อมูลผู้ใช้]\n$userContext\n\n" .
                             "[สถานการณ์ภัยพิบัติปัจจุบัน]\n$alertContext\n" .
                             "--------------------------\n" .
                             "กรุณาใช้ข้อมูลบริบทด้านบน (ถ้ามี) ในการตอบคำถามผู้ใช้ให้ตรงจุดที่สุด";

        $contents = [];
        
        // Add history
        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg['content']]]
            ];
        }
        
        // Add current message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1024,
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'ขออภัย ฉันไม่สามารถประมวลผลคำตอบได้ในขณะนี้';
                
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

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to connect to AI provider.',
                    'error' => $err
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while contacting the AI provider.'
            ], 500);
        }
    }
}
