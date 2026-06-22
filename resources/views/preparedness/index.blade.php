@extends('layouts.app')
@section('title', 'Preparedness Checklist')
@section('page-title')
    <x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" /> เตรียมพร้อมรับมือภัยพิบัติ
@endsection
@section('content')

<div class="max-w-2xl mx-auto">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('mt1') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ย้อนกลับ
        </a>
    </div>

    {{-- Hero --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-3xl p-8 mb-8 text-center">
        <div class="text-5xl mb-4"><x-heroicon-o-shield-check class="w-5 h-5 inline-block shrink-0" /></div>
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
        <h2 class="font-bold text-red-800 text-lg mb-4"><x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /> เบอร์ฉุกเฉิน</h2>
        <div class="grid grid-cols-2 gap-3">
            <a href="tel:191" class="flex items-center gap-3 p-3 bg-white rounded-xl hover:bg-red-50 transition-colors">
                <span class="text-2xl"><x-heroicon-o-bell class="w-8 h-8 text-red-500" /></span>
                <div>
                    <div class="text-sm font-medium text-gray-700">แจ้งเหตุฉุกเฉิน</div>
                    <div class="text-lg font-black text-red-600">191</div>
                </div>
            </a>
            <a href="tel:199" class="flex items-center gap-3 p-3 bg-white rounded-xl hover:bg-red-50 transition-colors">
                <span class="text-2xl"><x-heroicon-o-truck class="w-8 h-8 text-orange-500" /></span>
                <div>
                    <div class="text-sm font-medium text-gray-700">ดับเพลิง</div>
                    <div class="text-lg font-black text-red-600">199</div>
                </div>
            </a>
            <a href="tel:1669" class="flex items-center gap-3 p-3 bg-white rounded-xl hover:bg-red-50 transition-colors">
                <span class="text-2xl"><x-heroicon-o-truck class="w-8 h-8 text-blue-500" /></span>
                <div>
                    <div class="text-sm font-medium text-gray-700">กู้ชีพ</div>
                    <div class="text-lg font-black text-red-600">1669</div>
                </div>
            </a>
            <a href="tel:1422" class="flex items-center gap-3 p-3 bg-white rounded-xl hover:bg-red-50 transition-colors">
                <span class="text-2xl"><x-heroicon-o-building-office-2 class="w-8 h-8 text-green-500" /></span>
                <div>
                    <div class="text-sm font-medium text-gray-700">สายด่วน กรมสุขภาพ</div>
                    <div class="text-lg font-black text-red-600">1422</div>
                </div>
            </a>
            <a href="tel:1460" class="flex items-center gap-3 p-3 bg-white rounded-xl hover:bg-red-50 transition-colors">
                <span class="text-2xl"><x-heroicon-o-globe-asia-australia class="w-8 h-8 text-blue-500" /></span>
                <div>
                    <div class="text-sm font-medium text-gray-700">กรมชลประทาน</div>
                    <div class="text-lg font-black text-red-600">1460</div>
                </div>
            </a>
            <a href="tel:1323" class="flex items-center gap-3 p-3 bg-white rounded-xl hover:bg-red-50 transition-colors">
                <span class="text-2xl"><x-heroicon-o-heart class="w-8 h-8 text-pink-500" /></span>
                <div>
                    <div class="text-sm font-medium text-gray-700">สายด่วนสุขภาพจิต</div>
                    <div class="text-lg font-black text-red-600">1323</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
