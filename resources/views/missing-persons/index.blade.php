@extends('layouts.app')
@section('title', 'แจ้งคนหาย')
@section('page-title')
    <x-heroicon-o-magnifying-glass class="w-5 h-5 inline-block shrink-0" /> แจ้งคนหาย / ค้นหาผู้สูญหาย
@endsection
@section('content')

{{-- Create Form --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-bold text-gray-800"><x-heroicon-o-pencil-square class="w-5 h-5 inline-block shrink-0" /> แจ้งคนหาย</h2>
    </div>
    <form action="{{ route('missing-persons.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                <input type="text" name="name" required value="{{ old('name') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">อายุ</label>
                <input type="number" name="age" value="{{ old('age') }}" min="0" max="120"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">เพศ</label>
                <select name="gender" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                    <option value="">ไม่ระบุ</option>
                    <option value="male" {{ old('gender')=='male'?'selected':'' }}>ชาย</option>
                    <option value="female" {{ old('gender')=='female'?'selected':'' }}>หญิง</option>
                    <option value="other" {{ old('gender')=='other'?'selected':'' }}>อื่นๆ</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">พื้นที่ ที่หายไป <span class="text-red-500">*</span></label>
                <input type="text" name="province" required value="{{ old('province') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                @error('province') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่ล่าสุดที่พบ</label>
                <input type="text" name="last_seen_location" value="{{ old('last_seen_location') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">เวลาที่หายไป</label>
                <input type="datetime-local" name="last_seen_at" value="{{ old('last_seen_at') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพคนที่หาย</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย / ลักษณะ <span class="text-red-500">*</span></label>
                <textarea name="description" rows="3" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรติดต่อ <span class="text-red-500">*</span></label>
                <input type="tel" name="contact_phone" required value="{{ old('contact_phone') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <button type="submit" class="mt-6 w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl transition-colors">
            <x-heroicon-o-paper-airplane class="w-5 h-5 inline-block mr-1 -mt-1" /> ส่งแจ้งคนหาย
        </button>
    </form>
</div>

{{-- Missing Persons List --}}
<h2 class="text-xl font-bold text-gray-800 mb-4"><x-heroicon-o-magnifying-glass class="w-5 h-5 inline-block mr-1 -mt-1" /> รายการคนหายล่าสุด</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse($missingPersons as $mp)
    <a href="{{ route('missing-persons.show', $mp) }}" class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all flex gap-4">
        @if($mp->photo_url)
        <img src="{{ $mp->photo_url }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0" alt="{{ $mp->name }}">
        @else
        <div class="w-16 h-16 rounded-xl bg-amber-100 flex items-center justify-center text-2xl flex-shrink-0">
            {{ $mp->gender == 'female' ? '<x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" />' : '<x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" />' }}
        </div>
        @endif
        <div class="min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <h3 class="font-bold text-gray-800 truncate">{{ $mp->name }}</h3>
                <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0
                    {{ $mp->status == 'found' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    @if($mp->status == 'found')
                        <x-heroicon-s-check-circle class="w-4 h-4 inline-block shrink-0" /> พบแล้ว
                    @else
                        <x-heroicon-s-magnifying-glass class="w-4 h-4 inline-block shrink-0" /> ยังหาย
                    @endif
                </span>
            </div>
            <div class="text-sm text-gray-500">อายุ {{ $mp->age ?? '-' }} ปี • {{ $mp->province }}</div>
            @if($mp->last_seen_location)
            <div class="text-xs text-gray-400 mt-1 truncate"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> {{ $mp->last_seen_location }}</div>
            @endif
            <div class="text-xs text-gray-400">{{ $mp->created_at->diffForHumans() }}</div>
        </div>
    </a>
    @empty
    <div class="col-span-2 text-center py-16 text-gray-400">
        <div class="text-5xl mb-3"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></div>
        <div>ไม่พบรายการคนหาย</div>
    </div>
    @endforelse
</div>
<div class="mt-6">{{ $missingPersons->links() }}</div>
@endsection
