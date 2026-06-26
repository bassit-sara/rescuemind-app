<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReliefPoint;
use App\Models\ShelterBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // List bookings for the authenticated user
    public function index()
    {
        $bookings = Auth::user()->shelterBookings()->with('reliefPoint')->orderBy('created_at', 'desc')->get();
        return view('bookings.index', compact('bookings'));
    }

    // Show the booking form for a specific relief point
    public function create(ReliefPoint $reliefPoint)
    {
        if ($reliefPoint->type !== 'shelter') {
            return redirect()->route('relief-points.show', $reliefPoint)->with('error', 'จุดนี้ไม่ใช่ที่พักพิง ไม่สามารถจองได้');
        }
        
        // Prevent booking if full
        if ($reliefPoint->current_occupancy >= $reliefPoint->capacity && $reliefPoint->capacity > 0) {
            return redirect()->route('relief-points.show', $reliefPoint)->with('error', 'ที่พักพิงนี้เต็มแล้ว');
        }

        return view('bookings.create', compact('reliefPoint'));
    }

    // Store the booking
    public function store(Request $request, ReliefPoint $reliefPoint)
    {
        if ($reliefPoint->type !== 'shelter') {
            return redirect()->route('relief-points.show', $reliefPoint)->with('error', 'จุดนี้ไม่ใช่ที่พักพิง ไม่สามารถจองได้');
        }

        // Prevent duplicate bookings (active pending or approved)
        $hasActiveBooking = ShelterBooking::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
            
        if ($hasActiveBooking) {
            return redirect()->route('bookings.index')->with('error', 'คุณมีรายการจองที่กำลังรอดำเนินการหรือได้รับการอนุมัติอยู่แล้ว ไม่สามารถจองซ้ำได้ครับ');
        }

        $request->validate([
            'number_of_people' => 'required|integer|min:1',
            'province' => 'required|string',
            'district' => 'required|string',
            'subdistrict' => 'required|string',
            'house_no' => 'required|string',
            'current_situation' => 'required|string',
            'additional_info' => 'nullable|string',
        ]);

        $fullAddress = $request->house_no . ' ต.' . $request->subdistrict . ' อ.' . $request->district . ' จ.' . $request->province;
        $combinedInfo = "สถานการณ์ ณ ปัจจุบัน: " . $request->current_situation;
        if ($request->additional_info) {
            $combinedInfo .= "\nข้อมูลเพิ่มเติม: " . $request->additional_info;
        }

        ShelterBooking::create([
            'user_id' => Auth::id(),
            'relief_point_id' => $reliefPoint->id,
            'number_of_people' => $request->number_of_people,
            'current_address' => $fullAddress,
            'additional_info' => $combinedInfo,
            'status' => 'pending'
        ]);

        return redirect()->route('bookings.index')->with('success', 'ส่งคำขอจองที่พักพิงเรียบร้อยแล้ว กรุณารอการอนุมัติ');
    }

    // AI Route evaluation
    public function evaluateRoute(Request $request, ShelterBooking $booking)
    {
        // Ensure the user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $reliefPoint = $booking->reliefPoint;
        
        $origin = $booking->current_address;
        $destination = $reliefPoint->address . ' จ.' . $reliefPoint->province;

        $systemInstruction = "คุณคือ 'RescueMind Route AI' ผู้เชี่ยวชาญด้านการประเมินเส้นทางอพยพหนีน้ำท่วม\n" .
                             "หน้าที่ของคุณคือ วิเคราะห์ความเสี่ยงและให้คำแนะนำในการเดินทางจาก [จุดเริ่มต้น] ไปยัง [ที่พักพิง]\n" .
                             "ประเมินความเป็นไปได้ของการเดินทาง เช่น ถนนอาจถูกตัดขาดไหม หรือต้องใช้เส้นทางเลี่ยงอย่างไร\n" .
                             "ตอบเป็นภาษาไทยด้วยความกระชับ เข้าใจง่าย และแสดงความห่วงใย";

        $userPrompt = "ประเมินเส้นทางอพยพให้หน่อย:\nจุดเริ่มต้น: $origin\nจุดหมายที่พักพิง: $destination";

        $apiKey = env('GROQ_API_KEY');
        if (!$apiKey) {
            return response()->json(['evaluation' => 'ไม่สามารถประเมินได้เนื่องจากไม่ได้ตั้งค่า API Key']);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemInstruction],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => 0.7
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['choices'][0]['message']['content'] ?? 'ไม่สามารถประเมินได้ในขณะนี้';
                return response()->json(['evaluation' => $aiResponse]);
            } else {
                Log::error('Route AI Error', ['error' => $response->json()]);
                return response()->json(['evaluation' => 'เกิดข้อผิดพลาดในการเชื่อมต่อกับ AI']);
            }
        } catch (\Exception $e) {
            Log::error('Route AI Exception', ['exception' => $e->getMessage()]);
            return response()->json(['evaluation' => 'ระบบ AI ประเมินเส้นทางขัดข้องชั่วคราว']);
        }
    }
}
