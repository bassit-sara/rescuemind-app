@extends('layouts.app')
@section('title', 'การนัดหมาย')
@section('page-title')
    <x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> การนัดหมายของฉัน
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">การนัดหมาย</h2>
            <p class="text-gray-500 text-sm mt-1">ดูประวัติและสร้างนัดหมายกับผู้เชี่ยวชาญ</p>
        </div>
        <a href="{{ route('mental.appointments.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl hover:from-teal-600 hover:to-cyan-700 transition-all shadow-lg text-sm font-medium">
            <x-heroicon-o-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> นัดหมายใหม่
        </a>
    </div>

    {{-- Appointment List --}}
    @forelse($appointments as $appointment)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center
                    {{ $appointment->type === 'video' ? 'bg-blue-100' : 'bg-purple-100' }}">
                    <span class="text-2xl">{{ $appointment->type === 'video' ? '<x-heroicon-o-video-camera class="w-5 h-5 inline-block mr-1 -mt-1" />' : '<x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" />' }}</span>
                </div>
                <div>
                    <div class="font-semibold text-gray-800">
                        {{ $appointment->type === 'video' ? 'ปรึกษาออนไลน์ (Video Call)' : 'ปรึกษาแบบพบตัว' }}
                    </div>
                    <div class="text-sm text-gray-500 mt-0.5">
                        <x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> {{ $appointment->scheduled_at->format('d/m/Y H:i') }} น.
                    </div>
                    @if($appointment->mentalOfficer)
                    <div class="text-sm text-gray-500">
                        <x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" />‍<x-heroicon-o-heart class="w-5 h-5 inline-block mr-1 -mt-1" />️ {{ $appointment->mentalOfficer->name }}
                    </div>
                    @else
                    <div class="text-sm text-orange-500">รอเจ้าหน้าที่ตอบรับ</div>
                    @endif
                </div>
            </div>
            <div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ];
                    $statusLabels = [
                        'pending' => 'รอตอบรับ',
                        'confirmed' => 'ยืนยันแล้ว',
                        'completed' => 'เสร็จสิ้น',
                        'cancelled' => 'ยกเลิก',
                    ];
                @endphp
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $statusLabels[$appointment->status] ?? $appointment->status }}
                </span>
            </div>
        </div>

        @if($appointment->notes)
        <div class="mt-3 p-3 bg-gray-50 rounded-xl text-sm text-gray-600">
            {{ $appointment->notes }}
        </div>
        @endif

        @if($appointment->meeting_link && $appointment->status === 'confirmed')
        <div class="mt-3">
            <a href="{{ $appointment->meeting_link }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm hover:bg-blue-700 transition-colors">
                <x-heroicon-o-video-camera class="w-5 h-5 inline-block mr-1 -mt-1" /> เข้าร่วม Video Call
            </a>
        </div>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="text-5xl mb-4"><x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /></div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">ยังไม่มีการนัดหมาย</h3>
        <p class="text-gray-500 text-sm mb-4">สร้างนัดหมายเพื่อปรึกษาผู้เชี่ยวชาญด้านสุขภาพจิต</p>
        <a href="{{ route('mental.appointments.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-500 text-white rounded-xl hover:bg-teal-600 transition-colors text-sm font-medium">
            <x-heroicon-o-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> นัดหมายเลย
        </a>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($appointments->hasPages())
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
    @endif
</div>
@endsection
