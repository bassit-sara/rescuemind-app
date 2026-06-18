<?php

namespace App\Http\Controllers;

use App\Models\SosRequest;
use App\Models\MissingPerson;
use App\Models\HazardReport;
use App\Models\Alert;
use Illuminate\Http\Request;

class OfficerDashboardController extends Controller
{
    public function index()
    {
        $province = auth()->user()->province;

        $pendingSos = SosRequest::where('status', 'pending')
            ->when($province, fn($q) => $q->where('province', $province))
            ->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
            ->take(10)->get();

        $myActiveSos = SosRequest::where('officer_id', auth()->id())
            ->whereIn('status', ['assigned', 'in_progress'])
            ->get();

        $stats = [
            'pending'     => SosRequest::where('status', 'pending')->when($province, fn($q) => $q->where('province', $province))->count(),
            'in_progress' => SosRequest::where('status', 'in_progress')->when($province, fn($q) => $q->where('province', $province))->count(),
            'resolved'    => SosRequest::whereIn('status', ['resolved', 'safe'])->when($province, fn($q) => $q->where('province', $province))->whereDate('updated_at', today())->count(),
            'missing'     => MissingPerson::where('status', 'missing')->when($province, fn($q) => $q->where('province', $province))->count(),
        ];

        $recentReports = HazardReport::with('reporter')
            ->when($province, fn($q) => $q->where('province', $province))
            ->latest()->take(5)->get();

        // Map Data
        $allActiveSos = SosRequest::whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->when($province, fn($q) => $q->where('province', $province))
            ->get();

        return view('officer.dashboard', compact('pendingSos', 'myActiveSos', 'stats', 'recentReports', 'allActiveSos'));
    }
}
