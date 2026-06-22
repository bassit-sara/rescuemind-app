@extends('layouts.app')
@section('title', 'ขอความช่วยเหลือฟื้นฟูบ้าน')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen" x-data="homeRecoveryForm()" x-init="initMap()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mt3') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-emerald-600 hover:bg-emerald-50 shadow-sm transition-colors shrink-0">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-o-home-modern class="w-7 h-7 text-emerald-500" />
                        ขอความช่วยเหลือฟื้นฟูบ้าน
                    </h2>
                    <p class="text-gray-600 text-[15px] font-medium mt-1">แจ้งขอรับความช่วยเหลือในการทำความสะอาด ล้างโคลน ซ่อมแซมระบบไฟฟ้าและประปาเบื้องต้น</p>
                </div>
            </div>
            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
            <div class="flex items-center gap-2 shrink-0">
                <button class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-200 flex items-center gap-2 transition-colors">
                    <x-heroicon-o-pencil-square class="w-4 h-4" /> จัดการข้อมูลคำขอ
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
                <form action="{{ route('mt3.submit-form') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Location Section -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2">1. ตำแหน่งที่ตั้ง</h3>
                            
                            <!-- Live GPS Section -->
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden mb-6">
                                <h4 class="text-md font-black text-gray-800 mb-4 flex items-center gap-2"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> พิกัดตำแหน่งแบบสด (Live GPS)</h4>
                                
                                <div class="mb-4 relative">
                                    <div id="recovery-map" class="w-full h-[300px] rounded-xl border border-gray-300 z-10 shadow-inner" wire:ignore></div>
                                    <div x-show="locating" class="absolute inset-0 bg-white/70 z-20 flex flex-col items-center justify-center rounded-xl backdrop-blur-sm" style="display: none;">
                                        <div class="w-10 h-10 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin"></div>
                                        <span class="mt-2 text-sm font-bold text-emerald-800">กำลังค้นหาตำแหน่ง...</span>
                                    </div>
                                </div>

                                <button type="button" @click="getLocation()" class="w-full py-3 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 font-bold rounded-xl transition-colors flex items-center justify-center gap-2 mb-4">
                                    <x-heroicon-o-signal class="w-5 h-5 inline-block mr-1 -mt-1" /> ค้นหาตำแหน่ง GPS ปัจจุบันอีกครั้ง
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
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">บ้านเลขที่ / หมู่บ้าน</label>
                                    <input type="text" x-model="address" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required placeholder="เช่น 123/45 หมู่บ้านสุขใจ">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสไปรษณีย์</label>
                                    <input type="text" x-model="zipCode" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">จุดสังเกตเพิ่มเติม (ถ้ามี)</label>
                                    <input type="text" x-model="landmark" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" placeholder="เช่น ใกล้ร้านสะดวกซื้อ หรือ วัด...">
                                </div>
                            </div>
                        </div>

                        <!-- Needs Section -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">2. ประเภทความช่วยเหลือที่ต้องการ</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-start p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" value="cleaning" x-model="needs" class="mt-1 w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3">
                                        <span class="block text-gray-800 font-bold group-hover:text-emerald-700">ทำความสะอาด / ล้างโคลน</span>
                                        <span class="block text-gray-600 font-medium text-xs mt-1">สูบน้ำ, ล้างโคลน, ทำความสะอาดพื้นและผนัง</span>
                                    </span>
                                </label>
                                <label class="flex items-start p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" value="electric" x-model="needs" class="mt-1 w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3">
                                        <span class="block text-gray-800 font-bold group-hover:text-emerald-700">ซ่อมแซมระบบไฟฟ้าเบื้องต้น</span>
                                        <span class="block text-gray-600 font-medium text-xs mt-1">เช็คปลั๊กไฟ, สายไฟ, เบรกเกอร์</span>
                                    </span>
                                </label>
                                <label class="flex items-start p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" value="plumbing" x-model="needs" class="mt-1 w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3">
                                        <span class="block text-gray-800 font-bold group-hover:text-emerald-700">ซ่อมแซมระบบประปา</span>
                                        <span class="block text-gray-600 font-medium text-xs mt-1">ท่อแตก, ปั๊มน้ำไม่ทำงาน</span>
                                    </span>
                                </label>
                                <label class="flex items-start p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" value="structure" x-model="needs" class="mt-1 w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3">
                                        <span class="block text-gray-800 font-bold group-hover:text-emerald-700">ซ่อมแซมโครงสร้างเบื้องต้น</span>
                                        <span class="block text-gray-600 font-medium text-xs mt-1">หลังคารั่ว, ผนังร้าว, ประตูชำรุด</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Details & Photos -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">3. รายละเอียดเพิ่มเติมและรูปภาพ</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">อธิบายสภาพความเสียหายเพิ่มเติม</label>
                                    <textarea x-model="details" rows="4" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">อัพโหลดรูปภาพความเสียหาย (สูงสุด 5 รูป)</label>
                                    <label class="mt-1 flex justify-center px-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-emerald-400 hover:bg-emerald-50 transition-colors cursor-pointer group relative"
                                           :class="photoPreviews.length > 0 ? 'py-4' : 'pt-5 pb-6'">
                                        <div class="space-y-1 text-center" x-show="photoPreviews.length === 0">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-emerald-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <span class="relative font-bold text-emerald-600 group-hover:text-emerald-500">
                                                    <span>คลิกเพื่ออัพโหลดรูปภาพ</span>
                                                    <input id="photos" name="photos[]" type="file" multiple accept="image/png, image/jpeg, image/gif" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" x-on:change="handleFiles">
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 font-medium mt-1">PNG, JPG, GIF ไม่เกิน 10MB</p>
                                        </div>
                                        
                                        <div class="text-center" x-show="photoPreviews.length > 0" style="display: none;">
                                            <x-heroicon-o-camera class="w-8 h-8 mx-auto text-emerald-400 group-hover:text-emerald-500 transition-colors mb-1" />
                                            <span class="font-bold text-sm text-emerald-600">คลิกเพื่อเลือกรูปภาพใหม่</span>
                                        </div>
                                    </label>

                                    <!-- Image Previews -->
                                    <div x-show="photoPreviews.length > 0" class="mt-3 grid grid-cols-3 sm:grid-cols-5 gap-3" style="display: none;">
                                        <template x-for="(src, index) in photoPreviews" :key="index">
                                            <div class="relative aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-50">
                                                <img :src="src" class="w-full h-full object-cover">
                                                <button type="button" @click.stop="removePhoto(index)" class="absolute top-1.5 right-1.5 bg-white/90 hover:bg-red-500 hover:text-white text-gray-700 w-6 h-6 rounded-full flex items-center justify-center backdrop-blur-sm transition-colors shadow-sm focus:outline-none">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Swipe to Submit --}}
                    <div class="mt-10 bg-white rounded-2xl p-4 shadow-sm border border-red-100">
                        <p class="text-center text-sm font-bold text-red-600 mb-3">เลื่อนขวาเพื่อส่งคำขอความช่วยเหลือฟื้นฟูบ้าน</p>
                        <div id="swipe-track" class="relative h-16 bg-red-100 rounded-full overflow-hidden border border-red-200 shadow-inner">
                            <div class="absolute inset-0 flex items-center justify-center text-red-400 font-bold tracking-widest opacity-60">SWIPE TO SUBMIT >>></div>
                            <div id="swipe-slider" 
                                 class="absolute top-1 bottom-1 left-1 w-14 bg-red-600 rounded-full shadow-md flex items-center justify-center cursor-grab active:cursor-grabbing text-white z-10"
                                 :style="`transform: translateX(${swipeLeft}px); transition: ${isDragging ? 'none' : 'transform 0.3s'}`">
                                <span class="text-xl"><x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /></span>
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
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-emerald-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <span class="text-xl"><x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0 text-emerald-600" /></span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-black text-gray-900">ยืนยันการขอความช่วยเหลือฟื้นฟู</h3>
                            <div class="mt-2 text-[15px] text-gray-600 font-medium">
                                คุณกำลังจะส่งคำขอความช่วยเหลือฟื้นฟูบ้านไปยังส่วนกลาง ข้อมูลและตำแหน่งพิกัดของคุณจะถูกส่งให้ทีมอาสาสมัครลงพื้นที่ กรุณายืนยันการส่งข้อมูล
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="submitRecoveryRequest()" :disabled="isSubmitting" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span x-show="!isSubmitting">ยืนยันส่งคำขอ</span>
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
                    <h3 class="text-lg font-black text-gray-900 mb-2">ส่งคำขอสำเร็จ!</h3>
                    <p class="text-[15px] text-gray-600 font-medium mb-6" x-text="successMessage"></p>
                    <a href="{{ route('mt3.recovery-tracking') }}" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-green-600 text-base font-bold text-white hover:bg-green-700 focus:outline-none">
                        ติดตามสถานะการฟื้นฟู
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
function homeRecoveryForm() {
    return {
        lat: '', lng: '', address: '', zipCode: '', landmark: '', details: '', needs: [],
        locating: false, map: null, marker: null, accuracyCircle: null, liveDot: null, watchId: null,
        
        swipeLeft: 0, startX: 0, isDragging: false,
        showConfirmModal: false, showSuccessModal: false, showErrorModal: false,
        isSubmitting: false, successMessage: 'ส่งคำขอความช่วยเหลือฟื้นฟูบ้านเรียบร้อยแล้ว ทีมงานจะติดต่อกลับและลงพื้นที่โดยเร็วที่สุด', errorMessage: '',
        
        photoPreviews: [],
        
        handleFiles(e) {
            const files = e.target.files;
            if (files.length > 5) {
                this.errorMessage = "อัพโหลดรูปภาพได้สูงสุด 5 รูปครับ";
                this.showErrorModal = true;
                e.target.value = '';
                return;
            }
            this.photoPreviews = [];
            for (let i = 0; i < files.length; i++) {
                if (i < 5) {
                    this.photoPreviews.push(URL.createObjectURL(files[i]));
                }
            }
        },
        
        removePhoto(index) {
            this.photoPreviews.splice(index, 1);
            const dt = new DataTransfer();
            const input = document.getElementById('photos');
            const { files } = input;
            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }
            input.files = dt.files;
        },
        
        initMap() {
            // Default center: Bangkok
            this.map = L.map('recovery-map').setView([13.7563, 100.5018], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19, attribution: '© OpenStreetMap'
            }).addTo(this.map);

            this.map.on('click', (e) => {
                this.updateMarker(e.latlng.lat, e.latlng.lng, false);
            });
            setTimeout(() => { this.map.invalidateSize(); }, 300);
            
            this.startTracking();
            this.initSwipe();
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
                    this.lat = pos.lat.toFixed(6);
                    this.lng = pos.lng.toFixed(6);
                });
            }
            if(!isAuto) {
                this.map.setView([this.lat, this.lng], 16);
            }
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
                            radius: 6, color: '#ffffff', weight: 2, fillColor: '#3b82f6', fillOpacity: 1
                        }).addTo(this.map);
                    }
                    
                    if(this.accuracyCircle) {
                        this.accuracyCircle.setLatLng([lat, lng]);
                        this.accuracyCircle.setRadius(accuracy);
                    } else {
                        this.accuracyCircle = L.circle([lat, lng], {
                            radius: accuracy, color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: 0.1, weight: 1
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
                this.errorMessage = "กรุณาระบุพิกัดตำแหน่งบนแผนที่";
                this.showErrorModal = true;
                return;
            }
            this.showConfirmModal = true;
        },
        
        async submitRecoveryRequest() {
            this.isSubmitting = true;
            
            try {
                let response = await fetch('{{ route('mt3.home-recovery.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        lat: this.lat, lng: this.lng, address: this.address, zipCode: this.zipCode, landmark: this.landmark, needs: this.needs, details: this.details 
                    })
                });
                
                this.showConfirmModal = false;
                this.isSubmitting = false;
                this.showSuccessModal = true;
            } catch (e) {
                this.isSubmitting = false;
                this.showConfirmModal = false;
                this.errorMessage = "ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้";
                this.showErrorModal = true;
            }
        }
    }
}
</script>
@endpush
@endsection



