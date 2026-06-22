<?php

namespace App\Http\Controllers;

use App\Models\SosRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SosRequestController extends Controller
{
    public function create()
    {
        return view('sos.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'address'       => 'nullable|string|max:500',
            'province'      => 'nullable|string|max:100',
            'num_people'    => 'required|integer|min:1|max:999',
            'water_level'   => 'nullable|string|max:50',
            'has_elderly'   => 'boolean',
            'has_children'  => 'boolean',
            'has_bedridden' => 'boolean',
            'has_pregnant'  => 'boolean',
            'other_vulnerable' => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'urgent_needs'  => 'nullable|array',
            'urgent_needs.*'=> 'string|max:100',
            'image'         => 'nullable|image|max:5120', // Max 5MB
        ];

        // Add guest validation if not logged in
        if (!auth()->check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_phone'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        // Default rule-based Triage
        $priority = 'medium';
        if ($request->has_bedridden || $request->has_pregnant) {
            $priority = 'critical';
        } elseif ($request->has_elderly || $request->has_children || $request->other_vulnerable) {
            $priority = 'high';
        } elseif ($request->num_people >= 10) {
            $priority = 'high';
        }

        // AI Triage
        $aiPriority = $this->analyzePriorityWithAI($request, $priority);
        if ($aiPriority) {
            $priority = $aiPriority;
        }

        $sosData = array_merge($validated, [
            'user_id'      => auth()->id(),
            'status'       => 'pending',
            'priority'     => $priority,
            'has_elderly'  => $request->boolean('has_elderly'),
            'has_children' => $request->boolean('has_children'),
            'has_bedridden'=> $request->boolean('has_bedridden'),
            'has_pregnant' => $request->boolean('has_pregnant'),
            'other_vulnerable' => $request->other_vulnerable,
            'urgent_needs' => $request->urgent_needs,
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sos_images', 'public');
            $sosData['image_path'] = $imagePath;
        }

        if (!auth()->check()) {
            $sosData['guest_name'] = $request->guest_name;
            $sosData['guest_phone'] = $request->guest_phone;
        }

        $sos = SosRequest::create($sosData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'ส่งคำขอความช่วยเหลือสำเร็จ! เจ้าหน้าที่จะติดต่อกลับโดยเร็ว',
                'redirect' => auth()->check() ? route('sos.my') : route('sos.guest.success')
            ]);
        }

        return redirect(auth()->check() ? route('sos.my') : route('sos.guest.success'))
            ->with('success', 'ส่งคำขอความช่วยเหลือสำเร็จ!');
    }

    public function guestSuccess()
    {
        return view('sos.guest-success');
    }

    public function show(SosRequest $sosRequest)
    {
        // User can only view their own, officer can view all
        if (!auth()->user()->hasRole(['officer', 'admin', 'super_admin'])) {
            abort_unless($sosRequest->user_id === auth()->id(), 403);
        }
        return view('sos.show', compact('sosRequest'));
    }

    public function myRequests()
    {
        $sosRequests = auth()->user()->sosRequests()->latest()->paginate(10);
        return view('sos.my', compact('sosRequests'));
    }

    // Officer methods
    public function officerIndex(Request $request)
    {
        $query = SosRequest::with('user', 'officer');

        // Province scope for non-admin officers
        if (auth()->user()->hasRole('officer') && !auth()->user()->hasAnyRole(['admin', 'super_admin'])) {
            $province = str_replace('จังหวัด', '', auth()->user()->province);
            $query->where('province', 'like', '%' . $province . '%');
        }

        if ($request->status) $query->where('status', $request->status);
        if ($request->priority) $query->where('priority', $request->priority);
        if ($request->province) $query->where('province', $request->province);

        $sosRequests = $query
            ->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'assigned' THEN 2 WHEN 'in_progress' THEN 3 WHEN 'resolved' THEN 4 WHEN 'safe' THEN 5 ELSE 6 END")
            ->paginate(20);

        return view('officer.sos', compact('sosRequests'));
    }

    public function assign(Request $request, SosRequest $sosRequest)
    {
        $sosRequest->update([
            'officer_id'  => auth()->id(),
            'status'      => 'assigned',
            'assigned_at' => now(),
        ]);
        if ($request->wantsJson()) return response()->json(['success' => true, 'message' => 'รับเคสสำเร็จ']);
        return back()->with('success', 'รับเคสสำเร็จ');
    }

    public function updateStatus(Request $request, SosRequest $sosRequest)
    {
        $request->validate(['status' => 'required|in:pending,assigned,in_progress,resolved,safe']);
        $data = ['status' => $request->status];
        if (in_array($request->status, ['resolved', 'safe'])) {
            $data['resolved_at'] = now();
        }
        $sosRequest->update($data);
        if ($request->wantsJson()) return response()->json(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
        return back()->with('success', 'อัปเดตสถานะสำเร็จ');
    }

    public function navigate(SosRequest $sosRequest)
    {
        abort_unless($sosRequest->latitude && $sosRequest->longitude, 404, 'ไม่มีข้อมูลพิกัด');
        
        $hazards = \App\Models\HazardReport::where('status', '!=', 'resolved')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
            
        return view('officer.navigate', compact('sosRequest', 'hazards'));
    }

    private function analyzePriorityWithAI(Request $request, $fallbackPriority)
    {
        $apiKey = config('services.gemini.key');
        if (empty($apiKey) || empty($request->description)) {
            return $fallbackPriority;
        }

        $prompt = "วิเคราะห์ระดับความรุนแรงของคำขอความช่วยเหลือ SOS ดังต่อไปนี้\n" .
                  "- รายละเอียด/อาการ: " . $request->description . "\n" .
                  "- จำนวนคน: " . $request->num_people . "\n" .
                  "- มีผู้สูงอายุ: " . ($request->has_elderly ? 'ใช่' : 'ไม่') . "\n" .
                  "- มีเด็ก: " . ($request->has_children ? 'ใช่' : 'ไม่') . "\n" .
                  "- มีผู้ป่วยติดเตียง: " . ($request->has_bedridden ? 'ใช่' : 'ไม่') . "\n" .
                  "- มีหญิงตั้งครรภ์: " . ($request->has_pregnant ? 'ใช่' : 'ไม่') . "\n" .
                  "ให้ตอบกลับมาเป็นคำศัพท์ภาษาอังกฤษคำเดียวเท่านั้น จากตัวเลือกต่อไปนี้: 'low', 'medium', 'high', 'critical'";

        try {
            $response = Http::timeout(5)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.2
            ]);

            if ($response->successful()) {
                $text = strtolower(trim($response->json('choices.0.message.content') ?? ''));
                if (in_array($text, ['low', 'medium', 'high', 'critical'])) {
                    return $text;
                }
            }
        } catch (\Exception $e) {
            Log::error('AI Triage Error: ' . $e->getMessage());
        }

        return $fallbackPriority;
    }
}
