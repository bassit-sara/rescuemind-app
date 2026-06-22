@extends('layouts.app')

@section('title', 'MT2 ระหว่างเกิดภัย (Emergency Response)')
@section('page-title')
    มิติที่ 2: ระหว่างเกิดภัย
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Back Button --}}
    <div>
        <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-red-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ย้อนกลับหน้าหลัก
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-red-600 to-red-800 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="text-5xl mb-4"><x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /></div>
            <h1 class="text-3xl font-bold mb-2">ระหว่างเกิดภัย (Response)</h1>
            <p class="text-red-100 text-lg max-w-2xl">
                ระบบร้องขอความช่วยเหลือฉุกเฉิน (SOS) รายงานเหตุการณ์ แจ้งบุคคลสูญหาย และตรวจสอบสถานะความปลอดภัยของคนในครอบครัว
            </p>
        </div>
    </div>

    {{-- Main SOS Action --}}
    <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-2">คุณต้องการความช่วยเหลือฉุกเฉินหรือไม่?</h2>
        <p class="text-gray-500 mb-6">ทีมงานและอาสาสมัครพร้อมเข้าช่วยเหลือตลอด 24 ชั่วโมง</p>
        
        <a href="{{ route('sos.create') }}" class="inline-flex flex-col items-center justify-center w-32 h-32 bg-red-600 text-white rounded-full shadow-xl hover:bg-red-700 transition-all transform hover:scale-105 pulse-sos mx-auto">
            <span class="text-3xl mb-1"><x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /></span>
            <span class="font-black text-xl">SOS</span>
        </a>
        
        @auth
        <div class="mt-6">
            <a href="{{ route('sos.my') }}" class="text-red-600 font-semibold hover:underline"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> ติดตามสถานะ SOS ของฉัน</a>
        </div>
        @endauth
    </div>

    {{-- Other Features Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <a href="{{ route('missing-persons.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-red-200 transition-all group">
            <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 inline-block shrink-0" />
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">แจ้งคนหาย</h2>
            <p class="text-sm text-gray-500">ประกาศตามหาบุคคลสูญหายจากเหตุภัยพิบัติ</p>
        </a>

        <a href="{{ route('hazard-reports.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-red-200 transition-all group">
            <div class="w-14 h-14 bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" />
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">รายงานภัยพิบัติ</h2>
            <p class="text-sm text-gray-500">รายงานสถานการณ์น้ำท่วม ดินถล่ม หรือสิ่งกีดขวางในพื้นที่ของคุณ</p>
        </a>

        @auth
        <a href="{{ route('family.status') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-red-200 transition-all group">
            <div class="w-14 h-14 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <x-heroicon-o-user-group class="w-5 h-5 inline-block shrink-0" />
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">ครอบครัวปลอดภัย</h2>
            <p class="text-sm text-gray-500">อัปเดตสถานะความปลอดภัยของคุณให้ครอบครัวรับทราบ</p>
        </a>
        @else
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex flex-col items-center justify-center text-center opacity-70">
            <div class="text-3xl mb-2"><x-heroicon-o-lock-closed class="w-5 h-5 inline-block shrink-0" /></div>
            <p class="text-sm text-gray-600 font-medium">เข้าสู่ระบบเพื่อใช้งานระบบติดตามครอบครัว</p>
        </div>
        @endauth

    </div>

</div>
@endsection
