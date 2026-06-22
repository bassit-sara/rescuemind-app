<?php

namespace App\Http\Controllers;

use App\Models\SosRequest;
use App\Models\MentalAssessment;
use App\Models\MissingPerson;
use App\Models\HazardReport;
use App\Models\Alert;
use App\Models\ReliefPoint;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalSos = SosRequest::count();
        $resolvedSos = SosRequest::whereIn('status', ['resolved', 'safe'])->count();

        $kpi = [
            'total_users'       => User::count(),
            'new_users_today'   => User::whereDate('created_at', today())->count(),
            'total_sos'         => $totalSos,
            'sos_today'         => SosRequest::whereDate('created_at', today())->count(),
            'rescue_rate'       => $totalSos > 0 ? round($resolvedSos / $totalSos * 100, 1) : 0,
            'assessments_count' => MentalAssessment::count(),
            'active_alerts'     => Alert::where('is_active', true)->count(),
            'relief_points'     => ReliefPoint::where('is_active', true)->count(),
            'missing_persons'   => MissingPerson::where('status', 'missing')->count(),
            'volunteers'        => User::role('volunteer')->count(),
        ];

        // SOS by province (last 30 days)
        $sosByProvince = SosRequest::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('province, count(*) as count')
            ->groupBy('province')
            ->orderByDesc('count')
            ->take(10)
            ->pluck('count', 'province');

        // SOS by status
        $sosByStatus = SosRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Users by role
        $usersByRole = collect();
        foreach (Role::all() as $role) {
            $usersByRole[$role->name] = User::role($role->name)->count();
        }

        // Active SOS Requests for Map
        $activeSosRequests = SosRequest::with('user')
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->latest()->take(100)->get();

        $criticalRiskAreas = \App\Models\RiskArea::whereIn('risk_level', ['critical', 'warning'])
            ->orderByDesc('risk_score')
            ->take(5)->get();

        if (request()->ajax()) {
            return response()->json([
                'activeSosRequests' => $activeSosRequests,
                'kpi' => $kpi
            ]);
        }

        return view('super-admin.dashboard', compact('kpi', 'sosByProvince', 'sosByStatus', 'usersByRole', 'activeSosRequests', 'criticalRiskAreas'));
    }

    public function analytics()
    {
        // Daily SOS stats (last 30 days)
        $dailySos = SosRequest::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Mental assessments by severity
        $mentalBySeverity = MentalAssessment::selectRaw('severity, count(*) as count')
            ->groupBy('severity')->get();

        $sosRequests = SosRequest::with('user')
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->latest()->take(200)->get();
            
        $riskAreas = \App\Models\RiskArea::all();

        return view('super-admin.analytics', compact('dailySos', 'mentalBySeverity', 'sosRequests', 'riskAreas'));
    }

    public function users()
    {
        $users = User::with('roles')->latest()->paginate(20);
        $roles = Role::all();
        return view('super-admin.users', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'province' => 'nullable|string|max:100',
        ]);
        $user->syncRoles([$request->role]);
        $user->update(['province' => $request->province ? 'จังหวัด' . str_replace('จังหวัด', '', $request->province) : null]);
        return back()->with('success', "อัปเดตข้อมูลของ {$user->name} สำเร็จ");
    }

    public function systemLogs()
    {
        $logs = \App\Models\SystemLog::with('user')->latest()->paginate(50);
        return view('super-admin.system-logs', compact('logs'));
    }

    public function shelterMonitoring()
    {
        $shelters = ReliefPoint::where('type', 'shelter')->where('is_active', true)->orderBy('province')->get();
        $hospitals = ReliefPoint::where('type', 'hospital')->where('is_active', true)->orderBy('province')->get();
        return view('super-admin.shelter-monitoring', compact('shelters', 'hospitals'));
    }

    public function resourceMonitoring()
    {
        $resources = Resource::where('is_active', true)->orderBy('province')->orderBy('type')->get();
        return view('super-admin.resource-monitoring', compact('resources'));
    }
}
