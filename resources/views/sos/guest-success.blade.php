@extends('layouts.app')
@section('title', 'ส่งคำขอความช่วยเหลือสำเร็จ')
@section('page-title')
    <x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> ส่งคำขอสำเร็จ
@endsection

@section('content')
<div class="max-w-xl mx-auto mt-10">
    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-gray-100 text-center relative overflow-hidden">
        
        <!-- Decorative background elements -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-green-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        
        <div class="relative z-10">
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6 shadow-inner border-4 border-green-50">
                <span class="text-5xl animate-bounce"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></span>
            </div>
            
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-4 tracking-tight">ระบบได้รับข้อมูลของคุณแล้ว</h2>
            
            <p class="text-gray-600 mb-8 leading-relaxed">
                เจ้าหน้าที่กู้ภัยในพื้นที่ใกล้เคียงได้รับพิกัดตำแหน่งและเบอร์โทรศัพท์ของคุณเรียบร้อยแล้ว <br class="hidden sm:block">
                <span class="font-bold text-red-600">กรุณารอการติดต่อกลับและเตรียมตัวให้พร้อม</span>
            </p>

            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-8 text-left text-sm text-blue-800">
                <div class="font-bold mb-2 flex items-center gap-2">
                    <span><x-heroicon-o-information-circle class="w-5 h-5 inline-block shrink-0" /></span> คำแนะนำเบื้องต้น:
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    <li>พยายามอยู่ในที่ปลอดภัยและอยู่ในจุดที่สังเกตเห็นได้ง่าย</li>
                    <li>เปิดโทรศัพท์มือถือไว้เสมอเพื่อให้เจ้าหน้าที่ติดต่อได้</li>
                    <li>หากสถานการณ์เลวร้ายลงอย่างฉับพลัน โปรดโทร 1669 ทันที</li>
                </ul>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition-colors w-full sm:w-auto">
                    กลับสู่หน้าหลัก
                </a>
                <a href="{{ route('login') }}" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-red-500/30 w-full sm:w-auto">
                    เข้าสู่ระบบ / สมัครสมาชิก
                </a>
            </div>
            <p class="text-xs text-gray-400 mt-6">
                *การสมัครสมาชิกจะช่วยให้คุณสามารถติดตามสถานะการช่วยเหลือและดูประวัติย้อนหลังได้
            </p>
        </div>
    </div>
</div>
@endsection
