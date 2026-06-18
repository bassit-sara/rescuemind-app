<?php

namespace App\Http\Controllers;

use App\Models\SosRequest;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function dashboard()
    {
        $volunteer = auth()->user()->volunteer;
        $availableTasks = SosRequest::where('status', 'pending')
            ->where('province', auth()->user()->province)
            ->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
            ->take(10)->get();
        $myTasks = SosRequest::where('volunteer_id', auth()->id())
            ->whereIn('status', ['assigned', 'in_progress'])
            ->get();
        return view('volunteer.dashboard', compact('volunteer', 'availableTasks', 'myTasks'));
    }

    public function tasks()
    {
        $tasks = SosRequest::where('status', 'pending')
            ->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
            ->paginate(15);
        return view('volunteer.tasks', compact('tasks'));
    }

    public function acceptTask(SosRequest $sosRequest)
    {
        $sosRequest->update([
            'volunteer_id' => auth()->id(),
            'status'       => 'in_progress',
            'assigned_at'  => now(),
        ]);
        return back()->with('success', 'รับงานสำเร็จ');
    }

    public function reportTask(Request $request, SosRequest $sosRequest)
    {
        $request->validate(['status' => 'required|in:in_progress,resolved,safe', 'notes' => 'nullable|string']);
        $sosRequest->update(['status' => $request->status, 'notes' => $request->notes]);
        return back()->with('success', 'รายงานสถานการณ์สำเร็จ');
    }
}
