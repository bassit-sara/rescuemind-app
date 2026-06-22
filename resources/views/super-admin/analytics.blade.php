@extends('layouts.admin')
@section('title', 'ระบบวิเคราะห์สถิติมุมมองระดับประเทศ')
@section('page-title')
    <x-heroicon-o-presentation-chart-line class="w-5 h-5 inline-block shrink-0" /> National Analytics & SOS Map
@endsection
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-800">ศูนย์วิเคราะห์ข้อมูลภัยพิบัติแห่งชาติ (Analytics Center)</h1>
            <p class="text-sm text-gray-500 mt-1">วิเคราะห์แนวโน้ม SOS, สภาพจิตใจของประชาชน และพื้นที่เสี่ยงระดับจังหวัด</p>
        </div>
        <a href="{{ route('super-admin.dashboard') }}" class="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl text-sm transition-colors shadow-sm">
            <x-heroicon-o-globe-alt class="w-5 h-5 inline-block shrink-0" /> กลับหน้าแดชบอร์ดหลัก
        </a>
    </div>

    {{-- Map Container --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-4">
        <h2 class="font-bold text-gray-800 flex items-center gap-2">
            <x-heroicon-o-signal class="w-5 h-5 inline-block mr-1 -mt-1" /> แผนที่รับสัญญาณเหตุฉุกเฉินระดับประเทศ (200 เหตุล่าสุด)
        </h2>
        <div id="analytics-map" class="w-full h-[450px] rounded-xl border border-gray-100 overflow-hidden"></div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Mental Health distribution --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4"><x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" /> สัดส่วนความรุนแรงด้านสุขภาพจิตของประชาชน</h3>
            <div class="space-y-4">
                @forelse($mentalBySeverity as $m)
                    @php
                        $color = match($m->severity) {
                            'severe' => 'bg-red-500 text-white',
                            'moderate' => 'bg-orange-500 text-white',
                            'mild' => 'bg-yellow-500 text-gray-800',
                            default => 'bg-green-500 text-white'
                        };
                        $lbl = match($m->severity) {
                            'severe' => 'ระดับรุนแรง',
                            'moderate' => 'ระดับปานกลาง',
                            'mild' => 'ระดับน้อย',
                            default => 'น้อยมาก / ปกติ'
                        };
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1.5 font-medium">
                            <span class="text-gray-700">{{ $lbl }} ({{ strtoupper($m->severity) }})</span>
                            <span class="text-gray-900 font-bold">{{ $m->count }} เคส</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3.5 overflow-hidden">
                            @php
                                $totalM = max(1, $mentalBySeverity->sum('count'));
                                $percentage = round(($m->count / $totalM) * 100);
                            @endphp
                            <div class="{{ $color }} h-full transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400 text-sm">
                        ไม่มีข้อมูลประเมินสุขภาพจิตในระบบ
                    </div>
                @endforelse
            </div>
        </div>

        {{-- SOS Trends --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-800 mb-4"><x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> แนวโน้มความช่วยเหลือ SOS รายวัน (30 วันล่าสุด)</h3>
                <div class="space-y-3">
                    @forelse($dailySos->take(5) as $d)
                        <div class="flex items-center justify-between text-sm py-2 border-b border-gray-50">
                            <span class="font-semibold text-gray-700">{{ $d->date }}</span>
                            <span class="px-2.5 py-1 bg-red-50 border border-red-100 text-red-700 rounded-lg font-bold">
                                {{ $d->count }} การร้องขอ
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400 text-sm">
                            ไม่มีประวัติ SOS ใน 30 วันล่าสุด
                        </div>
                    @endforelse
                </div>
            </div>
            @if($dailySos->count() > 5)
                <div class="text-xs text-gray-400 text-center mt-4">
                    แสดงเฉพาะ 5 วันที่มีสถิติสูงสุด
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Set view to Southern Thailand since we are focusing on it
        var map = L.map('analytics-map').setView([6.8, 100.8], 8);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var riskAreas = [
            @if(isset($riskAreas))
                @foreach($riskAreas as $area)
                    {
                        lat: {{ $area->latitude }},
                        lng: {{ $area->longitude }},
                        name: "{{ $area->area_name }}",
                        score: {{ $area->risk_score }},
                        level: "{{ $area->risk_level }}",
                        prediction: "{{ $area->prediction_text }}",
                        sos: {{ $area->sos_count }},
                        water: {{ $area->water_score }}
                    },
                @endforeach
            @endif
        ];

        var riskBounds = [];

        riskAreas.forEach(function(area) {
            // Colors: Safe(Green), Watch(Yellow), Warning(Orange), Critical(Red)
            var color = area.level === 'critical' ? '#EF4444' : (area.level === 'warning' ? '#F97316' : (area.level === 'watch' ? '#EAB308' : '#22C55E'));
            var statusTh = area.level === 'critical' ? 'วิกฤติ' : (area.level === 'warning' ? 'เตรียมอพยพ' : (area.level === 'watch' ? 'เฝ้าระวัง' : 'ปลอดภัย'));
            
            // Draw circle to represent the area zone (radius in meters, 15000m = 15km for better visibility in South)
            var circle = L.circle([area.lat, area.lng], {
                color: color,
                fillColor: color,
                fillOpacity: 0.35,
                weight: 2,
                radius: 3000
            }).addTo(map);

            riskBounds.push([area.lat, area.lng]);

            var popupContent = `
                <div class="font-sans min-w-[200px]">
                    <h3 class="font-bold text-lg mb-2" style="color: ${color};">${area.name}</h3>
                    <div class="text-sm space-y-1">
                        <div><b>ความเสี่ยง:</b> ${area.score}%</div>
                        <div><b>สถานะ:</b> ${statusTh}</div>
                        <div><b>SOS:</b> ${area.sos} เคส</div>
                        <div><b>ระดับน้ำ (Score):</b> ${area.water}/100</div>
                    </div>
                    <hr class="my-2">
                    <div class="text-xs bg-gray-50 p-2 rounded border border-gray-200">
                        <span class="font-bold">คาดการณ์ (AI Prediction):</span><br>
                        ${area.prediction}
                    </div>
                </div>
            `;
            circle.bindPopup(popupContent);
        });

        // Still show the active SOS markers on top
        var sosMarkers = [
            @if(isset($sosRequests))
                @foreach($sosRequests as $req)
                    {
                        lat: {{ $req->latitude }},
                        lng: {{ $req->longitude }},
                        name: "{{ $req->user ? $req->user->name : ($req->guest_name ? $req->guest_name . ' (Guest)' : 'ไม่ระบุ') }}",
                        prio: "{{ $req->priority }}",
                        prov: "{{ $req->province }}",
                        desc: "{{ Str::limit($req->description, 50) }}"
                    },
                @endforeach
            @endif
        ];

        sosMarkers.forEach(function(m) {
            var color = m.prio === 'critical' ? '#EF4444' : (m.prio === 'high' ? '#F97316' : '#EAB308');
            var circle = L.circleMarker([m.lat, m.lng], {
                color: color,
                fillColor: color,
                fillOpacity: 0.8,
                radius: 5 // smaller for individual SOS
            }).addTo(map);

            riskBounds.push([m.lat, m.lng]);
            circle.bindPopup("<b>" + m.name + " (" + m.prov + ")</b><br>ระดับ: " + m.prio.toUpperCase() + "<br>" + m.desc);
        });

        // Fit map to markers if there are any
        if (riskBounds.length > 0) {
            map.fitBounds(riskBounds, { padding: [50, 50], maxZoom: 10 });
        }
    });
</script>
@endpush
@endsection
