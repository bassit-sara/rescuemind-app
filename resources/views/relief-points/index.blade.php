@extends(auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer', 'volunteer']) ? 'layouts.admin' : 'layouts.app')
@section('title', 'จุดช่วยเหลือและที่พักพิง')
@section('page-title')
    <x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /> จุดช่วยเหลือและที่พักพิง
@endsection
@section('content')

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('mt1') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ย้อนกลับ
        </a>
    </div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="province" class="flex-1 min-w-[150px] px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 hover:border-blue-300 transition-colors bg-gray-50 focus:bg-white">
            <option value="">ทุกจังหวัด</option>
            @php
                $defaultProvinces = ['ยะลา', 'ปัตตานี', 'นราธิวาส', 'สงขลา', 'สตูล'];
                $allProvinces = collect($defaultProvinces)->merge($provinces ?? [])->unique()->values();
            @endphp
            @foreach($allProvinces as $p)
            <option value="{{ $p }}" {{ request('province') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        
        {{-- Custom Type Dropdown using Alpine.js for SVG support --}}
        <div x-data="{ open: false, type: '{{ request('type', '') }}' }" class="relative flex-1 min-w-[150px]">
            <input type="hidden" name="type" :value="type">
            <button type="button" @click="open = !open" @click.away="open = false" 
                    class="w-full flex items-center justify-between px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50 focus:bg-white hover:border-blue-300 transition-colors text-left">
                <span class="flex items-center gap-2">
                    <template x-if="type === ''"><span class="font-medium text-gray-700">ทุกประเภท</span></template>
                    <template x-if="type === 'shelter'"><span class="flex items-center gap-2 font-medium text-gray-700"><x-heroicon-s-home class="w-5 h-5 text-orange-500"/> ที่พักพิง</span></template>
                    <template x-if="type === 'medical'"><span class="flex items-center gap-2 font-medium text-gray-700"><x-heroicon-s-plus-circle class="w-5 h-5 text-red-500"/> จุดพยาบาล</span></template>
                    <template x-if="type === 'food'"><span class="flex items-center gap-2 font-medium text-gray-700"><x-heroicon-s-cake class="w-5 h-5 text-yellow-500"/> แจกอาหาร</span></template>
                    <template x-if="type === 'water'"><span class="flex items-center gap-2 font-medium text-gray-700"><x-heroicon-s-beaker class="w-5 h-5 text-blue-500"/> แจกน้ำ</span></template>
                </span>
                <x-heroicon-m-chevron-down class="w-4 h-4 text-gray-400 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" style="display: none;" x-transition
                 class="absolute z-20 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl py-2 text-sm max-h-60 overflow-auto">
                <button type="button" @click="type = ''; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-blue-50 transition-colors text-left">
                    <span class="font-medium text-gray-700 ml-7">ทุกประเภท</span>
                </button>
                <button type="button" @click="type = 'shelter'; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-orange-50 transition-colors text-left">
                    <x-heroicon-s-home class="w-5 h-5 text-orange-500"/> <span class="font-medium text-gray-700">ที่พักพิง</span>
                </button>
                <button type="button" @click="type = 'medical'; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-red-50 transition-colors text-left">
                    <x-heroicon-s-plus-circle class="w-5 h-5 text-red-500"/> <span class="font-medium text-gray-700">จุดพยาบาล</span>
                </button>
                <button type="button" @click="type = 'food'; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-yellow-50 transition-colors text-left">
                    <x-heroicon-s-cake class="w-5 h-5 text-yellow-500"/> <span class="font-medium text-gray-700">แจกอาหาร</span>
                </button>
                <button type="button" @click="type = 'water'; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-blue-50 transition-colors text-left">
                    <x-heroicon-s-beaker class="w-5 h-5 text-blue-500"/> <span class="font-medium text-gray-700">แจกน้ำ</span>
                </button>
            </div>
        </div>
        
        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 hover:shadow-md transition-all">ค้นหา</button>
    </form>
</div>

{{-- Stats Bar --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-4 text-center border border-green-200">
        <div class="text-3xl font-black text-green-600 drop-shadow-sm">{{ $reliefPoints->total() }}</div>
        <div class="text-xs font-semibold text-green-700 mt-1">จุดทั้งหมด</div>
    </div>
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-4 text-center border border-orange-200">
        <div class="text-3xl font-black text-orange-600 drop-shadow-sm">{{ $reliefPoints->where('type', 'shelter')->count() }}</div>
        <div class="text-xs font-semibold text-orange-700 mt-1">ที่พักพิง</div>
    </div>
    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-4 text-center border border-red-200">
        <div class="text-3xl font-black text-red-600 drop-shadow-sm">{{ $reliefPoints->where('type', 'medical')->count() }}</div>
        <div class="text-xs font-semibold text-red-700 mt-1">จุดพยาบาล</div>
    </div>
</div>

{{-- Interactive Map --}}
<div class="mb-6 bg-white p-2 rounded-3xl shadow-sm border border-gray-100">
    <div id="relief-map" class="w-full h-[400px] rounded-2xl z-0 relative"></div>
</div>

{{-- Relief Points List --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($reliefPoints as $rp)
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full relative overflow-hidden group">
        {{-- Status badge absolute top right --}}
        <div class="absolute top-4 right-4 z-10">
            <span class="px-3 py-1 text-xs font-bold rounded-full flex items-center gap-1 shadow-sm {{ $rp->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                @if($rp->is_active)
                    <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div> เปิด
                @else
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div> ปิด
                @endif
            </span>
        </div>

        <div class="flex items-start gap-4 mb-4 pt-2">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-inner
                @if($rp->type == 'shelter') bg-orange-100 text-orange-600
                @elseif($rp->type == 'medical') bg-red-100 text-red-600
                @elseif($rp->type == 'food') bg-yellow-100 text-yellow-600
                @else bg-blue-100 text-blue-600 @endif
            ">
                @if($rp->type == 'shelter')
                    <x-heroicon-s-home class="w-7 h-7" />
                @elseif($rp->type == 'medical')
                    <x-heroicon-s-plus-circle class="w-7 h-7" />
                @elseif($rp->type == 'food')
                    <x-heroicon-s-cake class="w-7 h-7" />
                @else
                    <x-heroicon-s-beaker class="w-7 h-7" />
                @endif
            </div>
            <div class="pr-12">
                <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $rp->name }}</h3>
                <div class="text-xs font-medium text-gray-500 mt-1 bg-gray-100 w-fit px-2 py-0.5 rounded-md">{{ $rp->province }}</div>
            </div>
        </div>

        @if($rp->address)
        <div class="text-sm text-gray-600 mb-4 flex items-start gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
            <x-heroicon-s-map-pin class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" /> 
            <span class="leading-snug">{{ $rp->address }}</span>
        </div>
        @endif

        <div class="mt-auto">
            @if($rp->capacity)
            <div class="mb-5 bg-white border border-gray-100 shadow-sm p-3 rounded-xl">
                <div class="flex justify-between text-xs font-semibold text-gray-600 mb-2">
                    <span class="flex items-center gap-1"><x-heroicon-s-users class="w-3.5 h-3.5 text-gray-400"/> ความจุพื้นที่</span>
                    <span class="{{ ($rp->current_count ?? 0) >= $rp->capacity ? 'text-red-600 font-bold' : 'text-blue-600' }}">{{ $rp->current_count ?? 0 }} <span class="text-gray-400 font-normal">/ {{ $rp->capacity }} คน</span></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden border border-gray-200/50">
                    @php $pct = $rp->capacity > 0 ? min(100, round(($rp->current_count ?? 0) / $rp->capacity * 100)) : 0; @endphp
                    <div class="h-full rounded-full transition-all duration-1000 relative {{ $pct > 90 ? 'bg-red-500' : ($pct > 75 ? 'bg-orange-500' : 'bg-green-500') }}"
                         style="width: {{ $pct }}%">
                         <div class="absolute inset-0 bg-white/20 w-full" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex gap-2 pt-2 border-t border-gray-100 flex-wrap">
                @if($rp->type === 'shelter')
                <a href="{{ route('bookings.create', $rp) }}" class="w-full py-2.5 flex items-center justify-center gap-2 text-sm font-bold bg-orange-500 text-white rounded-xl hover:bg-orange-600 hover:shadow-md transition-all">
                    <x-heroicon-s-home-modern class="w-4 h-4" /> จองที่พักพิง
                </a>
                @endif
                @if($rp->latitude && $rp->longitude)
                <button type="button" onclick="openNavigation({{ $rp->latitude }}, {{ $rp->longitude }}, '{{ addslashes($rp->name) }}')"
                   class="flex-1 py-2.5 flex items-center justify-center gap-2 text-sm font-bold bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                    <x-heroicon-s-map class="w-4 h-4" /> นำทาง
                </button>
                @endif
                @if($rp->contact_phone)
                <a href="tel:{{ $rp->contact_phone }}" class="flex-1 py-2.5 flex items-center justify-center gap-2 text-sm font-bold bg-green-50 text-green-700 rounded-xl hover:bg-green-600 hover:text-white transition-all">
                    <x-heroicon-s-phone class="w-4 h-4" /> โทร
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-24 bg-white rounded-3xl border border-dashed border-gray-300">
        <div class="text-gray-300 flex justify-center mb-4"><x-heroicon-o-home-modern class="w-16 h-16" /></div>
        <div class="text-xl font-bold text-gray-700">ไม่พบจุดช่วยเหลือ</div>
        <p class="text-sm text-gray-500 mt-2">ลองเปลี่ยนตัวกรองการค้นหา หรือยังไม่มีจุดช่วยเหลือในขณะนี้</p>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $reliefPoints->withQueryString()->links() }}</div>

{{-- Navigation Modal --}}
<div id="nav-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl w-full max-w-5xl h-[85vh] flex flex-col shadow-2xl transform scale-95 opacity-0 transition-all duration-300" id="nav-modal-content">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-blue-50 rounded-t-3xl">
            <h3 class="font-bold text-lg text-blue-900 flex items-center gap-2">
                <x-heroicon-s-map class="w-5 h-5" /> นำทางไปยัง: <span id="nav-dest-name"></span>
            </h3>
            <div class="flex items-center gap-2">
                <a id="nav-google-maps-btn" href="#" target="_blank" class="hidden sm:flex text-xs font-bold bg-white text-blue-600 px-3 py-1.5 rounded-xl hover:bg-blue-600 hover:text-white transition-colors items-center gap-1 shadow-sm border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg> 
                    เปิดในแอปแผนที่ (Google Maps)
                </a>
                <button type="button" onclick="closeNavigation()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-gray-500 hover:bg-gray-100 hover:text-red-500 transition-colors shadow-sm border border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        <div class="p-2 flex-1 relative rounded-b-3xl overflow-hidden">
            <div id="nav-map" class="w-full h-full rounded-2xl z-0"></div>
            <div id="nav-loading" class="absolute inset-0 bg-white/90 z-10 flex flex-col items-center justify-center rounded-2xl hidden">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                <p class="text-blue-800 font-bold">กำลังค้นหาตำแหน่งของคุณและคำนวณเส้นทาง...</p>
                <p class="text-gray-500 text-sm mt-2" id="nav-loading-text">โปรดอนุญาตการเข้าถึงตำแหน่งที่ตั้ง (Location)</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        .leaflet-popup-content-wrapper { border-radius: 12px; }
        .leaflet-popup-content { margin: 12px; }
        .leaflet-routing-container { background-color: white; border-radius: 12px; padding: 10px; max-height: 50vh; overflow-y: auto; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('relief-map').setView([13.736717, 100.523186], 6);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            
            var points = @json($reliefPoints->items());
            var bounds = [];
            
            points.forEach(function(point) {
                if (point.latitude && point.longitude) {
                    var marker = L.marker([point.latitude, point.longitude]).addTo(map);
                    
                    var iconHtml = '';
                    if(point.type === 'shelter') iconHtml = '🏠 ที่พักพิง';
                    else if(point.type === 'medical') iconHtml = '🏥 จุดพยาบาล';
                    else if(point.type === 'food') iconHtml = '🍱 แจกอาหาร';
                    else iconHtml = '💧 แจกน้ำ';
                    
                    var popupContent = `<div class="p-1 min-w-[200px]">
                        <strong class="text-sm font-bold block mb-1 text-gray-900">${point.name}</strong>
                        <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-full mb-2 inline-block">${iconHtml}</span><br>
                        <span class="text-xs text-gray-500 mt-1 block leading-relaxed">${point.address || ''}</span>
                        ${point.contact_phone ? '<a href="tel:'+point.contact_phone+'" class="mt-2 text-blue-600 font-bold text-xs flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> '+point.contact_phone+'</a>' : ''}
                    </div>`;
                    marker.bindPopup(popupContent);
                    bounds.push([point.latitude, point.longitude]);
                }
            });
            
            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50] });
            } else {
                @if(request('province') == 'ยะลา') map.setView([6.5418, 101.2816], 10);
                @elseif(request('province') == 'ปัตตานี') map.setView([6.8673, 101.2501], 10);
                @elseif(request('province') == 'นราธิวาส') map.setView([6.4255, 101.8253], 10);
                @elseif(request('province') == 'สงขลา') map.setView([7.1897, 100.5954], 10);
                @elseif(request('province') == 'สตูล') map.setView([6.6222, 100.0674], 10);
                @endif
            }
        });

        // Navigation Logic
        var navMap = null;
        var routingControl = null;

        window.openNavigation = function(lat, lng, name) {
            document.getElementById('nav-dest-name').innerText = name;
            
            // Set base URL for Google Maps
            document.getElementById('nav-google-maps-btn').href = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
            document.getElementById('nav-google-maps-btn').classList.remove('hidden');
            
            var modal = document.getElementById('nav-modal');
            var modalContent = document.getElementById('nav-modal-content');
            var loading = document.getElementById('nav-loading');
            var loadingText = document.getElementById('nav-loading-text');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);

            if (!navMap) {
                navMap = L.map('nav-map').setView([13.736717, 100.523186], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(navMap);
            }
            
            setTimeout(() => { navMap.invalidateSize(); }, 300);

            if (routingControl) {
                navMap.removeControl(routingControl);
                routingControl = null;
            }

            loading.classList.remove('hidden');
            loadingText.innerText = "โปรดอนุญาตการเข้าถึงตำแหน่งที่ตั้ง (Location)";

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;
                        loadingText.innerText = "กำลังคำนวณเส้นทาง...";
                        
                        // Update Google Maps link with directions
                        document.getElementById('nav-google-maps-btn').href = `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${lat},${lng}`;

                        routingControl = L.Routing.control({
                            waypoints: [
                                L.latLng(userLat, userLng),
                                L.latLng(lat, lng)
                            ],
                            routeWhileDragging: false,
                            addWaypoints: false,
                            fitSelectedRoutes: true,
                            showAlternatives: true,
                            lineOptions: {
                                styles: [{color: '#2563eb', opacity: 0.8, weight: 6}]
                            },
                            createMarker: function(i, wp, nWps) {
                                var markerIcon = L.divIcon({
                                    className: 'custom-div-icon',
                                    html: `<div style="background-color: ${i === 0 ? '#10b981' : '#f97316'}; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                                    iconSize: [24, 24],
                                    iconAnchor: [12, 12]
                                });
                                return L.marker(wp.latLng, {
                                    icon: markerIcon,
                                    draggable: false
                                }).bindPopup(i === 0 ? "ตำแหน่งปัจจุบันของคุณ" : "จุดช่วยเหลือ: " + name);
                            }
                        }).addTo(navMap);

                        routingControl.on('routesfound', function() {
                            loading.classList.add('hidden');
                        });
                        
                        routingControl.on('routingerror', function(err) {
                            loadingText.innerText = "ไม่สามารถคำนวณเส้นทางได้";
                            setTimeout(() => loading.classList.add('hidden'), 3000);
                        });
                    },
                    function(error) {
                        loadingText.innerText = "ไม่สามารถเข้าถึงตำแหน่งของคุณได้ โปรดตรวจสอบการตั้งค่า Browser";
                        setTimeout(() => loading.classList.add('hidden'), 4000);
                        navMap.setView([lat, lng], 13);
                        L.marker([lat, lng]).addTo(navMap).bindPopup(name).openPopup();
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                loadingText.innerText = "เบราว์เซอร์ของคุณไม่รองรับการระบุตำแหน่ง";
                setTimeout(() => loading.classList.add('hidden'), 4000);
            }
        };

        window.closeNavigation = function() {
            var modal = document.getElementById('nav-modal');
            var modalContent = document.getElementById('nav-modal-content');
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        };
    </script>
@endpush
@endsection
