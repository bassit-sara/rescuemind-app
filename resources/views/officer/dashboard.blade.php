@extends('layouts.admin')
@section('title', 'Officer Dashboard')
@section('page-title')
    <x-heroicon-o-shield-exclamation class="w-5 h-5 inline-block mr-1 -mt-1" /> Command Center
@endsection
@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-red-600">{{ $stats['pending'] }}</div>
        <div class="text-sm text-red-700 font-medium">รอดำเนินการ</div>
    </div>
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-blue-600">{{ $stats['in_progress'] }}</div>
        <div class="text-sm text-blue-700 font-medium">กำลังช่วยเหลือ</div>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-green-600">{{ $stats['resolved'] }}</div>
        <div class="text-sm text-green-700 font-medium">เสร็จวันนี้</div>
    </div>
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-orange-600">{{ $stats['missing'] }}</div>
        <div class="text-sm text-orange-700 font-medium">คนหาย</div>
    </div>
</div>

{{-- Weather Widget --}}
<div class="mb-6">
    <x-weather-widget />
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Live Map (Full Width) --}}
    <div class="lg:col-span-2 mb-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-800 to-gray-900 text-white">
                <h2 class="font-bold flex items-center gap-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                    Live Command Map
                </h2>
                <div class="text-xs text-gray-300">อัปเดตอัตโนมัติทุก 30 วินาที</div>
            </div>
            <div id="officer-map" class="w-full h-[400px] z-10"></div>
        </div>
    </div>

    {{-- Pending SOS --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800"><x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /> SOS รอดำเนินการ</h2>
            <a href="{{ route('officer.sos.index') }}" class="text-sm text-red-600 hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($pendingSos as $sos)
            <div class="priority-{{ $sos->priority }} mx-4 my-3 p-3 rounded-xl">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                {{ $sos->priority == 'critical' ? 'bg-red-100 text-red-700' : ($sos->priority == 'high' ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ strtoupper($sos->priority) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $sos->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-sm font-medium text-gray-800 truncate">{{ $sos->province }} • {{ $sos->num_people }} คน</div>
                        @if($sos->has_bedridden || $sos->has_pregnant)
                        <div class="flex gap-1 mt-1">
                            @if($sos->has_bedridden) <span class="text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded"><x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" />️ติดเตียง</span> @endif
                            @if($sos->has_pregnant) <span class="text-xs bg-pink-100 text-pink-600 px-1.5 py-0.5 rounded"><x-heroicon-o-user-plus class="w-5 h-5 inline-block mr-1 -mt-1" />ตั้งครรภ์</span> @endif
                        </div>
                        @endif
                    </div>
                    <form action="{{ route('officer.sos.assign', $sos) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex-shrink-0 px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors">รับเคส</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400">
                <div class="text-3xl mb-2"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></div>
                <div class="text-sm">ไม่มี SOS รอดำเนินการ</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- My Active SOS --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800"><x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" /> ภารกิจของฉัน</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($myActiveSos as $sos)
            <div class="p-4 mx-2 my-2 bg-blue-50 rounded-xl">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-blue-600">SOS #{{ $sos->id }} • {{ $sos->province }}</span>
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">{{ $sos->status_label }}</span>
                </div>
                <div class="text-sm text-gray-700 mb-3">{{ $sos->num_people }} คน{{ $sos->address ? ' • '.$sos->address : '' }}</div>
                <div class="flex gap-2">
                    @if($sos->latitude)
                    <a href="https://www.google.com/maps?q={{ $sos->latitude }},{{ $sos->longitude }}" target="_blank"
                       class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700"><x-heroicon-o-map class="w-5 h-5 inline-block mr-1 -mt-1" />️ นำทาง</a>
                    @endif
                    <form action="{{ route('officer.sos.status', $sos) }}" method="POST" class="flex gap-1">
                        @csrf @method('PATCH')
                        <select name="status" class="text-xs border border-gray-300 rounded-lg px-2">
                            <option value="in_progress" {{ $sos->status=='in_progress'?'selected':'' }}>กำลังช่วยเหลือ</option>
                            <option value="resolved" {{ $sos->status=='resolved'?'selected':'' }}>เสร็จสิ้น</option>
                            <option value="safe" {{ $sos->status=='safe'?'selected':'' }}>ปลอดภัย</option>
                        </select>
                        <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700">อัปเดต</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400">
                <div class="text-sm">ไม่มีภารกิจที่รับไว้</div>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Hazard Reports --}}
@if($recentReports->count() > 0)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-800"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /> รายงานภัยล่าสุด</h2>
        <a href="{{ route('officer.hazard.index') }}" class="text-sm text-orange-600 hover:underline">ดูทั้งหมด →</a>
    </div>
    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach($recentReports as $r)
        <div class="p-3 bg-orange-50 rounded-xl">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-sm font-medium">{{ $r->type_label }}</span>
                @if($r->verified) <span class="text-xs text-green-600"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> ยืนยันแล้ว</span> @endif
            </div>
            <div class="text-xs text-gray-500">{{ $r->province }} • {{ $r->created_at->diffForHumans() }}</div>
            <div class="text-xs text-gray-600 mt-1 truncate">{{ $r->description }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize Map
        const map = L.map('officer-map').setView([13.7563, 100.5018], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Load SOS Data
        const sosData = @json($allActiveSos ?? []);
        
        let markers = L.markerClusterGroup({
            chunkedLoading: true,
            maxClusterRadius: 50
        });

        sosData.forEach(sos => {
            if(sos.latitude && sos.longitude) {
                let color = sos.priority === 'critical' ? 'red' : (sos.priority === 'high' ? 'orange' : 'yellow');
                if(sos.status === 'in_progress') color = 'blue';

                const iconHtml = `<div style="background-color: ${color}; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>`;
                const customIcon = L.divIcon({
                    html: iconHtml,
                    className: '',
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                const badge = `<span class="px-2 py-0.5 rounded text-xs text-white" style="background-color: ${color}">SOS #${sos.id}</span>`;
                const popup = `
                    <div class="p-1 min-w-[150px]">
                        <div class="mb-2">${badge}</div>
                        <div class="font-bold text-sm mb-1">${sos.name}</div>
                        <div class="text-xs text-gray-600 mb-2"><x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /> ${sos.phone}</div>
                        <div class="text-xs font-bold text-red-600">${sos.priority.toUpperCase()} Priority</div>
                        <a href="/officer/sos/${sos.id}" class="block mt-3 text-center bg-gray-800 text-white text-xs py-1.5 rounded hover:bg-gray-700">ดูรายละเอียด</a>
                    </div>
                `;

                const marker = L.marker([sos.latitude, sos.longitude], {icon: customIcon});
                marker.bindPopup(popup);
                markers.addLayer(marker);
            }
        });

        map.addLayer(markers);

        if (sosData.length > 0) {
            map.fitBounds(markers.getBounds(), { padding: [50, 50] });
        }

        // Auto Refresh
        setTimeout(() => {
            window.location.reload();
        }, 30000); // 30 seconds
    });
</script>
@endpush
