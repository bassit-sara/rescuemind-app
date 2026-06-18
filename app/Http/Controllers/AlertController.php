<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::where('is_active', true)
            ->orderByDesc('level')
            ->orderByDesc('issued_at')
            ->get();
        return view('alerts.index', compact('alerts'));
    }

    public function show(Alert $alert)
    {
        return view('alerts.show', compact('alert'));
    }

    public function adminIndex()
    {
        $alerts = Alert::orderByDesc('created_at')->paginate(20);
        return view('admin.alerts.index', compact('alerts'));
    }

    public function edit(Alert $alert)
    {
        return view('admin.alerts.edit', compact('alert'));
    }

    public function create()
    {
        return view('admin.alerts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'message'      => 'required|string',
            'level'        => 'required|integer|in:1,2,3',
            'province'     => 'nullable|string|max:100',
            'disaster_type'=> 'required|in:flood,landslide,storm,wildfire,pm25,other',
            'issued_at'    => 'nullable|date',
            'expires_at'   => 'nullable|date|after:issued_at',
        ]);

        $validated['issued_by'] = auth()->id();
        $validated['issued_at'] = $validated['issued_at'] ?? now();
        $validated['is_active'] = true;

        Alert::create($validated);
        return redirect()->route('admin.dashboard')->with('success', 'สร้างการแจ้งเตือนสำเร็จ');
    }

    public function update(Request $request, Alert $alert)
    {
        $alert->update($request->only(['title', 'message', 'level', 'is_active', 'expires_at']));
        return back()->with('success', 'อัปเดตการแจ้งเตือนสำเร็จ');
    }

    public function destroy(Alert $alert)
    {
        $alert->update(['is_active' => false]);
        return back()->with('success', 'ยกเลิกการแจ้งเตือนสำเร็จ');
    }
}
