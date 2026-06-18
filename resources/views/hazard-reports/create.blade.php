@extends('layouts.app')
@section('title', 'แจ้งจุดอันตราย')
@section('page-title', '⚠️ รายงานจุดอันตราย')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-orange-700 p-6 text-white">
            <div class="text-3xl mb-2">⚠️</div>
            <h1 class="text-xl font-bold">รายงานจุดอันตราย</h1>
            <p class="text-orange-100 text-sm mt-1">แจ้งพื้นที่อันตราย เช่น น้ำท่วม ถนนขาด ดินโคลนถล่ม</p>
        </div>

        <form action="{{ route('hazard-reports.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทภัย <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500">
                        <option value="">เลือกประเภท</option>
                        <option value="flood" {{ old('type')=='flood'?'selected':'' }}>🌊 น้ำท่วม</option>
                        <option value="landslide" {{ old('type')=='landslide'?'selected':'' }}>⛰️ ดินโคลนถล่ม</option>
                        <option value="road_damage" {{ old('type')=='road_damage'?'selected':'' }}>🛣️ ถนนเสียหาย/ขาด</option>
                        <option value="power_out" {{ old('type')=='power_out'?'selected':'' }}>⚡ ไฟฟ้าดับ</option>
                        <option value="fire" {{ old('type')=='fire'?'selected':'' }}>🔥 ไฟไหม้</option>
                        <option value="building_damage" {{ old('type')=='building_damage'?'selected':'' }}>🏚️ อาคารเสียหาย</option>
                        <option value="other" {{ old('type')=='other'?'selected':'' }}>อื่นๆ</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัด <span class="text-red-500">*</span></label>
                    <input type="text" name="province" required value="{{ old('province') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500">
                    @error('province') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="ที่อยู่หรือจุดสังเกต..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="grid grid-cols-2 gap-3 mb-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ละติจูด</label>
                        <input type="text" name="latitude" id="lat" value="{{ old('latitude') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-orange-500" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ลองจิจูด</label>
                        <input type="text" name="longitude" id="lng" value="{{ old('longitude') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-orange-500" readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ปักหมุดจุดอันตรายบนแผนที่</label>
                    <div id="hazard-map" class="w-full h-[300px] rounded-xl border border-gray-300 z-10"></div>
                    <p class="text-xs text-gray-500 mt-1">คลิกบนแผนที่หรือลากหมุดเพื่อระบุตำแหน่งที่เกิดเหตุ</p>
                </div>

                <button type="button" onclick="getGPS()" class="w-full py-2.5 bg-green-50 text-green-700 font-medium rounded-xl text-sm border border-green-200 hover:bg-green-100 transition-colors">
                    📍 ใช้ตำแหน่งปัจจุบัน
                </button>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required placeholder="อธิบายสถานการณ์..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ภาพประกอบ</label>
                    <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                </div>

                <button type="submit" class="w-full py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-2xl transition-colors text-lg">
                    📤 ส่งรายงาน
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let map = null;
let marker = null;

document.addEventListener("DOMContentLoaded", function () {
    map = L.map('hazard-map').setView([13.7563, 100.5018], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    map.on('click', function(e) {
        updateMarker(e.latlng.lat, e.latlng.lng);
    });
});

function updateMarker(lat, lng) {
    document.getElementById('lat').value = parseFloat(lat).toFixed(6);
    document.getElementById('lng').value = parseFloat(lng).toFixed(6);

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

function getGPS() {
    if (!navigator.geolocation) { alert('เบราว์เซอร์ไม่รองรับ GPS'); return; }
    navigator.geolocation.getCurrentPosition(pos => {
        updateMarker(pos.coords.latitude, pos.coords.longitude);
    }, () => alert('ไม่สามารถดึงตำแหน่งได้'), { enableHighAccuracy: true });
}
</script>
@endpush
@endsection
