@extends('layouts.admin')
@section('title', 'AI Navigation')
@section('page-title')
    <x-heroicon-o-map class="w-5 h-5 inline-block shrink-0" /> นำทาง (AI Route)
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        #map-container { height: calc(100vh - 140px); width: 100%; border-radius: 1rem; position: relative; overflow: hidden; z-index: 10; }
        .ai-overlay {
            position: absolute; inset: 0; background: rgba(0,0,0,0.7); z-index: 1000;
            display: flex; flex-direction: column; align-items: center; justify-content: center; color: white;
            transition: opacity 0.5s;
        }
        .ai-overlay.hidden-overlay { opacity: 0; pointer-events: none; }
        .radar-spinner {
            width: 80px; height: 80px; border: 4px solid rgba(255,255,255,0.3); border-top-color: #3b82f6;
            border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        
        /* Custom Routing Machine Styles */
        .leaflet-routing-container { background: white; border-radius: 12px; padding: 10px; max-height: 250px !important; overflow-y: auto; font-size: 13px; font-family: 'Noto Sans Thai', sans-serif;}
    </style>
@endpush

@section('content')

<div class="mb-4">
    <a href="{{ route('officer.sos.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-800">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> กลับหน้ารายการ
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 lg:p-4 relative">
    
    {{-- Header Info --}}
    <div class="flex flex-wrap justify-between items-center px-2 py-2 mb-2 gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">เป้าหมาย: ช่วยเหลือผู้ประสบภัย #{{ $sosRequest->id }}</h2>
            <div class="text-sm text-gray-500">{{ $sosRequest->num_people }} คน • {{ $sosRequest->address ?? $sosRequest->province }}</div>
        </div>
        <div class="flex items-center gap-2">
            <div class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs font-bold shadow-sm border border-blue-100">
                <span class="relative flex h-2.5 w-2.5 mr-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                </span>
                AI Route
            </div>

            @if(in_array($sosRequest->status, ['assigned', 'in_progress']))
            <form action="{{ route('officer.sos.status', $sosRequest) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="safe">
                <button type="submit" class="px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-full hover:bg-green-600 transition-colors shadow-sm" onclick="return confirm('ยืนยันจบภารกิจและช่วยเหลือผู้ประสบภัยปลอดภัยแล้ว?')">
                    <x-heroicon-o-check-circle class="w-5 h-5 inline-block mr-1 -mt-1" /> จบภารกิจ (ปลอดภัย)
                </button>
            </form>
            @elseif(in_array($sosRequest->status, ['safe', 'resolved']))
            <div class="px-3 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full border border-green-200">
                ภารกิจสำเร็จแล้ว
            </div>
            @endif
        </div>
    </div>

    {{-- Map --}}
    <div id="map-container" class="shadow-inner border border-gray-200">
        {{-- AI Loading Overlay --}}
        <div id="ai-loading" class="ai-overlay">
            <div class="radar-spinner"></div>
            <div class="text-xl font-bold mb-2 text-blue-400">AI กำลังวิเคราะห์เส้นทาง...</div>
            <div class="text-sm text-gray-300">กำลังสแกนจุดอันตรายและคำนวณเส้นทางที่ปลอดภัยที่สุด</div>
        </div>
        
        <div id="navigation-map" class="w-full h-full"></div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const targetLat = {{ $sosRequest->latitude }};
    const targetLng = {{ $sosRequest->longitude }};
    
    // Initialize map
    const map = L.map('navigation-map').setView([targetLat, targetLng], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // Target Marker
    const targetIcon = L.divIcon({
        className: 'custom-icon',
        html: `<div style="font-size: 24px; text-shadow: 0 0 5px rgba(255,255,255,0.8);"><x-heroicon-o-bell class="w-5 h-5 inline-block mr-1 -mt-1" /></div>`,
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });
    L.marker([targetLat, targetLng], {icon: targetIcon})
        .addTo(map)
        .bindPopup('<b>เป้าหมาย SOS #{{ $sosRequest->id }}</b><br>รอความช่วยเหลือ').openPopup();

    // Hazard Markers
    const hazards = @json($hazards);
    hazards.forEach(hazard => {
        let emoji = `<x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block mr-1 -mt-1" />️`;
        if(hazard.type === 'flood') emoji = `<x-heroicon-o-globe-asia-australia class="w-5 h-5 inline-block mr-1 -mt-1" />`;
        else if(hazard.type === 'fire') emoji = `<x-heroicon-o-fire class="w-5 h-5 inline-block mr-1 -mt-1" />`;
        else if(hazard.type === 'road_blocked') emoji = `<x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block mr-1 -mt-1" />`;
        else if(hazard.type === 'landslide') emoji = `<x-heroicon-o-globe-alt class="w-5 h-5 inline-block mr-1 -mt-1" />️`;
        
        const hazardIcon = L.divIcon({
            className: 'hazard-icon',
            html: `<div style="font-size: 20px; background: rgba(255,255,255,0.8); border-radius: 50%; padding: 2px; box-shadow: 0 0 5px red;">${emoji}</div>`,
            iconSize: [24, 24]
        });
        
        // Draw red circle around hazard
        L.circle([hazard.latitude, hazard.longitude], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.2,
            radius: 300
        }).addTo(map);

        L.marker([hazard.latitude, hazard.longitude], {icon: hazardIcon})
            .addTo(map)
            .bindPopup(`<b class="text-red-600">อันตราย: ${hazard.type}</b><br>${hazard.description || ''}`);
    });

    // Get Officer Location and Route
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const officerLat = position.coords.latitude;
            const officerLng = position.coords.longitude;
            
            // Draw Officer Marker
            const officerIcon = L.divIcon({
                className: 'officer-icon',
                html: `<div style="font-size: 24px;"><x-heroicon-o-paper-airplane class="w-5 h-5 inline-block mr-1 -mt-1" /></div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });
            L.marker([officerLat, officerLng], {icon: officerIcon})
                .addTo(map)
                .bindPopup('<b>ตำแหน่งของคุณ</b>');
                
            // Add Routing
            L.Routing.control({
                waypoints: [
                    L.latLng(officerLat, officerLng),
                    L.latLng(targetLat, targetLng)
                ],
                routeWhileDragging: false,
                addWaypoints: false,
                show: true,
                lineOptions: {
                    styles: [{color: '#3b82f6', opacity: 0.8, weight: 6}]
                },
                createMarker: function() { return null; }, // Hide default markers from routing
                language: 'en' // Leaflet routing machine doesn't have native TH support without plugins, en is fine.
            }).addTo(map);

            // Hide AI Overlay immediately
            document.getElementById('ai-loading').classList.add('hidden-overlay');

        }, function(error) {
            alert('ไม่สามารถดึงตำแหน่งปัจจุบันได้ กรุณาเปิด GPS');
            document.getElementById('ai-loading').classList.add('hidden-overlay');
        });
    } else {
        alert('เบราว์เซอร์ของคุณไม่รองรับ Geolocation');
        document.getElementById('ai-loading').classList.add('hidden-overlay');
    }
});
</script>
@endpush
