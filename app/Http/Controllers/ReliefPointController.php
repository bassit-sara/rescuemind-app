<?php

namespace App\Http\Controllers;

use App\Models\ReliefPoint;
use Illuminate\Http\Request;

class ReliefPointController extends Controller
{
    public function index(Request $request)
    {
        // Admin context: show all (including inactive)
        if ($request->routeIs('admin.*')) {
            $reliefPoints = ReliefPoint::orderBy('type')->paginate(20);
            return view('admin.relief-points.index', compact('reliefPoints'));
        }

        // Public context: only active
        $query = ReliefPoint::where('is_active', true);

        if ($request->province) {
            $query->where('province', $request->province);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }

        $reliefPoints = $query->orderBy('type')->paginate(12);
        $provinces = ReliefPoint::where('is_active', true)->distinct()->pluck('province')->filter()->sort()->values();

        return view('relief-points.index', compact('reliefPoints', 'provinces'));
    }

    public function create()
    {
        return view('admin.relief-points.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'type'              => 'required|in:shelter,hospital,food,parking',
            'province'          => 'nullable|string|max:100',
            'district'          => 'nullable|string|max:100',
            'address'           => 'nullable|string|max:500',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'capacity'          => 'nullable|integer|min:0',
            'current_occupancy' => 'nullable|integer|min:0',
            'available_beds'    => 'nullable|integer|min:0',
            'has_icu'           => 'boolean',
            'ambulance_count'   => 'nullable|integer|min:0',
            'phone'             => 'nullable|string|max:20',
        ]);

        $validated['has_icu'] = $request->boolean('has_icu');
        ReliefPoint::create($validated);
        return redirect()->route('admin.dashboard')->with('success', 'เพิ่มจุดช่วยเหลือสำเร็จ');
    }

    public function edit(ReliefPoint $reliefPoint)
    {
        return view('admin.relief-points.edit', compact('reliefPoint'));
    }

    public function update(Request $request, ReliefPoint $reliefPoint)
    {
        $reliefPoint->update($request->only([
            'name', 'type', 'province', 'district', 'address',
            'latitude', 'longitude', 'capacity', 'current_occupancy',
            'available_beds', 'has_icu', 'ambulance_count', 'phone', 'is_active',
        ]));
        return back()->with('success', 'อัปเดตสำเร็จ');
    }

    public function destroy(ReliefPoint $reliefPoint)
    {
        $reliefPoint->update(['is_active' => false]);
        return back()->with('success', 'ปิดจุดช่วยเหลือสำเร็จ');
    }
}
