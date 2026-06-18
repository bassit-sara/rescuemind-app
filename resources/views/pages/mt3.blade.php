@extends('layouts.app')

@section('title', 'MT3 หลังเกิดภัย (Recovery & Mental Health)')
@section('page-title', 'มิติที่ 3: หลังเกิดภัย')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-purple-600 to-indigo-800 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="text-5xl mb-4">🧠</div>
            <h1 class="text-3xl font-bold mb-2">หลังเกิดภัย (Recovery)</h1>
            <p class="text-purple-100 text-lg max-w-2xl">
                การฟื้นฟูเยียวยาจิตใจหลังเผชิญเหตุการณ์ร้ายแรง ประเมินความเครียด ติดตามอารมณ์ และปรึกษาผู้เชี่ยวชาญด้านสุขภาพจิต
            </p>
        </div>
    </div>

    {{-- Main Features Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        @auth
        <a href="{{ route('mental.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-200 transition-all group col-span-1 md:col-span-2 flex items-center gap-6">
            <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-3xl flex-shrink-0 group-hover:scale-110 transition-transform">
                💖
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-1">ศูนย์สุขภาพจิต (Mental Health Center)</h2>
                <p class="text-sm text-gray-500">ดูภาพรวมสุขภาพจิตของคุณ จัดการการนัดหมาย และเข้าถึงแหล่งข้อมูลการดูแลจิตใจด้วยตัวเอง</p>
            </div>
            <div class="hidden sm:block ml-auto text-purple-200 group-hover:text-purple-600 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <a href="{{ route('mental.assess.create', 'phq9') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-200 transition-all group">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                📋
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">แบบประเมินความซึมเศร้า (PHQ-9)</h2>
            <p class="text-sm text-gray-500">ทำแบบประเมินมาตรฐานเพื่อวิเคราะห์ระดับความเครียดและซึมเศร้าของคุณ</p>
        </a>

        <a href="{{ route('mental.mood.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-200 transition-all group">
            <div class="w-14 h-14 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                😊
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">บันทึกอารมณ์ (Mood Tracker)</h2>
            <p class="text-sm text-gray-500">ติดตามการเปลี่ยนแปลงทางอารมณ์ของคุณในแต่ละวันเพื่อดูแนวโน้มการฟื้นฟู</p>
        </a>
        @else
        
        <div class="col-span-1 md:col-span-2 bg-purple-50 rounded-2xl p-8 border border-purple-100 text-center">
            <div class="text-5xl mb-4">🔐</div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">ฟีเจอร์สุขภาพจิตสงวนไว้สำหรับสมาชิก</h2>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">เพื่อความเป็นส่วนตัวและความปลอดภัยของข้อมูลสุขภาพของคุณ กรุณาเข้าสู่ระบบก่อนใช้งาน</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="px-6 py-2.5 bg-white text-purple-700 font-semibold rounded-xl shadow-sm border border-purple-200 hover:bg-purple-50 transition-colors">เข้าสู่ระบบ</a>
                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-purple-600 text-white font-semibold rounded-xl shadow-sm hover:bg-purple-700 transition-colors">สมัครสมาชิกฟรี</a>
            </div>
        </div>

        @endauth
    </div>

</div>
@endsection
