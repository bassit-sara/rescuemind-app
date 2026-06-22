@extends('layouts.admin')
@section('title', 'แก้ไขแจ้งเตือน')
@section('page-title')
    <x-heroicon-o-pencil class="w-5 h-5 inline-block mr-1 -mt-1" />️ แก้ไขแจ้งเตือน
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('admin.alerts.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        ← กลับไปรายการแจ้งเตือน
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">แก้ไขการแจ้งเตือน</h2>

        <form action="{{ route('admin.alerts.update', $alert) }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">หัวข้อ</label>
                <input type="text" name="title" value="{{ old('title', $alert->title) }}" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">ข้อความ</label>
                <textarea name="message" rows="4" required
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">{{ old('message', $alert->message) }}</textarea>
                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ระดับความรุนแรง</label>
                    <select name="level" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="1" {{ old('level', $alert->level) == 1 ? 'selected' : '' }}><span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-1"></span> ระดับ 1 — เฝ้าระวัง</option>
                        <option value="2" {{ old('level', $alert->level) == 2 ? 'selected' : '' }}><span class="inline-block w-3 h-3 rounded-full bg-orange-500 mr-1"></span> ระดับ 2 — เตรียมอพยพ</option>
                        <option value="3" {{ old('level', $alert->level) == 3 ? 'selected' : '' }}><span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-1"></span> ระดับ 3 — อพยพทันที</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">สถานะ</label>
                    <select name="is_active"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="1" {{ old('is_active', $alert->is_active) ? 'selected' : '' }}><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> เปิดใช้งาน</option>
                        <option value="0" {{ !old('is_active', $alert->is_active) ? 'selected' : '' }}><x-heroicon-o-x-circle class="w-5 h-5 inline-block shrink-0" /> ปิดใช้งาน</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">หมดอายุ</label>
                <input type="datetime-local" name="expires_at"
                       value="{{ old('expires_at', $alert->expires_at ? $alert->expires_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg text-sm font-medium">
                    <x-heroicon-o-circle-stack class="w-5 h-5 inline-block mr-1 -mt-1" /> บันทึกการเปลี่ยนแปลง
                </button>
                <a href="{{ route('admin.alerts.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors text-sm font-medium">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
