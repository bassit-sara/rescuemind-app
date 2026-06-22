@extends('layouts.app')
@section('title', $missingPerson->name.' - คนหาย')
@section('page-title')
    <x-heroicon-o-magnifying-glass class="w-5 h-5 inline-block shrink-0" /> รายละเอียดคนหาย
@endsection
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 text-center bg-amber-50">
            @if($missingPerson->photo_url)
            <img src="{{ $missingPerson->photo_url }}" class="w-28 h-28 rounded-full object-cover mx-auto mb-4 border-4 border-white shadow-md" alt="{{ $missingPerson->name }}">
            @else
            <div class="w-28 h-28 rounded-full bg-amber-200 flex items-center justify-center text-5xl mx-auto mb-4">
                {{ $missingPerson->gender == 'female' ? '<x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" />' : '<x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" />' }}
            </div>
            @endif
            <h1 class="text-2xl font-black text-gray-800 mb-1">{{ $missingPerson->name }}</h1>
            <span class="px-3 py-1 rounded-full text-sm font-bold
                {{ $missingPerson->status == 'found' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                @if($missingPerson->status == 'found')
                    <x-heroicon-s-check-circle class="w-5 h-5 inline-block shrink-0" /> พบแล้ว
                @else
                    <x-heroicon-s-magnifying-glass class="w-5 h-5 inline-block shrink-0" /> ยังสูญหาย
                @endif
            </span>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-500 mb-1">อายุ</div>
                    <div class="font-bold text-gray-800">{{ $missingPerson->age ?? '-' }} ปี</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-500 mb-1">เพศ</div>
                    <div class="font-bold text-gray-800">{{ $missingPerson->gender == 'male' ? 'ชาย' : ($missingPerson->gender == 'female' ? 'หญิง' : 'ไม่ระบุ') }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-500 mb-1">จังหวัด</div>
                    <div class="font-bold text-gray-800">{{ $missingPerson->province }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-500 mb-1">แจ้งเมื่อ</div>
                    <div class="font-bold text-gray-800">{{ $missingPerson->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            @if($missingPerson->last_seen_location)
            <div class="p-4 bg-amber-50 rounded-xl" x-data="{ showWeather: false }">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500 mb-1">สถานที่พบล่าสุด</div>
                        <div class="font-medium text-gray-800"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> {{ $missingPerson->last_seen_location }}</div>
                        @if($missingPerson->last_seen_at)
                        <div class="text-xs text-gray-500 mt-1">{{ $missingPerson->last_seen_at->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>
                    <button @click="showWeather = !showWeather" type="button" class="flex flex-col items-center justify-center p-2 rounded-xl bg-white hover:bg-amber-100 border border-amber-200 text-amber-600 transition-colors shadow-sm w-16 h-16 shrink-0" title="เพิ่มสภาพอากาศในพื้นที่นี้">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="text-[10px] font-bold">เพิ่มสภาพอากาศ</span>
                    </button>
                </div>

                <div x-show="showWeather" x-transition.duration.300ms class="mt-4" style="display: none;">
                    <div class="h-[480px]">
                        <x-weather-widget />
                    </div>
                </div>
            </div>
            @endif

            <div class="p-4 bg-gray-50 rounded-xl">
                <div class="text-xs text-gray-500 mb-2">ลักษณะ / รายละเอียด</div>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $missingPerson->description }}</p>
            </div>

            @if($missingPerson->contact_phone)
            <a href="tel:{{ $missingPerson->contact_phone }}" class="flex items-center justify-center gap-2 w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl transition-colors">
                <x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /> โทรหาผู้แจ้ง: {{ $missingPerson->contact_phone }}
            </a>
            @endif

            <a href="{{ route('missing-persons.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700 mt-2">
                ← กลับรายการคนหาย
            </a>
        </div>
    </div>
</div>
@endsection
