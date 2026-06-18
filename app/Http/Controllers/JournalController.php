<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JournalController extends Controller
{
    public function index()
    {
        $journals = auth()->user()->journals()->latest()->paginate(10);
        return view('mental.journal', compact('journals'));
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string|max:5000']);

        [$score, $label] = $this->analyzeSentiment($request->input('content'));

        Journal::create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'sentiment_score' => $score,
            'sentiment_label' => $label,
        ]);

        return back()->with('success', 'บันทึกความรู้สึกสำเร็จ');
    }

    public function update(Request $request, Journal $journal)
    {
        if ($journal->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate(['content' => 'required|string|max:5000']);

        [$score, $label] = $this->analyzeSentiment($request->input('content'));

        $journal->update([
            'content' => $request->input('content'),
            'sentiment_score' => $score,
            'sentiment_label' => $label,
        ]);
        
        return back()->with('success', 'แก้ไขบันทึกสำเร็จ');
    }

    public function destroy(Journal $journal)
    {
        if ($journal->user_id !== auth()->id()) {
            abort(403);
        }
        $journal->delete();
        return back()->with('success', 'ลบบันทึกสำเร็จ');
    }

    /**
     * @param string $content
     * @return array{0: float, 1: string}
     */
    private function analyzeSentiment(string $content): array
    {
        $apiKey = config('services.gemini.key');
        
        if (!empty($apiKey)) {
            $systemInstruction = 'คุณคือ AI ผู้ช่วยวิเคราะห์อารมณ์ความรู้สึก (Sentiment Analysis) จากบันทึกประจำวัน หน้าที่ของคุณคืออ่านข้อความภาษาไทยและประเมินว่าข้อความนี้มีอารมณ์ในภาพรวมเป็นเชิงบวก (positive) เชิงลบ (negative) หรือเป็นกลาง (neutral) พร้อมให้คะแนนระหว่าง -1.0 (ลบมากที่สุด) ถึง 1.0 (บวกมากที่สุด) ให้ตอบกลับเป็น JSON รูปแบบนี้เท่านั้น: {"score": float, "label": "positive|neutral|negative"} ห้ามมีข้อความอื่นใด';

            $payload = [
                'system_instruction' => [
                    'parts' => [['text' => $systemInstruction]]
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [['text' => $content]]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'response_mime_type' => 'application/json',
                ]
            ];

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    
                    $result = json_decode($reply, true);
                    if (is_array($result) && isset($result['score']) && isset($result['label'])) {
                        return [(float) $result['score'], (string) $result['label']];
                    }
                }
            } catch (\Exception $e) {
                // Ignore and fallback to dictionary-based method
            }
        }

        // Fallback dictionary-based method
        $positiveWords = ['ดี', 'สุข', 'ยิ้ม', 'สนุก', 'สบาย', 'มั่นใจ', 'รัก', 'ชนะ', 'สำเร็จ', 'ปลอดภัย', 'อบอุ่น', 'ยินดี', 'ขอบคุณ', 'เยี่ยม', 'หัวเราะ', 'แฮปปี้', 'ดีใจ', 'ชอบ'];
        $negativeWords = ['เศร้า', 'เหงา', 'ท้อ', 'เหนื่อย', 'เครียด', 'กลัว', 'เจ็บ', 'ป่วย', 'ร้องไห้', 'เสียใจ', 'แย่', 'โดดเดี่ยว', 'เกลียด', 'โกรธ', 'กังวล', 'หมดไฟ', 'ท้อแท้', 'พัง', 'อึดอัด'];
        
        $posCount = 0;
        $negCount = 0;
        
        foreach ($positiveWords as $word) {
            $posCount += mb_substr_count($content, $word);
        }
        
        foreach ($negativeWords as $word) {
            $negCount += mb_substr_count($content, $word);
        }
        
        $total = $posCount + $negCount;
        if ($total == 0) {
            return [0.0, 'neutral'];
        }
        
        $score = ($posCount - $negCount) / $total;
        
        if ($score > 0.1) {
            $label = 'positive';
        } elseif ($score < -0.1) {
            $label = 'negative';
        } else {
            $label = 'neutral';
        }
        
        return [(float) $score, (string) $label];
    }
}
