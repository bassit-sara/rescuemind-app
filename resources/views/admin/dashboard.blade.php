@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title')
    <x-heroicon-o-cog-8-tooth class="w-5 h-5 inline-block shrink-0" /> Admin Dashboard
@endsection
@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-indigo-600">{{ $stats['total_users'] }}</div>
        <div class="text-sm text-indigo-700 font-medium">ผู้ใช้ทั้งหมด</div>
    </div>
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-red-600">{{ $stats['pending_sos'] }}</div>
        <div class="text-sm text-red-700 font-medium">SOS รอดำเนินการ</div>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-green-600">{{ $stats['relief_points'] }}</div>
        <div class="text-sm text-green-700 font-medium">จุดช่วยเหลือเปิด</div>
    </div>
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-amber-600">{{ $stats['active_alerts'] }}</div>
        <div class="text-sm text-amber-700 font-medium">แจ้งเตือนใช้งาน</div>
    </div>
</div>

{{-- Weather Widget --}}
<div class="mb-6">
    <x-weather-widget />
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Quick Admin Tools --}}
    <div class="lg:col-span-1 space-y-3">
        <a href="{{ route('admin.alerts.create') }}" class="flex items-center gap-3 p-4 bg-red-600 text-white rounded-2xl hover:bg-red-700 transition-colors">
            <span class="text-2xl"><x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /></span>
            <div>
                <div class="font-bold">ออกประกาศแจ้งเตือน</div>
                <div class="text-xs text-red-100">สร้าง Alert ใหม่</div>
            </div>
        </a>
        <a href="{{ route('admin.relief-points.create') }}" class="flex items-center gap-3 p-4 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition-colors">
            <span class="text-2xl"><x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /></span>
            <div>
                <div class="font-bold">เพิ่มจุดช่วยเหลือ</div>
                <div class="text-xs text-green-100">เพิ่ม Relief Point</div>
            </div>
        </a>
        <a href="{{ route('admin.resources.create') }}" class="flex items-center gap-3 p-4 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
            <span class="text-2xl"><x-heroicon-o-archive-box class="w-5 h-5 inline-block shrink-0" /></span>
            <div>
                <div class="font-bold">จัดการทรัพยากร</div>
                <div class="text-xs text-blue-100">เพิ่มวัสดุ/อุปกรณ์</div>
            </div>
        </a>
        <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-4 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition-colors">
            <span class="text-2xl"><x-heroicon-o-users class="w-5 h-5 inline-block shrink-0" /></span>
            <div>
                <div class="font-bold">จัดการผู้ใช้</div>
                <div class="text-xs text-indigo-100">กำหนดบทบาท/สิทธิ์</div>
            </div>
        </a>
    </div>

    {{-- Recent SOS --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800"><x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /> SOS ล่าสุด</h2>
            <a href="{{ route('officer.sos.index') }}" class="text-sm text-red-600 hover:underline">จัดการ SOS →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentSos as $sos)
            <div class="priority-{{ $sos->priority }} mx-4 my-2 p-3 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-800">{{ $sos->user ? $sos->user->name : ($sos->guest_name ? $sos->guest_name . ' (Guest)' : 'ไม่ระบุ') }} • {{ $sos->province }}</div>
                        <div class="text-xs text-gray-500">{{ $sos->num_people }} คน • {{ $sos->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $sos->status == 'safe' ? 'bg-green-100 text-green-700' : ($sos->status == 'pending' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ $sos->status_label }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">ไม่มี SOS</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Alert Management --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-800"><x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /> การแจ้งเตือนที่ใช้งานอยู่</h2>
        <a href="{{ route('admin.alerts.create') }}" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700">+ เพิ่ม</a>
    </div>
    <div class="divide-y divide-gray-50">
        @forelse($activeAlerts as $alert)
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center gap-3">
                <span class="text-xl">{!! $alert->level == 3 ? '<span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-1"></span>' : ($alert->level == 2 ? '<span class="inline-block w-3 h-3 rounded-full bg-orange-500 mr-1"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-1"></span>') !!}</span>
                <div>
                    <div class="font-medium text-gray-800">{{ $alert->title }}</div>
                    <div class="text-xs text-gray-500">{{ $alert->province ?? 'ทั่วประเทศ' }} • {{ $alert->issued_at?->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.alerts.update', $alert) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="is_active" value="0">
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-lg hover:bg-gray-200">ปิด</button>
                </form>
                <form action="{{ route('admin.alerts.destroy', $alert) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('ลบการแจ้งเตือนนี้?')" class="px-3 py-1.5 bg-red-100 text-red-600 text-xs rounded-lg hover:bg-red-200">ลบ</button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-400">ไม่มีการแจ้งเตือน</div>
        @endforelse
    </div>
</div>
@endsection
