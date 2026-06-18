@extends('layouts.admin')
@section('title', 'Super Admin - KPI')
@section('page-title', '👑 Command & Control Center')
@section('content')

{{-- KPI Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-2xl p-5">
        <div class="text-3xl font-black">{{ $kpi['total_users'] }}</div>
        <div class="text-slate-300 text-sm font-medium">👥 ผู้ใช้ทั้งหมด</div>
        @if(isset($kpi['new_users_today']))
        <div class="text-green-400 text-xs mt-1">+{{ $kpi['new_users_today'] }} วันนี้</div>
        @endif
    </div>
    <div class="bg-gradient-to-br from-red-600 to-red-800 text-white rounded-2xl p-5">
        <div class="text-3xl font-black">{{ $kpi['total_sos'] }}</div>
        <div class="text-red-100 text-sm font-medium">🆘 SOS ทั้งหมด</div>
        <div class="text-red-200 text-xs mt-1">{{ $kpi['sos_today'] ?? 0 }} วันนี้</div>
    </div>
    <div class="bg-gradient-to-br from-green-600 to-green-800 text-white rounded-2xl p-5">
        <div class="text-3xl font-black">{{ $kpi['rescue_rate'] }}%</div>
        <div class="text-green-100 text-sm font-medium">✅ อัตราช่วยเหลือ</div>
    </div>
    <div class="bg-gradient-to-br from-purple-600 to-purple-800 text-white rounded-2xl p-5">
        <div class="text-3xl font-black">{{ $kpi['assessments_count'] }}</div>
        <div class="text-purple-100 text-sm font-medium">🧠 การประเมินจิตใจ</div>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-black text-gray-800">{{ $kpi['active_alerts'] }}</div>
        <div class="text-xs text-gray-500">แจ้งเตือนใช้งาน</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-black text-gray-800">{{ $kpi['relief_points'] }}</div>
        <div class="text-xs text-gray-500">จุดช่วยเหลือ</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-black text-gray-800">{{ $kpi['missing_persons'] }}</div>
        <div class="text-xs text-gray-500">คนหาย (ยังไม่พบ)</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-black text-gray-800">{{ $kpi['volunteers'] }}</div>
        <div class="text-xs text-gray-500">อาสาสมัคร</div>
    </div>
</div>

{{-- Weather Widget --}}
<div class="mb-8">
    <x-weather-widget />
</div>

{{-- Live Command Center Map --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 relative">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-gray-800 text-lg flex items-center gap-2">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            🗺️ Live Command Center (พิกัดฉุกเฉินแบบเรียลไทม์)
        </h2>
        <div class="text-xs text-gray-500 flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
            <svg class="w-4 h-4 animate-spin text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span id="countdown-text">อัปเดตข้อมูลใน 60 วินาที</span>
        </div>
    </div>
    
    <div id="live-map" class="w-full h-[500px] rounded-xl border border-gray-200 z-10"></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- SOS by Province --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-4">📊 SOS แยกตามจังหวัด (30 วัน)</h2>
        <div class="space-y-3">
            @foreach($sosByProvince as $province => $count)
            @php $max = $sosByProvince->max(); @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700 font-medium">{{ $province ?: 'ไม่ระบุ' }}</span>
                    <span class="text-gray-500">{{ $count }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full bg-gradient-to-r from-red-500 to-red-700"
                         style="width: {{ $max > 0 ? round($count/$max*100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- SOS Status Distribution --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-4">🔄 สถานะ SOS ปัจจุบัน</h2>
        <div class="space-y-4">
            @foreach(['pending' => ['label'=>'รอดำเนินการ','color'=>'bg-red-500'], 'assigned' => ['label'=>'มอบหมายแล้ว','color'=>'bg-yellow-500'], 'in_progress' => ['label'=>'กำลังช่วยเหลือ','color'=>'bg-blue-500'], 'resolved' => ['label'=>'แก้ไขแล้ว','color'=>'bg-purple-500'], 'safe' => ['label'=>'ปลอดภัย','color'=>'bg-green-500']] as $status => $info)
            @php $count = $sosByStatus[$status] ?? 0; $total = max(1, array_sum($sosByStatus->toArray())); @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">{{ $info['label'] }}</span>
                    <span class="font-bold text-gray-800">{{ $count }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="{{ $info['color'] }} h-2.5 rounded-full" style="width: {{ round($count/$total*100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Role Distribution --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-4">👥 ผู้ใช้แยกตาม Role</h2>
        <div class="space-y-3">
            @foreach($usersByRole as $role => $count)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <span class="text-sm font-medium text-gray-700">{{ $role }}</span>
                <span class="font-black text-gray-800">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Quick Navigation --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-4">⚡ จัดการระบบ</h2>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('super-admin.users') }}" class="p-4 bg-indigo-50 rounded-xl text-center hover:bg-indigo-100 transition-colors">
                <div class="text-2xl mb-1">👥</div>
                <div class="text-sm font-medium text-indigo-700">จัดการผู้ใช้</div>
            </a>
            <a href="{{ route('super-admin.shelter') }}" class="p-4 bg-green-50 rounded-xl text-center hover:bg-green-100 transition-colors">
                <div class="text-2xl mb-1">🏕️</div>
                <div class="text-sm font-medium text-green-700">ที่พักพิง</div>
            </a>
            <a href="{{ route('super-admin.resources') }}" class="p-4 bg-blue-50 rounded-xl text-center hover:bg-blue-100 transition-colors">
                <div class="text-2xl mb-1">📦</div>
                <div class="text-sm font-medium text-blue-700">ทรัพยากร</div>
            </a>
            <a href="{{ route('super-admin.analytics') }}" class="p-4 bg-purple-50 rounded-xl text-center hover:bg-purple-100 transition-colors">
                <div class="text-2xl mb-1">📈</div>
                <div class="text-sm font-medium text-purple-700">Analytics</div>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include Leaflet CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize Map
    var map = L.map('live-map').setView([13.7563, 100.5018], 6);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var markersLayer = L.layerGroup().addTo(map);

    // Custom Icon for SOS
    var redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    function renderMarkers(data) {
        markersLayer.clearLayers();
        var currentBounds = [];
        data.forEach(function(sos) {
            if(sos.latitude && sos.longitude) {
                var latlng = [sos.latitude, sos.longitude];
                var user = sos.user ? sos.user.name : 'ไม่ระบุตัวตน';
                var phone = sos.phone || 'ไม่มีเบอร์ติดต่อ';
                
                var popupContent = `
                    <div class="font-sans">
                        <div class="font-bold text-red-600 mb-1">🆘 ขอความช่วยเหลือฉุกเฉิน</div>
                        <div class="text-sm"><b>ผู้แจ้ง:</b> ${user}</div>
                        <div class="text-sm"><b>เบอร์โทร:</b> ${phone}</div>
                        <div class="text-sm mb-2"><b>สถานะ:</b> ${sos.status === 'pending' ? 'รอดำเนินการ' : 'รับเรื่องแล้ว'}</div>
                    </div>
                `;
                
                L.marker(latlng, {icon: redIcon}).bindPopup(popupContent).addTo(markersLayer);
                currentBounds.push(latlng);
            }
        });
        return currentBounds;
    }

    var sosData = @json($activeSosRequests);
    var bounds = renderMarkers(sosData);

    if(bounds.length > 0) {
        map.fitBounds(bounds, {padding: [50, 50], maxZoom: 15});
    }

    // 2. Auto-refresh & Alert Logic
    var countdown = 60;
    var timerDisplay = document.getElementById('countdown-text');
    var previousSosCount = sessionStorage.getItem('last_sos_count') || sosData.length;
    sessionStorage.setItem('last_sos_count', previousSosCount);

    setInterval(function() {
        countdown--;
        if(countdown <= 0) {
            timerDisplay.textContent = "กำลังอัปเดตข้อมูล...";
            
            // Fetch updated data via AJAX
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.activeSosRequests) {
                    var newSosData = data.activeSosRequests;
                    var newCount = newSosData.length;
                    
                    renderMarkers(newSosData);

                    // Check if new SOS arrived
                    if (newCount > previousSosCount) {
                        // Play alert beep
                        try {
                            var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                            var oscillator = audioCtx.createOscillator();
                            var gainNode = audioCtx.createGain();
                            oscillator.type = 'square';
                            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime);
                            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                            oscillator.connect(gainNode);
                            gainNode.connect(audioCtx.destination);
                            oscillator.start();
                            setTimeout(() => oscillator.stop(), 500);
                        } catch(e) {}
                        
                        alert('🚨 แจ้งเตือน: มีการขอความช่วยเหลือ (SOS) เคสใหม่เข้ามาในระบบ!');
                    }
                    
                    previousSosCount = newCount;
                    sessionStorage.setItem('last_sos_count', previousSosCount);
                }
                
                countdown = 60;
                timerDisplay.textContent = `อัปเดตข้อมูลใน ${countdown} วินาที`;
            })
            .catch(err => {
                console.error('Failed to update map data', err);
                countdown = 60;
                timerDisplay.textContent = `อัปเดตข้อมูลใน ${countdown} วินาที`;
            });

        } else {
            timerDisplay.textContent = `อัปเดตข้อมูลใน ${countdown} วินาที`;
        }
    }, 1000);
});
</script>
@endsection
