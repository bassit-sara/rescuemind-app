@extends('layouts.app')
@section('title', 'ติดตาม SOS #' . $sosRequest->id)
@section('page-title')
    ติดตามสถานะ SOS
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Priority Badge --}}
    <div class="priority-{{ $sosRequest->priority }} rounded-2xl p-5 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-500 mb-1">คำขอ SOS #{{ $sosRequest->id }}</div>
                <div class="text-2xl font-bold text-gray-800">{{ $sosRequest->status_label }}</div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                    {{ $sosRequest->priority == 'critical' ? 'bg-red-100 text-red-700' : ($sosRequest->priority == 'high' ? 'bg-orange-100 text-orange-700' : ($sosRequest->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')) }}">
                    {{ strtoupper($sosRequest->priority) }}
                </span>
                <div class="text-xs text-gray-500 mt-1">{{ $sosRequest->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">สถานะการดำเนินการ</h2>
        @php
            $steps = ['pending' => 'รอดำเนินการ', 'assigned' => 'มอบหมายแล้ว', 'in_progress' => 'กำลังช่วยเหลือ', 'resolved' => 'เสร็จสิ้น', 'safe' => 'ปลอดภัย'];
            $stepOrder = array_keys($steps);
            $currentIdx = array_search($sosRequest->status, $stepOrder);
        @endphp
        <div class="relative">
            @foreach($steps as $key => $label)
            @php $idx = array_search($key, $stepOrder); $done = $idx <= $currentIdx; $current = $idx == $currentIdx; @endphp
            <div class="flex items-center gap-4 mb-4 last:mb-0">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                    {{ $done ? ($current ? 'bg-red-600 text-white ring-4 ring-red-100' : 'bg-green-500 text-white') : 'bg-gray-200 text-gray-400' }}">
                    {{ $done && !$current ? '✓' : ($idx + 1) }}
                </div>
                <div>
                    <div class="font-medium {{ $current ? 'text-red-600' : ($done ? 'text-gray-700' : 'text-gray-400') }}">{{ $label }}</div>
                    @if($key == 'assigned' && $sosRequest->assigned_at)
                        <div class="text-xs text-gray-400">{{ $sosRequest->assigned_at->format('d/m H:i') }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Feedback Loop Banner (ETA & Status) --}}
    @if(in_array($sosRequest->status, ['assigned', 'in_progress']))
    <div class="bg-blue-600 rounded-2xl p-5 mb-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-4 -top-4 opacity-10 text-9xl"><x-heroicon-o-paper-airplane class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
        <div class="relative z-10 flex items-start gap-4">
            <div class="text-4xl animate-bounce"><x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /></div>
            <div>
                <h3 class="font-bold text-lg mb-1">เจ้าหน้าที่กำลังดำเนินการ!</h3>
                <p class="text-blue-100 text-sm mb-3">
                    เจ้าหน้าที่ <b>{{ $sosRequest->officer?->name ?? 'ทีมกู้ภัย' }}</b> กำลังปฏิบัติงานช่วยเหลือคุณ<br>
                    ระยะเวลาคาดการณ์ (ETA): <b>ประมาณ 15-30 นาที</b>
                </p>
                <div class="inline-block bg-white text-blue-800 text-xs font-bold px-3 py-1.5 rounded-full">
                    โปรดเตรียมความพร้อมและรอในจุดที่ปลอดภัย
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Details --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">รายละเอียด</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">จังหวัด</span><div class="font-medium">{{ $sosRequest->province ?? '-' }}</div></div>
            <div><span class="text-gray-500">จำนวนผู้ประสบภัย</span><div class="font-medium">{{ $sosRequest->num_people }} คน</div></div>
            <div><span class="text-gray-500">ระดับน้ำ</span><div class="font-medium">{{ $sosRequest->water_level ?? 'ไม่ระบุ' }}</div></div>
            <div><span class="text-gray-500">เจ้าหน้าที่</span><div class="font-medium">{{ $sosRequest->officer?->name ?? 'ยังไม่มอบหมาย' }}</div></div>
        </div>

        @if($sosRequest->urgent_needs && count($sosRequest->urgent_needs) > 0)
        <div class="mt-4 border-t border-gray-100 pt-4">
            <div class="text-xs text-gray-500 mb-2">ความต้องการเร่งด่วน</div>
            <div class="flex flex-wrap gap-2">
                @foreach($sosRequest->urgent_needs as $need)
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full flex items-center gap-1 inline-flex">
                        @if($need == 'food') <x-heroicon-o-cube class="w-4 h-4" /> อาหาร
                        @elseif($need == 'water') <x-heroicon-o-sparkles class="w-4 h-4" /> น้ำดื่ม
                        @elseif($need == 'medicine') <x-heroicon-o-beaker class="w-4 h-4" /> ยารักษาโรค
                        @elseif($need == 'boat') <x-heroicon-o-paper-airplane class="w-4 h-4" /> เรืออพยพ
                        @elseif($need == 'electricity') <x-heroicon-o-battery-100 class="w-4 h-4" /> ไฟฟ้า/พาวแบงก์
                        @elseif($need == 'clothing') <x-heroicon-o-shopping-bag class="w-4 h-4" /> เสื้อผ้า/ผ้าห่ม
                        @else {{ $need }}
                        @endif
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        @if($sosRequest->has_elderly || $sosRequest->has_children || $sosRequest->has_bedridden || $sosRequest->has_pregnant)
        <div class="mt-4 flex flex-wrap gap-2">
            @if($sosRequest->has_elderly) <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full"><x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" /> ผู้สูงอายุ</span> @endif
            @if($sosRequest->has_children) <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full"><x-heroicon-o-face-smile class="w-5 h-5 inline-block mr-1 -mt-1" /> เด็กเล็ก</span> @endif
            @if($sosRequest->has_bedridden) <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full"><x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" />️ ผู้ป่วยติดเตียง</span> @endif
            @if($sosRequest->has_pregnant) <span class="px-2 py-1 bg-pink-100 text-pink-700 text-xs rounded-full"><x-heroicon-o-user-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> หญิงตั้งครรภ์</span> @endif
        </div>
        @endif

        @if($sosRequest->description)
        <div class="mt-4 p-3 bg-gray-50 rounded-xl">
            <div class="text-xs text-gray-500 mb-1">รายละเอียด</div>
            <div class="text-sm text-gray-700">{{ $sosRequest->description }}</div>
        </div>
        @endif

        @if($sosRequest->image_path)
        <div class="mt-4">
            <div class="text-xs text-gray-500 mb-2">ภาพสถานที่เกิดเหตุ</div>
            <div class="rounded-xl overflow-hidden border border-gray-200">
                <img src="{{ Storage::url($sosRequest->image_path) }}" alt="SOS Image" class="w-full object-cover">
            </div>
        </div>
        @endif
    </div>

    {{-- Map --}}
    @if($sosRequest->latitude && $sosRequest->longitude)
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> พิกัด</h2>
            <a href="https://www.google.com/maps?q={{ $sosRequest->latitude }},{{ $sosRequest->longitude }}" target="_blank"
               class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                เปิด Google Maps →
            </a>
        </div>
        <div id="sos-map" class="h-48 rounded-xl"></div>
    </div>
    @endif

    <a href="{{ route('sos.my') }}" class="block text-center py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-2xl transition-colors">
        ← ดู SOS ทั้งหมดของฉัน
    </a>
</div>

@push('scripts')
@if($sosRequest->latitude && $sosRequest->longitude)
<script>
    const map = L.map('sos-map').setView([{{ $sosRequest->latitude }}, {{ $sosRequest->longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([{{ $sosRequest->latitude }}, {{ $sosRequest->longitude }}])
        .addTo(map)
        .bindPopup(`<b><x-heroicon-o-bell class="w-5 h-5 inline-block mr-1 -mt-1" /> จุดเกิดเหตุ SOS #{{ $sosRequest->id }}</b>`).openPopup();

    let volunteerMarker = null;

    if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('tracking.sos.{{ $sosRequest->id }}')
            .listen('.VolunteerLocationUpdated', (e) => {
                if (!volunteerMarker) {
                    const officerIcon = L.divIcon({
                        className: 'officer-icon',
                        html: `<div style="font-size: 24px; text-shadow: 0 0 5px rgba(255,255,255,0.8);"><svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v8l9-11h-7z"></path></svg></div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });
                    volunteerMarker = L.marker([e.latitude, e.longitude], {icon: officerIcon})
                        .addTo(map)
                        .bindPopup('<b>รถกู้ภัย / อาสาสมัครกำลังเดินทางมา</b>');
                        
                    // Adjust map view to fit both SOS and volunteer
                    const bounds = L.latLngBounds([
                        [{{ $sosRequest->latitude }}, {{ $sosRequest->longitude }}],
                        [e.latitude, e.longitude]
                    ]);
                    map.fitBounds(bounds, { padding: [50, 50] });
                } else {
                    volunteerMarker.setLatLng([e.latitude, e.longitude]);
                }
            });
    }
</script>
@endif
@endpush
@endsection
