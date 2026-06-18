@extends('layouts.admin')
@section('title', 'สร้างการแจ้งเตือน')
@section('page-title', '🚨 สร้างการแจ้งเตือนภัย')
@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-red-800 p-6 text-white">
                <div class="text-3xl mb-2">🚨</div>
                <h1 class="text-xl font-bold">ออกประกาศแจ้งเตือนภัย</h1>
                <p class="text-red-100 text-sm mt-1">ประกาศจะแสดงต่อผู้ใช้ทุกคนในระบบทันที</p>
            </div>

            <form action="{{ route('admin.alerts.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หัวข้อ <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทภัย <span
                                class="text-red-500">*</span></label>
                        <select name="disaster_type" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                            <option value="">เลือก</option>
                            <option value="flood">🌊 น้ำท่วม</option>
                            <option value="storm">🌀 พายุ</option>
                            <option value="landslide">⛰️ ดินโคลนถล่ม</option>
                            <option value="earthquake">🏔️ แผ่นดินไหว</option>
                            <option value="fire">🔥 ไฟ</option>
                            <option value="other">อื่นๆ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ระดับความรุนแรง <span
                                class="text-red-500">*</span></label>
                        <select name="level" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                            <option value="1" {{ old('level') == 1 ? 'selected' : '' }}>🟡 ระดับ 1 - เฝ้าระวัง</option>
                            <option value="2" {{ old('level') == 2 ? 'selected' : '' }}>🟠 ระดับ 2 - เตือนภัย</option>
                            <option value="3" {{ old('level') == 3 ? 'selected' : '' }}>🔴 ระดับ 3 - อันตราย</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัดที่ได้รับผลกระทบ
                        (ว่างหมายถึงทั่วประเทศ)</label>
                    <select name="province"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500">
                        <option value="">-- ทั่วประเทศ (ไม่ระบุจังหวัด) --</option>
                        @foreach(['ยะลา', 'ปัตตานี', 'นราธิวาส', 'สงขลา',] as $p)
                            <option value="{{ $p }}" {{ old('province') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด <span
                            class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500 resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">คำแนะนำสำหรับประชาชน</label>
                    <textarea name="instructions" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm resize-none">{{ old('instructions') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">เวลาออกประกาศ</label>
                        <input type="datetime-local" name="issued_at"
                            value="{{ old('issued_at', now()->format('Y-m-d\TH:i')) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมดอายุเมื่อ</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                </div>

                <div class="flex items-center gap-3 p-4 bg-red-50 rounded-xl">
                    <input type="checkbox" name="is_active" value="1" id="is_active" checked
                        class="w-4 h-4 text-red-600 rounded">
                    <label for="is_active" class="text-sm font-medium text-red-700">เปิดใช้งานทันที
                        (แสดงต่อผู้ใช้ทุกคน)</label>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl transition-colors">
                        📢 ออกประกาศแจ้งเตือน
                    </button>
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-6 py-4 bg-gray-100 text-gray-700 rounded-2xl hover:bg-gray-200 transition-colors font-medium">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
@endsection