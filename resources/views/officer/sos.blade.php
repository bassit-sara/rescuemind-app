@extends('layouts.admin')
@section('title', 'รายการ SOS ทั้งหมด')
@section('page-title', '🆘 คิว SOS ทั้งหมด')
@section('content')

{{-- Filter --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <option value="">ทุกสถานะ</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>รอดำเนินการ</option>
            <option value="assigned" {{ request('status')=='assigned'?'selected':'' }}>มอบหมายแล้ว</option>
            <option value="in_progress" {{ request('status')=='in_progress'?'selected':'' }}>กำลังช่วยเหลือ</option>
            <option value="resolved" {{ request('status')=='resolved'?'selected':'' }}>เสร็จสิ้น</option>
            <option value="safe" {{ request('status')=='safe'?'selected':'' }}>ปลอดภัย</option>
        </select>
        <select name="priority" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <option value="">ทุกความเร่งด่วน</option>
            <option value="critical" {{ request('priority')=='critical'?'selected':'' }}>วิกฤต</option>
            <option value="high" {{ request('priority')=='high'?'selected':'' }}>เร่งด่วน</option>
            <option value="medium" {{ request('priority')=='medium'?'selected':'' }}>ปานกลาง</option>
            <option value="low" {{ request('priority')=='low'?'selected':'' }}>ต่ำ</option>
        </select>
        <input type="text" name="province" value="{{ request('province') }}" placeholder="จังหวัด..."
               class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
        <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700">ค้นหา</button>
        <a href="{{ route('officer.sos.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm hover:bg-gray-200">ล้าง</a>
    </form>
</div>

{{-- SOS List --}}
<div class="space-y-3">
    @forelse($sosRequests as $sos)
    <div class="priority-{{ $sos->priority }} rounded-2xl p-5 bg-white shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-2">
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full
                        {{ $sos->priority == 'critical' ? 'bg-red-200 text-red-800' : ($sos->priority == 'high' ? 'bg-orange-200 text-orange-800' : ($sos->priority == 'medium' ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-200 text-gray-700')) }}">
                        {{ strtoupper($sos->priority) }}
                    </span>
                    <span class="px-2 py-0.5 text-xs rounded-full
                        {{ $sos->status == 'safe' ? 'bg-green-100 text-green-700' : ($sos->status == 'pending' ? 'bg-red-100 text-red-700' : ($sos->status == 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                        {{ $sos->status_label }}
                    </span>
                    <span class="text-xs text-gray-400">#{{ $sos->id }} • {{ $sos->created_at->diffForHumans() }}</span>
                </div>
                <div class="font-bold text-gray-800">{{ $sos->province ?? 'ไม่ระบุจังหวัด' }}</div>
                <div class="text-sm text-gray-600 mt-0.5">{{ $sos->num_people }} คน{{ $sos->address ? ' • '.$sos->address : '' }}</div>
                @if($sos->user)
                <div class="text-xs text-gray-400 mt-1">แจ้งโดย: {{ $sos->user->name }} • {{ $sos->user->phone ?? 'ไม่มีเบอร์' }}</div>
                @endif
                <div class="flex flex-wrap gap-1.5 mt-2">
                    @if($sos->has_elderly) <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">👴ผู้สูงอายุ</span> @endif
                    @if($sos->has_children) <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">👶เด็ก</span> @endif
                    @if($sos->has_pregnant) <span class="text-xs bg-pink-100 text-pink-700 px-2 py-0.5 rounded-full">🤰ตั้งครรภ์</span> @endif
                    @if($sos->has_bedridden) <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">🛏️ติดเตียง</span> @endif
                    @if($sos->has_disabled) <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">♿พิการ</span> @endif
                </div>
            </div>

            <div class="flex flex-col gap-2 flex-shrink-0">
                @if($sos->latitude)
                <a href="https://www.google.com/maps?q={{ $sos->latitude }},{{ $sos->longitude }}" target="_blank"
                   class="px-3 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 text-center">🗺️ GPS</a>
                @endif
                @if($sos->status == 'pending')
                <form action="{{ route('officer.sos.assign', $sos) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white text-xs font-bold rounded-xl hover:bg-red-700">รับเคส</button>
                </form>
                @elseif(in_array($sos->status, ['assigned','in_progress']))
                <form action="{{ route('officer.sos.status', $sos) }}" method="POST" class="flex flex-col gap-1">
                    @csrf @method('PATCH')
                    <select name="status" class="text-xs border border-gray-300 rounded-lg px-2 py-1.5">
                        <option value="in_progress">กำลังช่วย</option>
                        <option value="resolved">เสร็จสิ้น</option>
                        <option value="safe">ปลอดภัย</option>
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700">อัปเดต</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-4">✅</div>
        <div class="text-xl font-medium">ไม่พบรายการ SOS</div>
        <p class="text-sm mt-2">ลองเปลี่ยนตัวกรองการค้นหา</p>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $sosRequests->withQueryString()->links() }}</div>
@endsection
