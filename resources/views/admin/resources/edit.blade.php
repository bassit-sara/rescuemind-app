@extends('layouts.admin')
@section('title', 'แก้ไขทรัพยากร')
@section('page-title')
    <x-heroicon-o-archive-box class="w-5 h-5 inline-block shrink-0" /> แก้ไขทรัพยากรช่วยเหลือภัยพิบัติ
@endsection
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white">
            <div class="text-3xl mb-2"><x-heroicon-o-pencil-square class="w-5 h-5 inline-block shrink-0" /></div>
            <h1 class="text-xl font-bold">แก้ไขข้อมูลทรัพยากร / อุปกรณ์</h1>
            <p class="text-blue-100 text-sm mt-1">อัปเดตจำนวน หรือสถานะการใช้งานของทรัพยากรนี้</p>
        </div>

        <form action="{{ route('admin.resources.update', $resource) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อทรัพยากร / ยานพาหนะ / ยาสามัญ <span class="text-red-500">*</span></label>
                <input type="text" name="name" required value="{{ old('name', $resource->name) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ประเภททรัพยากร <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="boat" {{ old('type', $resource->type)=='boat'?'selected':'' }}><x-heroicon-o-paper-airplane class="w-5 h-5 inline-block mr-1 -mt-1" /> เรือ</option>
                        <option value="truck" {{ old('type', $resource->type)=='truck'?'selected':'' }}><x-heroicon-o-truck class="w-5 h-5 inline-block mr-1 -mt-1" /> รถยนต์ / รถบรรทุก</option>
                        <option value="medicine" {{ old('type', $resource->type)=='medicine'?'selected':'' }}><x-heroicon-o-beaker class="w-5 h-5 inline-block mr-1 -mt-1" /> ยา / เวชภัณฑ์</option>
                        <option value="food" {{ old('type', $resource->type)=='food'?'selected':'' }}><x-heroicon-o-cube class="w-5 h-5 inline-block mr-1 -mt-1" /> อาหารแห้ง / ถุงยังชีพ</option>
                        <option value="water" {{ old('type', $resource->type)=='water'?'selected':'' }}><x-heroicon-o-sparkles class="w-5 h-5 inline-block mr-1 -mt-1" /> น้ำดื่ม</option>
                        <option value="other" {{ old('type', $resource->type)=='other'?'selected':'' }}><x-heroicon-o-archive-box class="w-5 h-5 inline-block shrink-0" /> อื่นๆ</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัดที่จัดเก็บ</label>
                    <input type="text" name="province" value="{{ old('province', $resource->province) }}"
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
                    <input type="text" name="unit" value="{{ old('unit', $resource->unit) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่จัดเก็บอย่างละเอียด</label>
                <input type="text" name="location" value="{{ old('location', $resource->location) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between border border-gray-200 p-3 rounded-xl bg-gray-50 mt-4">
                <label class="text-sm font-medium text-gray-700">สถานะคลัง (พร้อมใช้งาน/พร้อมแจกจ่าย)</label>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $resource->is_active ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                    <x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> บันทึกการแก้ไข
                </button>
                <a href="{{ url()->previous() }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-medium">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
