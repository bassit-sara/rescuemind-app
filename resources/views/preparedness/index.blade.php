@extends('layouts.app')
@section('title', 'Preparedness Checklist')
@section('page-title', '📋 เตรียมพร้อมรับมือภัยพิบัติ')
@section('content')

<div class="max-w-2xl mx-auto">
    {{-- Hero --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-3xl p-8 mb-8 text-center">
        <div class="text-5xl mb-4">🛡️</div>
        <h1 class="text-2xl font-black mb-2">Preparedness Checklist</h1>
        <p class="text-blue-100">เตรียมพร้อมก่อนภัยพิบัติจะช่วยชีวิตคุณและครอบครัว</p>
    </div>

    {{-- Checklist Categories --}}
    @foreach($checklists as $category => $items)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5 overflow-hidden" x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
            <h2 class="text-lg font-bold text-gray-800">{{ $category }}</h2>
            <span class="text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''">▼</span>
        </button>
        <div x-show="open" x-transition class="divide-y divide-gray-50">
            @foreach($items as $i => $item)
            <div class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition-colors" x-data="{ checked: false }">
                <button @click="checked = !checked" class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full border-2 transition-all"
                        :class="checked ? 'bg-green-500 border-green-500' : 'border-gray-300'">
                    <span x-show="checked" class="text-white text-xs flex items-center justify-center h-full">✓</span>
                </button>
                <div :class="checked ? 'opacity-60 line-through' : ''">
                    <div class="font-medium text-gray-800 text-sm">{{ $item['title'] }}</div>
                    @if(isset($item['detail']))
                    <div class="text-xs text-gray-500 mt-0.5">{{ $item['detail'] }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Emergency Numbers --}}
    <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mt-6">
        <h2 class="font-bold text-red-800 text-lg mb-4">📞 เบอร์ฉุกเฉิน</h2>
        <div class="grid grid-cols-2 gap-3">
            @foreach([['🚨','แจ้งเหตุฉุกเฉิน','191'],['🚒','ดับเพลิง','199'],['🚑','กู้ชีพ','1669'],['🏥','สายด่วน กรมสุขภาพ','1422'],['🌊','กรมชลประทาน','1460'],['🧠','สายด่วนสุขภาพจิต','1323']] as [$icon,$label,$num])
            <a href="tel:{{ $num }}" class="flex items-center gap-3 p-3 bg-white rounded-xl hover:bg-red-50 transition-colors">
                <span class="text-2xl">{{ $icon }}</span>
                <div>
                    <div class="text-sm font-medium text-gray-700">{{ $label }}</div>
                    <div class="text-lg font-black text-red-600">{{ $num }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
