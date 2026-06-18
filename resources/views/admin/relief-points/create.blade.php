@extends('layouts.admin')
@section('title', 'เพิ่มจุดช่วยเหลือ')
@section('page-title', '🏕️ เพิ่มจุดช่วยเหลือ')
@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-800 p-6 text-white">
                <div class="text-3xl mb-2">🏕️</div>
                <h1 class="text-xl font-bold">เพิ่มจุดช่วยเหลือใหม่</h1>
            </div>
            <form action="{{ route('admin.relief-points.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อจุดช่วยเหลือ <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ประเภท <span
                                class="text-red-500">*</span></label>
                        <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                            <option value="shelter">🏠 ที่พักพิง (Shelter)</option>
                            <option value="hospital">🏥 จุดพยาบาล / โรงพยาบาล</option>
                            <option value="food">🍱 แจกอาหารและน้ำ</option>
                            <option value="parking">🚗 จุดจอดรถหนีน้ำ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัด</label>
                        @php
                            $provinces = ['ยะลา', 'ปัตตานี', 'นราธิวาส', 'สงขลา'];
                        @endphp
                        <select name="province" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                            <option value="">-- ระบุจังหวัด --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p }}" {{ old('province') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ที่อยู่</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ละติจูด</label>
                        <input type="text" name="latitude" id="lat" value="{{ old('latitude') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ลองจิจูด</label>
                        <input type="text" name="longitude" id="lng" value="{{ old('longitude') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ความจุ (คน)</label>
                        <input type="number" name="capacity" value="{{ old('capacity') }}" min="0"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">โทรศัพท์ติดต่อ</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div class="col-span-2">
                        <button type="button" onclick="getGPS()"
                            class="w-full py-2.5 bg-green-50 text-green-700 font-medium rounded-xl text-sm border border-green-200 hover:bg-green-100">
                            📍 ดึงตำแหน่ง GPS
                        </button>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors">✅
                        บันทึก</button>
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-medium">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            function getGPS() {
                if (!navigator.geolocation) return;
                navigator.geolocation.getCurrentPosition(p => {
                    document.getElementById('lat').value = p.coords.latitude.toFixed(6);
                    document.getElementById('lng').value = p.coords.longitude.toFixed(6);
                });
            }
        </script>
    @endpush
@endsection