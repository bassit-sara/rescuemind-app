@extends('layouts.app')
@section('title', 'ประเมินความต้องการชุมชน')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen" x-data="communityNeedsForm()" x-init="initAll()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mt3') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-teal-600 hover:bg-teal-50 shadow-sm transition-colors shrink-0">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-o-megaphone class="w-7 h-7 text-teal-500" />
                        ประเมินความต้องการชุมชน
                    </h2>
                    <p class="text-gray-600 text-[15px] font-medium mt-1">แจ้งและรวบรวมความต้องการของชุมชนส่วนรวม เพื่อประสานงานจัดหาทรัพยากร</p>
                </div>
            </div>
            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
            <div class="flex items-center gap-2 shrink-0">
                <button class="px-4 py-2 bg-teal-100 text-teal-700 rounded-xl text-sm font-bold shadow-sm hover:bg-teal-200 flex items-center gap-2 transition-colors">
                    <x-heroicon-o-pencil-square class="w-4 h-4" /> จัดการข้อมูลชุมชน
                </button>
            </div>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm">
                <x-heroicon-s-check-circle class="w-6 h-6 text-green-500 shrink-0" />
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
            <div class="p-5 sm:p-8">
                <form action="#" @submit.prevent>
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Community Info -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2">1. ข้อมูลชุมชน</h3>
                            
                            <!-- Live GPS Section -->
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-teal-100 relative overflow-hidden mb-6">
                                <h4 class="text-md font-black text-gray-800 mb-4 flex items-center gap-2"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0 text-teal-600" /> พิกัดที่ตั้งชุมชน (Community Location)</h4>
                                
                                <div class="mb-4 relative">
                                    <div id="community-map" class="w-full h-[300px] rounded-xl border border-gray-300 z-10 shadow-inner" wire:ignore></div>
                                    <div x-show="locating" class="absolute inset-0 bg-white/70 z-20 flex flex-col items-center justify-center rounded-xl backdrop-blur-sm" style="display: none;">
                                        <div class="w-10 h-10 border-4 border-teal-200 border-t-teal-600 rounded-full animate-spin"></div>
                                        <span class="mt-2 text-sm font-bold text-teal-800">กำลังค้นหาพิกัดชุมชน...</span>
                                    </div>
                                </div>

                                <button type="button" @click="getLocation()" class="w-full py-3 bg-teal-50 text-teal-700 hover:bg-teal-100 border border-teal-200 font-bold rounded-xl transition-colors flex items-center justify-center gap-2 mb-4">
                                    <x-heroicon-o-signal class="w-5 h-5 inline-block mr-1 -mt-1" /> ค้นหาพิกัดชุมชนปัจจุบันอัตโนมัติ
                                </button>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 font-medium uppercase tracking-wider mb-1">ละติจูด (Lat)</label>
                                        <input type="text" x-model="lat" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 font-mono text-gray-600" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 font-medium uppercase tracking-wider mb-1">ลองจิจูด (Lng)</label>
                                        <input type="text" x-model="lng" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 font-mono text-gray-600" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <div class="flex justify-between items-end mb-1">
                                        <label class="block text-sm font-medium text-gray-700">ชื่อชุมชน / หมู่บ้าน</label>
                                        <span x-show="isLoadingAddress" class="text-xs text-teal-600 animate-pulse flex items-center gap-1">
                                            <x-heroicon-o-arrow-path class="w-3 h-3 animate-spin" /> กำลังดึงข้อมูล...
                                        </span>
                                    </div>
                                    <input type="text" x-model="communityName" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 transition-shadow" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนประชากรที่ได้รับผลกระทบโดยประมาณ</label>
                                    <input type="number" x-model="population" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 transition-shadow" required placeholder="จำนวนคน">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสไปรษณีย์</label>
                                    <input type="text" x-model="zipCode" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 transition-shadow" required>
                                </div>
                            </div>
                        </div>

                        <!-- Resources Needed -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">2. สิ่งของและทรัพยากรที่ต้องการเร่งด่วน</h3>
                            <div class="space-y-4">
                                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-2xl hover:bg-teal-50 hover:border-teal-300 cursor-pointer transition-all shadow-sm group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform">
                                            <x-heroicon-s-shopping-bag class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <div class="font-black text-gray-800 group-hover:text-teal-700">อาหารแห้งและน้ำดื่ม</div>
                                            <div class="text-sm text-gray-600 font-medium">ข้าวสาร, ปลากระป๋อง, น้ำขวด</div>
                                        </div>
                                    </div>
                                    <input type="number" x-model="needs.food" placeholder="จำนวนชุด" class="w-28 border-gray-300 rounded-xl text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 text-center font-bold text-teal-700">
                                </label>

                                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-2xl hover:bg-teal-50 hover:border-teal-300 cursor-pointer transition-all shadow-sm group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform">
                                            <x-heroicon-s-beaker class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <div class="font-black text-gray-800 group-hover:text-teal-700">ยารักษาโรคและเวชภัณฑ์</div>
                                            <div class="text-sm text-gray-600 font-medium">ยาสามัญ, ชุดทำแผล, ยาน้ำกัดเท้า</div>
                                        </div>
                                    </div>
                                    <input type="number" x-model="needs.medicine" placeholder="จำนวนชุด" class="w-28 border-gray-300 rounded-xl text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 text-center font-bold text-teal-700">
                                </label>

                                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-2xl hover:bg-teal-50 hover:border-teal-300 cursor-pointer transition-all shadow-sm group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform">
                                            <x-heroicon-s-sparkles class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <div class="font-black text-gray-800 group-hover:text-teal-700">อุปกรณ์ทำความสะอาด</div>
                                            <div class="text-sm text-gray-600 font-medium">ไม้กวาด, ถุงดำ, น้ำยาล้างพื้น</div>
                                        </div>
                                    </div>
                                    <input type="number" x-model="needs.cleaning" placeholder="จำนวนชุด" class="w-28 border-gray-300 rounded-xl text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 text-center font-bold text-teal-700">
                                </label>

                                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-2xl hover:bg-teal-50 hover:border-teal-300 cursor-pointer transition-all shadow-sm group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform">
                                            <x-heroicon-s-user-group class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <div class="font-black text-gray-800 group-hover:text-teal-700">เสื้อผ้าและเครื่องนุ่งห่ม</div>
                                            <div class="text-sm text-gray-600 font-medium">เสื้อผ้ามือหนึ่ง/มือสองสภาพดี, ผ้าห่ม</div>
                                        </div>
                                    </div>
                                    <input type="number" x-model="needs.clothing" placeholder="จำนวนชุด" class="w-28 border-gray-300 rounded-xl text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 text-center font-bold text-teal-700">
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Swipe to Submit -->
                    <div class="mt-10 bg-white rounded-2xl p-4 shadow-sm border border-teal-100">
                        <p class="text-center text-sm font-bold text-teal-600 mb-3">เลื่อนขวาเพื่อส่งข้อมูลประเมินชุมชน</p>
                        <div id="swipe-track" class="relative h-16 bg-teal-100 rounded-full overflow-hidden border border-teal-200 shadow-inner">
                            <div class="absolute inset-0 flex items-center justify-center text-teal-500 font-bold tracking-widest opacity-60">SWIPE TO SUBMIT >>></div>
                            <div id="swipe-slider" 
                                 class="absolute top-1 bottom-1 left-1 w-14 bg-teal-600 rounded-full shadow-md flex items-center justify-center cursor-grab active:cursor-grabbing text-white z-10"
                                 :style="`transform: translateX(${swipeLeft}px); transition: ${isDragging ? 'none' : 'transform 0.3s'}`">
                                <span class="text-xl"><x-heroicon-o-megaphone class="w-5 h-5 inline-block shrink-0" /></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Confirm Modal --}}
        <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" @click="showConfirmModal = false"></div>
                <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-teal-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <span class="text-xl"><x-heroicon-o-megaphone class="w-5 h-5 inline-block shrink-0 text-teal-600" /></span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-black text-gray-900">ยืนยันการส่งข้อมูลประเมินชุมชน</h3>
                            <div class="mt-2 text-[15px] text-gray-600 font-medium">
                                คุณกำลังส่งข้อมูลความต้องการของชุมชน ข้อมูลนี้จะถูกส่งไปยังศูนย์รับบริจาคเพื่อจัดสรรสิ่งของให้ตรงจุด กรุณายืนยันการส่งข้อมูล
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="submitForm()" :disabled="isSubmitting" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span x-show="!isSubmitting">ยืนยันส่งข้อมูล</span>
                            <span x-show="isSubmitting" class="animate-pulse">กำลังส่งข้อมูล...</span>
                        </button>
                        <button type="button" @click="showConfirmModal = false" :disabled="isSubmitting" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                            ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Modal --}}
        <div x-show="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm"></div>
                <div class="relative w-full max-w-sm p-6 bg-white rounded-2xl shadow-xl text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <span class="text-3xl"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0 text-green-600" /></span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 mb-2">ส่งข้อมูลสำเร็จ!</h3>
                    <p class="text-[15px] text-gray-600 font-medium mb-6" x-text="successMessage"></p>
                    <a href="{{ route('mt3.donation') }}" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-teal-600 text-base font-bold text-white hover:bg-teal-700 focus:outline-none">
                        ดูรายการรับบริจาคปัจจุบัน
                    </a>
                </div>
            </div>
        </div>

        {{-- Error Modal --}}
        <div x-show="showErrorModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" @click="showErrorModal = false"></div>
                <div class="relative w-full max-w-sm p-6 bg-white rounded-2xl shadow-xl text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <span class="text-3xl"><x-heroicon-o-x-circle class="w-5 h-5 inline-block shrink-0 text-red-600" /></span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 mb-2">เกิดข้อผิดพลาด</h3>
                    <p class="text-[15px] text-gray-600 font-medium mb-6" x-text="errorMessage"></p>
                    <button type="button" @click="showErrorModal = false" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none">
                        ตกลง
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function communityNeedsForm() {
    return {
        lat: '', lng: '', locating: false, isLoadingAddress: false, map: null, marker: null, accuracyCircle: null, liveDot: null, watchId: null,
        communityName: '', population: '', zipCode: '',
        needs: { food: '', medicine: '', cleaning: '', clothing: '' },
        swipeLeft: 0, startX: 0, isDragging: false,
        showConfirmModal: false, showSuccessModal: false, showErrorModal: false, errorMessage: '',
        isSubmitting: false, successMessage: 'ข้อมูลความต้องการของชุมชนถูกบันทึกเข้าระบบเรียบร้อยแล้ว ทีมงานจะเร่งจัดสรรสิ่งของให้ตรงจุดที่สุด',
        
        initAll() {
            this.initMap();
            this.initSwipe();
        },
        
        initMap() {
            // Default center: Bangkok
            this.map = L.map('community-map').setView([13.7563, 100.5018], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19, attribution: '© OpenStreetMap'
            }).addTo(this.map);

            this.map.on('click', (e) => {
                this.updateMarker(e.latlng.lat, e.latlng.lng, false);
            });
            setTimeout(() => { this.map.invalidateSize(); }, 300);
            
            this.startTracking();
        },
        
        updateMarker(lat, lng, isAuto = false) {
            this.lat = parseFloat(lat).toFixed(6);
            this.lng = parseFloat(lng).toFixed(6);
            
            if(this.marker) {
                this.marker.setLatLng([this.lat, this.lng]);
            } else {
                this.marker = L.marker([this.lat, this.lng], {draggable: true}).addTo(this.map);
                this.marker.on('dragend', (e) => {
                    let pos = e.target.getLatLng();
                    this.updateMarker(pos.lat, pos.lng, true); // Trigger reverse geocode on drag end
                });
            }
            if(!isAuto) {
                this.map.setView([this.lat, this.lng], 16);
            }
            
            this.reverseGeocode(this.lat, this.lng);
        },
        
        reverseGeocode(lat, lng) {
            this.isLoadingAddress = true;
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=th`)
                .then(res => res.json())
                .then(data => {
                    this.isLoadingAddress = false;
                    if(data && data.address) {
                        let address = data.address;
                        let localName = address.village || address.suburb || address.neighbourhood || address.town || address.city_district || address.city || '';
                        
                        if(localName && !this.communityName) {
                            this.communityName = localName;
                        }
                        if(address.postcode && !this.zipCode) {
                            this.zipCode = address.postcode;
                        }
                        
                        // Show popup
                        if(this.marker) {
                            this.marker.bindPopup(`<div class="font-bold text-teal-700">${localName || 'ตำแหน่งที่เลือก'}</div><div class="text-xs mt-1">${data.display_name}</div>`).openPopup();
                        }
                    }
                })
                .catch(e => {
                    this.isLoadingAddress = false;
                    console.error('Reverse geocoding failed', e);
                });
        },
        
        startTracking() {
            this.locating = true;
            if (!navigator.geolocation) { this.locating = false; return; }
            
            if(this.watchId) navigator.geolocation.clearWatch(this.watchId);
            
            this.watchId = navigator.geolocation.watchPosition(
                (pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    const accuracy = pos.coords.accuracy;
                    
                    this.locating = false;
                    
                    if(this.liveDot) {
                        this.liveDot.setLatLng([lat, lng]);
                    } else {
                        this.liveDot = L.circleMarker([lat, lng], {
                            radius: 6, color: '#ffffff', weight: 2, fillColor: '#0d9488', fillOpacity: 1
                        }).addTo(this.map);
                    }
                    
                    if(this.accuracyCircle) {
                        this.accuracyCircle.setLatLng([lat, lng]);
                        this.accuracyCircle.setRadius(accuracy);
                    } else {
                        this.accuracyCircle = L.circle([lat, lng], {
                            radius: accuracy, color: '#0d9488', fillColor: '#0d9488', fillOpacity: 0.1, weight: 1
                        }).addTo(this.map);
                    }
                    
                    if(!this.marker) {
                        this.updateMarker(lat, lng, true);
                        this.map.setView([lat, lng], 16);
                    }
                },
                (err) => { this.locating = false; },
                { enableHighAccuracy: true, maximumAge: 0 }
            );
        },
        
        getLocation() {
            if(this.liveDot) {
                const pos = this.liveDot.getLatLng();
                this.updateMarker(pos.lat, pos.lng, false);
                this.map.setView([pos.lat, pos.lng], 16);
            } else {
                this.startTracking();
            }
        },
        
        initSwipe() {
            const slider = document.getElementById('swipe-slider');
            const track = document.getElementById('swipe-track');
            
            const onMove = (clientX) => {
                if(!this.isDragging) return;
                let x = clientX - this.startX;
                let max = track.clientWidth - slider.clientWidth;
                if(x < 0) x = 0;
                if(x > max) x = max;
                this.swipeLeft = x;
                if(x >= max - 5) {
                    this.isDragging = false;
                    this.swipeLeft = 0;
                    this.validateAndConfirm();
                }
            };
            
            slider.addEventListener('touchstart', (e) => { this.isDragging = true; this.startX = e.touches[0].clientX - this.swipeLeft; }, {passive: false});
            slider.addEventListener('touchmove', (e) => { onMove(e.touches[0].clientX); e.preventDefault(); }, {passive: false});
            slider.addEventListener('touchend', () => { this.isDragging = false; if(this.swipeLeft > 0) this.swipeLeft = 0; });
            
            slider.addEventListener('mousedown', (e) => { this.isDragging = true; this.startX = e.clientX - this.swipeLeft; });
            window.addEventListener('mousemove', (e) => { onMove(e.clientX); });
            window.addEventListener('mouseup', () => { this.isDragging = false; if(this.swipeLeft > 0) this.swipeLeft = 0; });
        },
        
        validateAndConfirm() {
            if(!this.lat || !this.lng) {
                this.errorMessage = "กรุณาระบุพิกัดตำแหน่งชุมชนบนแผนที่";
                this.showErrorModal = true;
                return;
            }
            this.showConfirmModal = true;
        },
        
        async submitForm() {
            this.isSubmitting = true;
            
            try {
                // Mock AJAX request
                let response = await fetch('{{ route('mt3.community-needs.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        needs: this.needs,
                        communityName: this.communityName,
                        population: this.population,
                        zipCode: this.zipCode,
                        lat: this.lat,
                        lng: this.lng
                    })
                });
                
                this.showConfirmModal = false;
                this.isSubmitting = false;
                this.showSuccessModal = true;
            } catch (e) {
                this.isSubmitting = false;
                this.showConfirmModal = false;
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
    }
}
</script>
@endpush
@endsection


