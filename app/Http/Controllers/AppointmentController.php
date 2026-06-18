<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = auth()->user()->appointments()->with('mentalOfficer')->latest()->paginate(10);
        return view('mental.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $mentalOfficers = \App\Models\User::role('mental_officer')->get();
        return view('mental.appointments.create', compact('mentalOfficers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mental_officer_id' => 'nullable|exists:users,id',
            'scheduled_at'      => 'required|date|after:now',
            'type'              => 'required|in:video,in_person',
            'notes'             => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Appointment::create($validated);
        return redirect()->route('mental.appointments.create')->with('success', 'นัดหมายสำเร็จ');
    }

    public function show(Appointment $appointment)
    {
        abort_unless($appointment->user_id === auth()->id(), 403);
        return view('mental.appointments.show', compact('appointment'));
    }

    public function officerIndex()
    {
        $appointments = Appointment::with('user')
            ->where('mental_officer_id', auth()->id())
            ->orWhereNull('mental_officer_id')
            ->latest()
            ->paginate(20);
        return view('mental-officer.appointments', compact('appointments'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);
        $appointment->update([
            'status'           => $request->status,
            'mental_officer_id'=> auth()->id(),
        ]);
        return back()->with('success', 'อัปเดตสถานะสำเร็จ');
    }
}
