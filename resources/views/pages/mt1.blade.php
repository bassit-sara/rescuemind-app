@extends('layouts.app')

@section('title', 'MT1 ก่อนเกิดภัย (Early Warning & Preparedness)')
@section('page-title')
    มิติที่ 1: ก่อนเกิดภัย
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Back Button --}}
    <div>
        <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ย้อนกลับหน้าหลัก
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="text-5xl mb-4"><x-heroicon-o-globe-asia-australia class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
            <h1 class="text-3xl font-bold mb-2">ก่อนเกิดภัย (Preparedness)</h1>
            <p class="text-blue-100 text-lg max-w-2xl">
                เตรียมความพร้อมก่อนเกิดเหตุการณ์ฉุกเฉิน ติดตามการแจ้งเตือนภัย ค้นหาจุดช่วยเหลือที่ใกล้ที่สุด และเช็คลิสต์สิ่งที่ต้องเตรียมพร้อม
            </p>
        </div>
    </div>

    {{-- AI Risk Areas Map --}}
    @if(isset($riskAreas) && count($riskAreas) > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 text-xl mb-4 flex items-center gap-2">
            <span class="text-2xl"><x-heroicon-o-cpu-chip class="w-5 h-5 inline-block mr-1 -mt-1" /></span> AI Risk Monitoring (พื้นที่เสี่ยงภัย)
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Text Explanation --}}
            <div class="lg:col-span-1 space-y-4 flex flex-col justify-center">
                <p class="text-gray-600 text-sm leading-relaxed">
                    ระบบ AI ของศูนย์บัญชาการ (RescueMind) ทำการวิเคราะห์ข้อมูลพื้นที่แบบเรียลไทม์ 
                    โดยอ้างอิงจาก <strong>ปริมาณน้ำฝน ระดับน้ำ การร้องขอความช่วยเหลือ (SOS)</strong> 
                    เพื่อจำแนกระดับความเสี่ยงและแจ้งเตือนประชาชนล่วงหน้า:
                </p>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-red-50 border border-red-100">
                        <span class="w-4 h-4 rounded-full bg-red-500 shrink-0 mt-0.5"></span>
                        <div>
                            <strong class="text-red-800 block">ระดับวิกฤติ (Critical)</strong>
                            <span class="text-red-600 text-xs">ต้องอพยพทันที อันตรายต่อชีวิต</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-orange-50 border border-orange-100">
                        <span class="w-4 h-4 rounded-full bg-orange-500 shrink-0 mt-0.5"></span>
                        <div>
                            <strong class="text-orange-800 block">ระดับเตรียมอพยพ (Warning)</strong>
                            <span class="text-orange-600 text-xs">เก็บของขึ้นที่สูง เตรียมย้ายไปยังศูนย์พักพิง</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-yellow-50 border border-yellow-100">
                        <span class="w-4 h-4 rounded-full bg-yellow-400 shrink-0 mt-0.5"></span>
                        <div>
                            <strong class="text-yellow-800 block">ระดับเฝ้าระวัง (Watch)</strong>
                            <span class="text-yellow-600 text-xs">ติดตามข่าวสารอย่างใกล้ชิด อาจมีน้ำท่วมขัง</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
                        <span class="w-4 h-4 rounded-full bg-green-500 shrink-0 mt-0.5"></span>
                        <div>
                            <strong class="text-green-800 block">ระดับปลอดภัย (Safe)</strong>
                            <span class="text-green-600 text-xs">สถานการณ์ปกติ ไม่มีเหตุร้ายแรง</span>
                        </div>
                    </li>
                </ul>
                <p class="text-xs text-gray-400 mt-2">
                    * คุณสามารถคลิกที่วงกลมบนแผนที่เพื่อดูการพยากรณ์จาก AI ได้
                </p>
            </div>

            {{-- Map Container --}}
            <div class="lg:col-span-2">
                <div id="risk-map" class="w-full h-[400px] md:h-[500px] rounded-xl border border-gray-200 shadow-inner overflow-hidden relative z-0"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Features Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <a href="{{ route('alerts.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" />
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">การแจ้งเตือนภัย</h2>
            <p class="text-sm text-gray-500">ติดตามประกาศเตือนภัยจากหน่วยงานรัฐ และดูพื้นที่เสี่ยงภัย</p>
        </a>

        <a href="{{ route('relief-points.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" />
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">จุดช่วยเหลือ & อพยพ</h2>
            <p class="text-sm text-gray-500">ค้นหาสถานที่หลบภัย ศูนย์อพยพ และจุดแจกจ่ายสิ่งของบรรเทาทุกข์</p>
        </a>

        <a href="{{ route('preparedness.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" />
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">เตรียมพร้อมรับมือ</h2>
            <p class="text-sm text-gray-500">เช็คลิสต์กระเป๋าฉุกเฉิน และคำแนะนำการปฏิบัติตัวเมื่อเกิดภัย</p>
        </a>

    </div>

    {{-- Call to Action / Info --}}
    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 flex items-center gap-4">
        <div class="text-4xl"><x-heroicon-o-light-bulb class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
        <div>
            <h3 class="font-bold text-blue-900">รู้หรือไม่?</h3>
            <p class="text-sm text-blue-700">การเตรียมพร้อมล่วงหน้าสามารถลดความสูญเสียเมื่อเกิดภัยพิบัติได้มากกว่า 50% ตรวจสอบจุดอพยพใกล้บ้านคุณตั้งแต่วันนี้</p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(isset($riskAreas) && count($riskAreas) > 0)
        var map = L.map('risk-map').setView([6.8, 100.8], 8);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var riskAreas = [
            @foreach($riskAreas as $area)
                {
                    lat: {{ $area->latitude }},
                    lng: {{ $area->longitude }},
                    name: "{{ $area->area_name }}",
                    score: {{ $area->risk_score }},
                    level: "{{ $area->risk_level }}",
                    prediction: "{{ $area->prediction_text }}"
                },
            @endforeach
        ];

        var riskBounds = [];

        riskAreas.forEach(function(area) {
            var color = area.level === 'critical' ? '#EF4444' : (area.level === 'warning' ? '#F97316' : (area.level === 'watch' ? '#EAB308' : '#22C55E'));
            var statusTh = area.level === 'critical' ? 'วิกฤติ' : (area.level === 'warning' ? 'เตรียมอพยพ' : (area.level === 'watch' ? 'เฝ้าระวัง' : 'ปลอดภัย'));
            
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

        if (riskBounds.length > 0) {
            map.fitBounds(riskBounds, { padding: [50, 50], maxZoom: 10 });
        }
        @endif
    });
</script>
@endpush
