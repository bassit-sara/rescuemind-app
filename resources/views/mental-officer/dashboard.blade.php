@extends('layouts.admin')
@section('title', 'Mental Officer Dashboard')
@section('page-title')
    <x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" /> ศูนย์สุขภาพจิต - เจ้าหน้าที่
@endsection
@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-purple-50 border border-purple-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-purple-600">{{ $stats['total_assessments'] }}</div>
        <div class="text-sm text-purple-700 font-medium">การประเมินทั้งหมด</div>
    </div>
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-red-600">{{ $stats['severe_cases'] }}</div>
        <div class="text-sm text-red-700 font-medium">เคสรุนแรง</div>
    </div>
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-orange-600">{{ $stats['pending_appointments'] }}</div>
        <div class="text-sm text-orange-700 font-medium">นัดหมายรอยืนยัน</div>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
        <div class="text-3xl font-black text-green-600">{{ $stats['today_appointments'] }}</div>
        <div class="text-sm text-green-700 font-medium">นัดวันนี้</div>
    </div>
</div>

{{-- Weather Widget --}}
<div class="mb-6">
    <x-weather-widget />
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Severe/High Risk Assessments --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800"><span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-1"></span> เคสที่ต้องใส่ใจ</h2>
            <a href="{{ route('mental-officer.assessments') }}" class="text-sm text-purple-600 hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($severeAssessments as $a)
            <a href="{{ route('mental-officer.assessments.show', $a) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                <div>
                    <div class="font-medium text-gray-800">{{ $a->user->name }}</div>
                    <div class="text-xs text-gray-500">{{ strtoupper($a->type) }} • {{ $a->created_at->diffForHumans() }}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-red-600">{{ $a->score }} คะแนน</div>
                    <span class="text-xs px-2 py-0.5 bg-red-100 text-red-700 rounded-full">{{ $a->severity_label }}</span>
                </div>
            </a>
            @empty
            <div class="p-8 text-center text-gray-400">
                <div class="text-3xl mb-2"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></div>
                <div class="text-sm">ไม่มีเคสเร่งด่วน</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Pending Appointments --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800"><x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> นัดหมายรอยืนยัน</h2>
            <a href="{{ route('mental-officer.appointments') }}" class="text-sm text-purple-600 hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($pendingAppointments as $appt)
            <div class="p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-medium text-gray-800">{{ $appt->user->name }}</div>
                        <div class="text-xs text-gray-500">
                            {{ ['video'=>'<x-heroicon-o-computer-desktop class="w-5 h-5 inline-block mr-1 -mt-1" /> ออนไลน์','in_person'=>'<x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" /> พบหน้า'][$appt->type] ?? $appt->type }}
                            • {{ $appt->scheduled_at?->format('d/m/Y H:i') }}
                        </div>
                        @if($appt->notes)
                        <div class="text-xs text-gray-400 mt-1 truncate">{{ Str::limit($appt->notes, 60) }}</div>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2 mt-3">
                    <form action="{{ route('mental-officer.appointments.status', $appt) }}" method="POST" class="flex gap-2 w-full">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="flex-1 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> ยืนยัน</button>
                    </form>
                    <form action="{{ route('mental-officer.appointments.status', $appt) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="px-4 py-1.5 bg-red-100 text-red-700 text-xs font-bold rounded-lg hover:bg-red-200">ยกเลิก</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">
                <div class="text-sm">ไม่มีนัดหมายรอ</div>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Assessment Type Distribution --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-6 p-6">
    <h2 class="font-bold text-gray-800 mb-4"><x-heroicon-o-chart-bar class="w-5 h-5 inline-block shrink-0" /> การกระจายตัวระดับความรุนแรง (7 วันล่าสุด)</h2>
    <div class="grid grid-cols-4 gap-4">
        @foreach(['minimal' => ['label'=>'ปกติ','color'=>'bg-green-500'], 'mild' => ['label'=>'น้อย','color'=>'bg-yellow-500'], 'moderate' => ['label'=>'ปานกลาง','color'=>'bg-orange-500'], 'severe' => ['label'=>'รุนแรง','color'=>'bg-red-500']] as $sev => $info)
        <div class="text-center">
            <div class="h-20 flex items-end justify-center mb-2">
                @php $cnt = $severityDist[$sev] ?? 0; $max = max(1, $severityDist->max() ?? 1); @endphp
                <div class="{{ $info['color'] }} rounded-t-lg w-10 transition-all" style="height: {{ max(8, round($cnt/$max*100)).'%' }}"></div>
            </div>
            <div class="text-xl font-black text-gray-800">{{ $cnt }}</div>
            <div class="text-xs text-gray-500">{{ $info['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection
