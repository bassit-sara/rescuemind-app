@extends('layouts.app')
@section('title', 'แจ้งคนหาย')
@section('page-title', '🔍 แจ้งเบาะแสคนหาย')
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-orange-600 p-6 text-white">
            <div class="text-3xl mb-2">🔍</div>
            <h1 class="text-xl font-bold">ลงทะเบียนแจ้งคนหาย</h1>
            <p class="text-red-100 text-sm mt-1">กรอกข้อมูลผู้สูญหายเพื่อให้เจ้าหน้าที่และอาสาสมัครช่วยประสานงานค้นหา</p>
        </div>

        <form action="{{ route('missing-persons.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล ผู้สูญหาย <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="ระบุชื่อผู้สูญหาย"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">อายุ (ปี)</label>
                    <input type="number" name="age" value="{{ old('age') }}" min="0" max="120" placeholder="ระบุอายุโดยประมาณ"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                    @error('age') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เพศ</label>
                    <select name="gender" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                        <option value="">ระบุเพศ</option>
                        <option value="male" {{ old('gender')=='male'?'selected':'' }}>ชาย</option>
                        <option value="female" {{ old('gender')=='female'?'selected':'' }}>หญิง</option>
                        <option value="other" {{ old('gender')=='other'?'selected':'' }}>อื่นๆ</option>
                    </select>
                    @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัดที่หายตัวไป</label>
                    <input type="text" name="province" value="{{ old('province') ?? auth()->user()->province }}" placeholder="ระบุชื่อจังหวัด"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                    @error('province') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันและเวลาที่พบตัวล่าสุด</label>
                    <input type="datetime-local" name="last_seen_at" value="{{ old('last_seen_at') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                    @error('last_seen_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">พิกัดละติจูด (Latitude)</label>
                    <input type="text" name="last_seen_lat" id="lat" value="{{ old('last_seen_lat') }}" placeholder="เช่น 13.7563"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-red-500" readonly>
                    @error('last_seen_lat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">พิกัดลองจิจูด (Longitude)</label>
                    <input type="text" name="last_seen_lng" id="lng" value="{{ old('last_seen_lng') }}" placeholder="เช่น 100.5018"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-red-500" readonly>
                    @error('last_seen_lng') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">ปักหมุดตำแหน่งที่พบตัวล่าสุด</label>
                <div id="missing-map" class="w-full h-[300px] rounded-xl border border-gray-300 z-10"></div>
                <p class="text-xs text-gray-500 mt-1">คลิกบนแผนที่หรือลากหมุดเพื่อระบุตำแหน่ง</p>
            </div>

            <div class="p-3 bg-red-50 rounded-xl text-xs text-red-700 flex justify-between items-center">
                <span>📍 ท่านสามารถดึงตำแหน่งปัจจุบันของคุณเพื่อใช้แทนจุดพบตัวล่าสุดได้</span>
                <button type="button" onclick="getLocation()" class="underline font-bold hover:text-red-900">ดึงตำแหน่ง GPS</button>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รูปถ่ายผู้สูญหาย</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ลักษณะรูปพรรณ / ข้อมูลเพิ่มเติม</label>
                <textarea name="description" rows="4" placeholder="ระบุการแต่งตัวล่าสุด ตำหนิ รูปพรรณสัณฐาน หรือเบอร์ผู้แจ้งเบาะแส..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500 resize-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl transition-colors shadow-sm">
                📢 แจ้งคนหายลงระบบ
            </button>
        </form>
    </div>
</div>

<script>
let map = null;
let marker = null;

document.addEventListener("DOMContentLoaded", function () {
    map = L.map('missing-map').setView([13.7563, 100.5018], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    map.on('click', function(e) {
        updateMarker(e.latlng.lat, e.latlng.lng);
    });
});

function updateMarker(lat, lng) {
    document.getElementById('lat').value = lat.toFixed(6);
    document.getElementById('lng').value = lng.toFixed(6);

    if(marker) {
        marker.setLatLng([lat, lng]);
    } else {
        marker = L.marker([lat, lng], {draggable: true}).addTo(map);
        marker.on('dragend', function(e) {
            let pos = e.target.getLatLng();
            document.getElementById('lat').value = pos.lat.toFixed(6);
            document.getElementById('lng').value = pos.lng.toFixed(6);
        });
    }
    map.setView([lat, lng], 14);
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            updateMarker(position.coords.latitude, position.coords.longitude);
        }, function(error) {
            alert('ไม่สามารถดึงตำแหน่งพิกัดได้: ' + error.message);
        }, { enableHighAccuracy: true });
    } else {
        alert('เบราว์เซอร์ของคุณไม่รองรับการดึงพิกัด');
    }
}
</script>

@endsection
