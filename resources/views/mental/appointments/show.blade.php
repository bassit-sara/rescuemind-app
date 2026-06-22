@extends('layouts.app')
@section('title', 'รายละเอียดนัดหมาย')
@section('page-title')
    <x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> รายละเอียดนัดหมาย
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('mental.appointments.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        ← กลับไปรายการนัดหมาย
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-teal-50 to-cyan-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center
                        {{ $appointment->type === 'video' ? 'bg-blue-100' : 'bg-purple-100' }}">
                        <span class="text-3xl">{{ $appointment->type === 'video' ? '<x-heroicon-o-video-camera class="w-5 h-5 inline-block mr-1 -mt-1" />' : '<x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" />' }}</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            {{ $appointment->type === 'video' ? 'ปรึกษาออนไลน์ (Video Call)' : 'ปรึกษาแบบพบตัว' }}
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">
                            สร้างเมื่อ {{ $appointment->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'confirmed' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'completed' => 'bg-green-100 text-green-700 border-green-200',
                        'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                    ];
                    $statusLabels = [
                        'pending' => 'รอตอบรับ',
                        'confirmed' => 'ยืนยันแล้ว',
                        'completed' => 'เสร็จสิ้น',
                        'cancelled' => 'ยกเลิก',
                    ];
                @endphp
                <span class="inline-flex px-4 py-1.5 rounded-full text-sm font-semibold border {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $statusLabels[$appointment->status] ?? $appointment->status }}
                </span>
            </div>
        </div>

        {{-- Details --}}
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">วันนัดหมาย</div>
                    <div class="font-semibold text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y') }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">เวลา</div>
                    <div class="font-semibold text-gray-800">{{ $appointment->scheduled_at->format('H:i') }} น.</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">ประเภท</div>
                    <div class="font-semibold text-gray-800">
                        {{ $appointment->type === 'video' ? '<x-heroicon-o-video-camera class="w-5 h-5 inline-block mr-1 -mt-1" /> ปรึกษาออนไลน์' : '<x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" /> พบตัว' }}
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">ผู้เชี่ยวชาญ</div>
                    <div class="font-semibold text-gray-800">
                        {{ $appointment->mentalOfficer ? $appointment->mentalOfficer->name : 'รอเจ้าหน้าที่ตอบรับ' }}
                    </div>
                </div>
            </div>

            @if($appointment->notes)
            <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-2">หมายเหตุ</div>
                <div class="bg-gray-50 rounded-xl p-4 text-gray-700 leading-relaxed">
                    {{ $appointment->notes }}
                </div>
            </div>
            @endif

            @if($appointment->meeting_link && $appointment->status === 'confirmed')
            <div class="pt-4 border-t border-gray-100">
                <a href="{{ $appointment->meeting_link }}" target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg text-sm font-medium">
                    <x-heroicon-o-video-camera class="w-5 h-5 inline-block mr-1 -mt-1" /> เข้าร่วม Video Call
                </a>
            </div>
            @endif

            @if($appointment->status === 'pending')
            <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-200 text-sm text-yellow-700">
                <x-heroicon-o-clock class="w-5 h-5 inline-block mr-1 -mt-1" /> นัดหมายของคุณอยู่ในระหว่างรอการตอบรับจากผู้เชี่ยวชาญ เราจะแจ้งให้ทราบเมื่อมีการอัปเดต
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
