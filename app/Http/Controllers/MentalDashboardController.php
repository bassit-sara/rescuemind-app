<?php

namespace App\Http\Controllers;

use App\Models\MentalAssessment;
use App\Models\Appointment;

class MentalDashboardController extends Controller
{
    public function index()
    {
        $severeAssessments = MentalAssessment::with('user')
            ->whereIn('severity', ['moderate', 'severe'])
            ->latest()->take(10)->get();

        $pendingAppointments = Appointment::with('user')
            ->where(function($q) {
                $q->where('mental_officer_id', auth()->id())
                  ->orWhereNull('mental_officer_id');
            })
            ->where('status', 'pending')
            ->latest()->take(10)->get();

        // Severity distribution last 7 days
        $severityDist = MentalAssessment::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('severity, count(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity');

        $stats = [
            'total_assessments'   => MentalAssessment::count(),
            'severe_cases'        => MentalAssessment::where('severity', 'severe')->count(),
            'pending_appointments'=> Appointment::where('status', 'pending')->count(),
            'today_appointments'  => Appointment::whereDate('scheduled_at', today())->where('status', 'confirmed')->count(),
        ];

        return view('mental-officer.dashboard', compact(
            'severeAssessments', 'pendingAppointments', 'stats', 'severityDist'
        ));
    }
}
