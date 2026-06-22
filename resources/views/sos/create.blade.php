@extends('layouts.app')
@section('title', 'กด SOS — ขอความช่วยเหลือฉุกเฉิน')
@section('page-title')
    <x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /> ขอความช่วยเหลือฉุกเฉิน
@endsection

@section('content')
<div class="max-w-2xl mx-auto" x-data="sosForm()" x-init="initMap()">
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-center gap-3 shadow-sm">
        <span class="text-3xl animate-bounce"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /></span>
        <div>
            <div class="font-bold text-red-800">สถานการณ์ฉุกเฉิน</div>
            <div class="text-sm text-red-600">กรอกข้อมูลให้ครบถ้วนเพื่อให้เจ้าหน้าที่เข้าช่วยเหลือได้รวดเร็วที่สุด</div>
        </div>
    </div>

    <div class="space-y-6">
        @guest
        {{-- Guest Contact Section --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100 relative overflow-hidden">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"><x-heroicon-o-user class="w-5 h-5 inline-block shrink-0" /> ข้อมูลผู้แจ้งเหตุ (Guest)</h2>
            <div class="bg-orange-50 text-orange-700 text-xs p-3 rounded-lg mb-4">
                คุณยังไม่ได้เข้าสู่ระบบ กรุณาระบุชื่อและเบอร์โทรศัพท์เพื่อให้เจ้าหน้าที่สามารถติดต่อกลับได้ หรือ <a href="{{ route('login') }}" class="font-bold underline">เข้าสู่ระบบ</a> เพื่อความรวดเร็ว
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                    <input type="text" x-model="guest_name" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500" placeholder="ระบุชื่อของคุณ" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ติดต่อ <span class="text-red-500">*</span></label>
                    <input type="tel" x-model="guest_phone" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500" placeholder="08X-XXX-XXXX" required>
                </div>
            </div>
        </div>
        @endguest

        {{-- Location Section --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> พิกัดตำแหน่งแบบสด (Live GPS)</h2>
            
            <div class="mb-4 relative">
                <div id="sos-map" class="w-full h-[300px] rounded-xl border border-gray-300 z-10 shadow-inner" wire:ignore></div>
                <div x-show="locating" class="absolute inset-0 bg-white/70 z-20 flex flex-col items-center justify-center rounded-xl backdrop-blur-sm">
                    <div class="w-10 h-10 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                    <span class="mt-2 text-sm font-bold text-blue-800">กำลังค้นหาตำแหน่ง...</span>
                </div>
            </div>

            <button type="button" @click="getLocation()" class="w-full py-3 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 font-bold rounded-xl transition-colors flex items-center justify-center gap-2 mb-4">
                <x-heroicon-o-signal class="w-5 h-5 inline-block mr-1 -mt-1" /> ค้นหาตำแหน่ง GPS ปัจจุบันอีกครั้ง
            </button>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">ละติจูด (Lat)</label>
                    <input type="text" x-model="lat" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 font-mono text-gray-600" readonly>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">ลองจิจูด (Lng)</label>
                    <input type="text" x-model="lng" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 font-mono text-gray-600" readonly>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่ตั้ง (อ้างอิงจากแผนที่)</label>
                <textarea x-model="address" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500 bg-gray-50" rows="2" placeholder="ที่อยู่จะขึ้นอัตโนมัติเมื่อปักหมุด..."></textarea>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัด</label>
                <input type="text" x-model="province" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500" placeholder="เชียงใหม่">
            </div>
        </div>

        {{-- Details Section --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4"><x-heroicon-o-globe-asia-australia class="w-5 h-5 inline-block mr-1 -mt-1" /> สถานการณ์ผู้ประสบภัย</h2>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนผู้ประสบภัย</label>
                    <input type="number" x-model="num_people" min="1" max="999" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ระดับน้ำ</label>
                    <select x-model="water_level" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                        <option value="">ไม่มีน้ำท่วม</option>
                        <option value="knee">ระดับเข่า</option>
                        <option value="waist">ระดับเอว</option>
                        <option value="chest">ระดับอก</option>
                        <option value="neck">ระดับคอ</option>
                        <option value="over">สูงเกินศีรษะ</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">กลุ่มเปราะบาง (ต้องการช่วยเหลือพิเศษ)</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 bg-orange-50 border border-orange-200 rounded-xl cursor-pointer hover:bg-orange-100 transition-colors" :class="has_elderly ? 'ring-2 ring-orange-500' : ''">
                        <input type="checkbox" x-model="has_elderly" class="hidden">
                        <span class="text-sm font-medium text-orange-800"><x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" /> ผู้สูงอายุ</span>
                        <span x-show="has_elderly" class="ml-auto text-orange-600">✓</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-xl cursor-pointer hover:bg-blue-100 transition-colors" :class="has_children ? 'ring-2 ring-blue-500' : ''">
                        <input type="checkbox" x-model="has_children" class="hidden">
                        <span class="text-sm font-medium text-blue-800"><x-heroicon-o-face-smile class="w-5 h-5 inline-block mr-1 -mt-1" /> เด็กเล็ก</span>
                        <span x-show="has_children" class="ml-auto text-blue-600">✓</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-xl cursor-pointer hover:bg-red-100 transition-colors" :class="has_bedridden ? 'ring-2 ring-red-500' : ''">
                        <input type="checkbox" x-model="has_bedridden" class="hidden">
                        <span class="text-sm font-medium text-red-800"><x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" />️ ติดเตียง</span>
                        <span x-show="has_bedridden" class="ml-auto text-red-600">✓</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-pink-50 border border-pink-200 rounded-xl cursor-pointer hover:bg-pink-100 transition-colors" :class="has_pregnant ? 'ring-2 ring-pink-500' : ''">
                        <input type="checkbox" x-model="has_pregnant" class="hidden">
                        <span class="text-sm font-medium text-pink-800"><x-heroicon-o-user-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> คนท้อง</span>
                        <span x-show="has_pregnant" class="ml-auto text-pink-600">✓</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors" :class="has_none ? 'ring-2 ring-gray-500' : ''">
                        <input type="checkbox" x-model="has_none" class="hidden" @change="if(has_none) { has_elderly=false; has_children=false; has_bedridden=false; has_pregnant=false; has_other=false; }">
                        <span class="text-sm font-medium text-gray-800"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> ไม่มี</span>
                        <span x-show="has_none" class="ml-auto text-gray-600">✓</span>
                    </label>
                    <div class="col-span-1">
                        <label class="flex items-center gap-3 p-3 bg-purple-50 border border-purple-200 rounded-xl cursor-pointer hover:bg-purple-100 transition-colors" :class="has_other ? 'ring-2 ring-purple-500' : ''">
                            <input type="checkbox" x-model="has_other" class="hidden" @change="if(has_other) has_none=false;">
                            <span class="text-sm font-medium text-purple-800"><x-heroicon-o-sparkles class="w-5 h-5 inline-block shrink-0" /> อื่นๆ</span>
                            <span x-show="has_other" class="ml-auto text-purple-600">✓</span>
                        </label>
                        <div x-show="has_other" class="mt-2" x-transition>
                            <input type="text" x-model="other_vulnerable" class="w-full px-3 py-2 border border-purple-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 bg-white" placeholder="โปรดระบุ...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2"><x-heroicon-o-archive-box class="w-5 h-5 inline-block shrink-0" /> ความต้องการเร่งด่วน (ถ้ามี)</label>
                <div class="flex flex-wrap gap-2">
                    <template x-for="item in availableNeeds" :key="item.value">
                        <label class="inline-flex items-center px-3 py-1.5 rounded-full border text-sm cursor-pointer transition-colors" 
                               :class="urgent_needs.includes(item.value) ? 'bg-indigo-100 border-indigo-300 text-indigo-800 font-bold' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'">
                            <input type="checkbox" :value="item.value" x-model="urgent_needs" class="hidden">
                            <span x-html="item.label" class="flex items-center gap-1"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"><x-heroicon-o-camera class="w-5 h-5 inline-block shrink-0" /> แนบรูปภาพสถานที่เกิดเหตุ (ไม่บังคับ)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-red-500 transition-colors bg-gray-50 relative cursor-pointer" @click="$refs.fileInput.click()">
                    <div class="space-y-1 text-center" x-show="!imageFile">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <span class="relative font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                อัปโหลดไฟล์รูปภาพ
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF ขนาดไม่เกิน 5MB</p>
                    </div>
                    <div x-ref="imagePreviewContainer" class="hidden w-full h-48 overflow-hidden rounded-lg">
                        <img x-ref="imagePreview" class="object-cover w-full h-full" alt="Image preview">
                    </div>
                    <button x-show="imageFile" type="button" @click.stop="removeImage()" class="absolute top-2 right-2 p-1 bg-white rounded-full shadow-md text-red-600 hover:text-red-800">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>
                <input type="file" x-ref="fileInput" class="hidden" accept="image/*" @change="handleImageUpload">
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดเพิ่มเติม</label>
                <textarea x-model="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500 resize-none" placeholder="อธิบายสถานการณ์เพิ่มเติม (ถ้ามี)..."></textarea>
            </div>
        </div>

        {{-- Swipe to Submit --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <p class="text-center text-sm font-bold text-red-600 mb-3">เลื่อนขวาเพื่อส่งคำขอความช่วยเหลือฉุกเฉิน</p>
            <div id="swipe-track" x-ref="swipeTrack" class="relative h-16 bg-red-100 rounded-full overflow-hidden border border-red-200 shadow-inner">
                <div class="absolute inset-0 flex items-center justify-center text-red-400 font-bold tracking-widest opacity-60">SWIPE TO SOS >>></div>
                <div id="swipe-slider" x-ref="swipeSlider"
                     @mousedown="startDrag"
                     @touchstart.passive="startDrag"
                     @mousemove.window="onDrag"
                     @touchmove.window.passive="onDrag"
                     @mouseup.window="endDrag"
                     @touchend.window="endDrag"
                     class="absolute top-1 bottom-1 left-1 w-14 bg-red-600 rounded-full shadow-md flex items-center justify-center cursor-grab active:cursor-grabbing text-white"
                     :style="`transform: translateX(${swipeLeft}px); transition: ${isDragging ? 'none' : 'transform 0.3s'}`">
                    <span class="text-xl"><x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Confirm Modal --}}
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" @click="showConfirmModal = false"></div>
            <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <span class="text-xl"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /></span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-bold text-gray-900">ยืนยันการขอความช่วยเหลือ</h3>
                        <div class="mt-2 text-sm text-gray-500">
                            คุณกำลังจะส่งสัญญาณ SOS ไปยังศูนย์รับแจ้งเหตุ ข้อมูลและตำแหน่งของคุณจะถูกส่งไปให้เจ้าหน้าที่ กรุณายืนยันหากนี่คือเหตุฉุกเฉินจริง
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="submitEmergencyReport()" :disabled="isSubmitting" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span x-show="!isSubmitting">ยืนยันส่ง SOS</span>
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
                    <span class="text-3xl"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">ส่งคำขอสำเร็จ!</h3>
                <p class="text-sm text-gray-500 mb-6" x-text="successMessage"></p>
                <a :href="redirectUrl" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-green-600 text-base font-bold text-white hover:bg-green-700 focus:outline-none">
                    ตกลง
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
                    <span class="text-3xl"><x-heroicon-o-x-circle class="w-5 h-5 inline-block shrink-0" /></span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">เกิดข้อผิดพลาด</h3>
                <p class="text-sm text-gray-500 mb-6" x-text="errorMessage"></p>
                <button type="button" @click="showErrorModal = false" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function sosForm() {
    return {
        is_guest: {{ auth()->check() ? 'false' : 'true' }},
        guest_name: '', guest_phone: '',
        lat: '', lng: '', address: '', province: @json(auth()->check() ? auth()->user()->province : ''),
        num_people: 1, water_level: '', has_elderly: false, has_children: false, has_bedridden: false, has_pregnant: false, has_none: false, has_other: false, other_vulnerable: '', description: '',
        imageFile: null,
        
        urgent_needs: [],
        availableNeeds: [
            {value: 'food', label: `<x-heroicon-o-cube class="w-5 h-5 inline-block mr-1 -mt-1" /> อาหาร`},
            {value: 'water', label: `<x-heroicon-o-sparkles class="w-5 h-5 inline-block mr-1 -mt-1" /> น้ำดื่ม`},
            {value: 'medicine', label: `<x-heroicon-o-beaker class="w-5 h-5 inline-block mr-1 -mt-1" /> ยารักษาโรค`},
            {value: 'boat', label: `<x-heroicon-o-paper-airplane class="w-5 h-5 inline-block mr-1 -mt-1" /> เรืออพยพ`},
            {value: 'electricity', label: `<x-heroicon-o-battery-100 class="w-5 h-5 inline-block mr-1 -mt-1" /> ไฟฟ้า/พาวเวอร์แบงก์`},
            {value: 'clothing', label: `<x-heroicon-o-shopping-bag class="w-5 h-5 inline-block mr-1 -mt-1" /> เสื้อผ้า/ผ้าห่ม`}
        ],

        locating: false, locationSuccess: false, locationError: false,
        map: null, marker: null, accuracyCircle: null, liveDot: null, watchId: null,
        
        showConfirmModal: false, showSuccessModal: false, showErrorModal: false,
        errorMessage: '', successMessage: 'ส่งคำขอความช่วยเหลือสำเร็จ! เจ้าหน้าที่จะติดต่อกลับโดยเร็ว', redirectUrl: '{{ route('sos.my') }}',
        isSubmitting: false,
        
        swipeLeft: 0, startX: 0, isDragging: false,
        
        initMap() {
            this.map = L.map('sos-map').setView([13.7563, 100.5018], 5);
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
                    this.lat = pos.lat.toFixed(6);
                    this.lng = pos.lng.toFixed(6);
                    this.reverseGeocode(this.lat, this.lng);
                });
            }
            if(!isAuto) {
                this.map.setView([this.lat, this.lng], 16);
            }
            this.reverseGeocode(this.lat, this.lng);
        },
        
        startTracking() {
            this.locating = true;
            if (!navigator.geolocation) { this.locating = false; return; }
            
            // Stop existing watch if any
            if(this.watchId) navigator.geolocation.clearWatch(this.watchId);
            
            this.watchId = navigator.geolocation.watchPosition(
                (pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    const accuracy = pos.coords.accuracy;
                    
                    this.locating = false;
                    
                    // Live Dot
                    if(this.liveDot) {
                        this.liveDot.setLatLng([lat, lng]);
                    } else {
                        this.liveDot = L.circleMarker([lat, lng], {
                            radius: 6, color: '#ffffff', weight: 2, fillColor: '#3b82f6', fillOpacity: 1
                        }).addTo(this.map);
                    }
                    
                    // Accuracy Circle
                    if(this.accuracyCircle) {
                        this.accuracyCircle.setLatLng([lat, lng]);
                        this.accuracyCircle.setRadius(accuracy);
                    } else {
                        this.accuracyCircle = L.circle([lat, lng], {
                            radius: accuracy, color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: 0.1, weight: 1
                        }).addTo(this.map);
                    }
                    
                    // If marker is not placed by user yet, snap to live location
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
            // Force center to live location
            if(this.liveDot) {
                const pos = this.liveDot.getLatLng();
                this.updateMarker(pos.lat, pos.lng, false);
                this.map.setView([pos.lat, pos.lng], 16);
            } else {
                this.startTracking();
            }
        },
        
        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=th`);
                const data = await response.json();
                if(data && data.display_name) {
                    this.address = data.display_name;
                    // Try to extract province if possible
                    if(data.address && data.address.province) {
                        this.province = data.address.province.replace('จ.', '').trim();
                    }
                }
            } catch (e) {
                console.error("Reverse geocode failed", e);
            }
        },
        
        startDrag(e) {
            this.isDragging = true;
            let clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            this.startX = clientX - this.swipeLeft;
        },
        
        onDrag(e) {
            if(!this.isDragging) return;
            let clientX = e.type.includes('mouse') ? e.clientX : (e.touches ? e.touches[0].clientX : 0);
            if (!clientX && e.changedTouches) clientX = e.changedTouches[0].clientX;
            
            let x = clientX - this.startX;
            let track = this.$refs.swipeTrack;
            let slider = this.$refs.swipeSlider;
            let max = track.clientWidth - slider.clientWidth;
            
            if(x < 0) x = 0;
            if(x > max) x = max;
            this.swipeLeft = x;
            
            if(x >= max - 5) {
                this.isDragging = false;
                this.swipeLeft = 0;
                this.validateAndConfirm();
            }
        },
        
        endDrag() {
            this.isDragging = false;
            if(this.swipeLeft > 0) this.swipeLeft = 0;
        },
        
        validateAndConfirm() {
            if(this.is_guest && (!this.guest_name || !this.guest_phone)) {
                this.errorMessage = "กรุณาระบุชื่อและเบอร์โทรศัพท์ติดต่อกลับ";
                this.showErrorModal = true;
                return;
            }
            if(!this.lat || !this.lng) {
                this.errorMessage = "กรุณาระบุพิกัดตำแหน่งบนแผนที่";
                this.showErrorModal = true;
                return;
            }
            if(!this.num_people || this.num_people < 1) {
                this.errorMessage = "กรุณาระบุจำนวนผู้ประสบภัย";
                this.showErrorModal = true;
                return;
            }
            this.showConfirmModal = true;
        },
        
        handleImageUpload(e) {
            if(e.target.files.length > 0) {
                const file = e.target.files[0];
                if(file.size > 5 * 1024 * 1024) {
                    this.errorMessage = "ไฟล์รูปภาพต้องมีขนาดไม่เกิน 5MB";
                    this.showErrorModal = true;
                    e.target.value = '';
                    return;
                }
                this.imageFile = file;
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.$refs.imagePreview.src = event.target.result;
                    this.$refs.imagePreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(this.imageFile);
            }
        },
        
        removeImage() {
            this.imageFile = null;
            this.$refs.fileInput.value = '';
            this.$refs.imagePreviewContainer.classList.add('hidden');
            this.$refs.imagePreview.src = '';
        },

        async submitEmergencyReport() {
            this.isSubmitting = true;
            
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('guest_name', this.guest_name);
            formData.append('guest_phone', this.guest_phone);
            formData.append('latitude', this.lat);
            formData.append('longitude', this.lng);
            formData.append('address', this.address);
            formData.append('province', this.province);
            formData.append('num_people', this.num_people);
            formData.append('water_level', this.water_level);
            formData.append('has_elderly', this.has_elderly ? 1 : 0);
            formData.append('has_children', this.has_children ? 1 : 0);
            formData.append('has_bedridden', this.has_bedridden ? 1 : 0);
            formData.append('has_pregnant', this.has_pregnant ? 1 : 0);
            if(this.has_other && this.other_vulnerable) formData.append('other_vulnerable', this.other_vulnerable);
            formData.append('description', this.description);
            this.urgent_needs.forEach(need => formData.append('urgent_needs[]', need));
            
            if(this.imageFile) {
                formData.append('image', this.imageFile);
            }
            
            try {
                let response = await fetch('{{ route('sos.store') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });
                
                let data = await response.json();
                this.showConfirmModal = false;
                this.isSubmitting = false;
                
                if(data.success) {
                    this.successMessage = data.message;
                    this.redirectUrl = data.redirect;
                    this.showSuccessModal = true;
                } else {
                    this.errorMessage = data.message || "เกิดข้อผิดพลาดจากเซิร์ฟเวอร์";
                    this.showErrorModal = true;
                }
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
