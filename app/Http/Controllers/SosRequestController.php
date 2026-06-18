<?php

namespace App\Http\Controllers;

use App\Models\SosRequest;
use Illuminate\Http\Request;

class SosRequestController extends Controller
{
    public function create()
    {
        return view('sos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'description'   => 'nullable|string|max:1000',
        ]);

        // AI Triage logic (rule-based)
        $priority = 'medium';
        if ($request->has_bedridden || $request->has_pregnant) {
            $priority = 'critical';
        } elseif ($request->has_elderly || $request->has_children) {
            $priority = 'high';
        } elseif ($request->num_people >= 10) {
            $priority = 'high';
        }

        $sos = SosRequest::create(array_merge($validated, [
            'user_id'      => auth()->id(),
            'status'       => 'pending',
            'priority'     => $priority,
            'has_elderly'  => $request->boolean('has_elderly'),
            'has_children' => $request->boolean('has_children'),
            'has_bedridden'=> $request->boolean('has_bedridden'),
            'has_pregnant' => $request->boolean('has_pregnant'),
        ]));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'ส่งคำขอความช่วยเหลือสำเร็จ! เจ้าหน้าที่จะติดต่อกลับโดยเร็ว',
                'redirect' => route('sos.my') // Redirect to history per the summary
            ]);
        }

        return redirect()->route('sos.show', $sos)->with('success', 'ส่งคำขอความช่วยเหลือสำเร็จ! เจ้าหน้าที่จะติดต่อกลับโดยเร็ว');
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
        return back()->with('success', 'อัปเดตสถานะสำเร็จ');
    }
}
