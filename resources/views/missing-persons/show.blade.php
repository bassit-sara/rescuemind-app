@extends('layouts.app')
@section('title', $missingPerson->name.' - คนหาย')
@section('page-title', '🔍 รายละเอียดคนหาย')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 text-center bg-amber-50">
            @if($missingPerson->photo_url)
            <img src="{{ $missingPerson->photo_url }}" class="w-28 h-28 rounded-full object-cover mx-auto mb-4 border-4 border-white shadow-md" alt="{{ $missingPerson->name }}">
            @else
            <div class="w-28 h-28 rounded-full bg-amber-200 flex items-center justify-center text-5xl mx-auto mb-4">
                {{ $missingPerson->gender == 'female' ? '👩' : '👨' }}
            </div>
            @endif
            <h1 class="text-2xl font-black text-gray-800 mb-1">{{ $missingPerson->name }}</h1>
            <span class="px-3 py-1 rounded-full text-sm font-bold
                {{ $missingPerson->status == 'found' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                {{ $missingPerson->status == 'found' ? '✅ พบแล้ว' : '🔍 ยังสูญหาย' }}
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
            <div class="p-4 bg-amber-50 rounded-xl">
                <div class="text-xs text-gray-500 mb-1">สถานที่พบล่าสุด</div>
                <div class="font-medium text-gray-800">📍 {{ $missingPerson->last_seen_location }}</div>
                @if($missingPerson->last_seen_at)
                <div class="text-xs text-gray-500 mt-1">{{ $missingPerson->last_seen_at->format('d/m/Y H:i') }}</div>
                @endif
            </div>
            @endif

            <div class="p-4 bg-gray-50 rounded-xl">
                <div class="text-xs text-gray-500 mb-2">ลักษณะ / รายละเอียด</div>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $missingPerson->description }}</p>
            </div>

            @if($missingPerson->contact_phone)
            <a href="tel:{{ $missingPerson->contact_phone }}" class="flex items-center justify-center gap-2 w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl transition-colors">
                📞 โทรหาผู้แจ้ง: {{ $missingPerson->contact_phone }}
            </a>
            @endif

            <a href="{{ route('missing-persons.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700 mt-2">
                ← กลับรายการคนหาย
            </a>
        </div>
    </div>
</div>
@endsection
