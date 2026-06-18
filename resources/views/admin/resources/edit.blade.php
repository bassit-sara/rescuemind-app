@extends('layouts.admin')
@section('title', 'แก้ไขทรัพยากร')
@section('page-title', '📦 แก้ไขทรัพยากรช่วยเหลือภัยพิบัติ')
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white">
            <div class="text-3xl mb-2">📦</div>
            <h1 class="text-xl font-bold">แก้ไขคลังทรัพยากร: {{ $resource->name }}</h1>
            <p class="text-blue-100 text-sm mt-1">อัปเดตข้อมูลทรัพยากรช่วยเหลือภัยพิบัติเข้าระบบ</p>
        </div>

        <form action="{{ route('admin.resources.update', $resource) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อทรัพยากร / ยานพาหนะ / ยาสามัญ <span class="text-red-500">*</span></label>
                <input type="text" name="name" required value="{{ old('name', $resource->name) }}" placeholder="เช่น เรือท้องแบนขนาดใหญ่"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ประเภททรัพยากร <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="boat" {{ old('type', $resource->type)=='boat'?'selected':'' }}>⛵ เรือ</option>
                        <option value="truck" {{ old('type', $resource->type)=='truck'?'selected':'' }}>🚚 รถยนต์ / รถบรรทุก</option>
                        <option value="medicine" {{ old('type', $resource->type)=='medicine'?'selected':'' }}>💊 ยา / เวชภัณฑ์</option>
                        <option value="food" {{ old('type', $resource->type)=='food'?'selected':'' }}>🍱 อาหารแห้ง / ถุงยังชีพ</option>
                        <option value="water" {{ old('type', $resource->type)=='water'?'selected':'' }}>💧 น้ำดื่ม</option>
                        <option value="other" {{ old('type', $resource->type)=='other'?'selected':'' }}>📦 อื่นๆ</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัดที่จัดเก็บ</label>
                    <input type="text" name="province" value="{{ old('province', $resource->province) }}" placeholder="ระบุจังหวัด"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    @error('province') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนทั้งหมด <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" required value="{{ old('quantity', $resource->quantity) }}" min="0"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนที่พร้อมใช้ <span class="text-red-500">*</span></label>
                    <input type="number" name="available_quantity" required value="{{ old('available_quantity', $resource->available_quantity) }}" min="0"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    @error('available_quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หน่วยนับ</label>
                    <input type="text" name="unit" value="{{ old('unit', $resource->unit) }}" placeholder="เช่น ลำ, กล่อง, ชิ้น"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่จัดเก็บอย่างละเอียด</label>
                <input type="text" name="location" value="{{ old('location', $resource->location) }}" placeholder="เช่น คลังบรรเทาสาธารณภัยเขต 1"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">สถานะใช้งาน</label>
                <select name="is_active" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="1" {{ $resource->is_active ? 'selected':'' }}>เปิดใช้งาน (Active)</option>
                    <option value="0" {{ !$resource->is_active ? 'selected':'' }}>ปิดใช้งาน / เลิกใช้งาน</option>
                </select>
                @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                    ✅ บันทึกการเปลี่ยนแปลง
                </button>
                <a href="{{ route('admin.resources.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-medium">
                    กลับหน้าหลักทรัพยากร
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
