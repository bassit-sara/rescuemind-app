<?php

namespace App\Http\Controllers;

use App\Models\HazardReport;
use Illuminate\Http\Request;

class HazardReportController extends Controller
{
    public function index()
    {
        $reports = HazardReport::with('reporter')->latest()->paginate(12);
        return view('hazard-reports.index', compact('reports'));
    }

    public function create()
    {
        return view('hazard-reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'        => 'required|in:flood,landslide,road_blocked,bridge_damaged,power_outage,fire,other',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'province'    => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('hazard-reports', 'public');
        }

        $validated['reporter_id'] = auth()->id();
        HazardReport::create($validated);

        return redirect()->route('hazard-reports.index')->with('success', 'รายงานภัยสำเร็จ ขอบคุณสำหรับข้อมูล');
    }

    public function show(HazardReport $hazardReport)
    {
        return view('hazard-reports.show', compact('hazardReport'));
    }

    public function officerIndex()
    {
        $reports = HazardReport::with('reporter')->latest()->paginate(20);
        return view('officer.hazard.index', compact('reports'));
    }

    public function verify(HazardReport $hazardReport)
    {
        $hazardReport->update(['verified' => true, 'status' => 'verified']);
        return back()->with('success', 'ยืนยันรายงานสำเร็จ');
    }
}
