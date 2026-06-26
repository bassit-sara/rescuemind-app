<?php

namespace App\Http\Controllers;

use App\Models\SosRequest;
use App\Models\ReliefPoint;
use App\Models\Resource;
use App\Models\Alert;
use App\Models\User;
use App\Models\MissingPerson;
use App\Models\HazardReport;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $province = auth()->user()->province;

        $stats = [
            'total_sos'      => SosRequest::query()->when($province, fn($q) => $q->where('province', $province))->count(),
            'pending_sos'    => SosRequest::query()->where('status', 'pending')->when($province, fn($q) => $q->where('province', $province))->count(),
            'active_alerts'  => Alert::query()->where('is_active', true)->when($province, fn($q) => $q->where('province', $province)->orWhereNull('province'))->count(),
            'relief_points'  => ReliefPoint::query()->where('is_active', true)->when($province, fn($q) => $q->where('province', $province))->count(),
            'missing_people' => MissingPerson::query()->where('status', 'missing')->when($province, fn($q) => $q->where('province', $province))->count(),
            'total_users'    => User::query()->when($province, fn($q) => $q->where('province', $province))->count(),
        ];

        $shelters = ReliefPoint::query()->where('type', 'shelter')
            ->when($province, fn($q) => $q->where('province', $province))
            ->where('is_active', true)->get();

        $hospitals = ReliefPoint::query()->where('type', 'hospital')
            ->when($province, fn($q) => $q->where('province', $province))
            ->where('is_active', true)->get();

        $resources = Resource::query()->when($province, fn($q) => $q->where('province', $province))
            ->where('is_active', true)->get();

        $recentSos = SosRequest::with('user')
            ->when($province, fn($q) => $q->where('province', $province))
            ->latest()->take(5)->get();

        $activeAlerts = Alert::where('is_active', true)
            ->when($province, fn($q) => $q->where('province', $province)->orWhereNull('province'))
            ->orderByDesc('level')->get();

        return view('admin.dashboard', compact('stats', 'shelters', 'hospitals', 'resources', 'recentSos', 'activeAlerts'));
    }

    public function users(Request $request)
    {
        $province = auth()->user()->province;
        $users = User::with('roles')
            ->when($province && !auth()->user()->hasRole('super_admin'), fn($q) => $q->where('province', $province))
            ->when($request->search, function($q, $search) {
                $q->where(function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->role, function($q, $role) {
                $q->whereHas('roles', fn($query) => $query->where('name', $role));
            })
            ->latest()->paginate(20)->withQueryString();
        $roles = Role::all();
        return view('admin.users', compact('users', 'roles'));
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

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
        }
        
        $province = auth()->user()->province;
        if ($province && !auth()->user()->hasRole('super_admin') && $user->province !== $province) {
            return back()->with('error', 'ไม่มีสิทธิ์ลบผู้ใช้ในจังหวัดอื่น');
        }

        $user->delete();
        return back()->with('success', "ลบผู้ใช้ {$user->name} สำเร็จ");
    }
}
