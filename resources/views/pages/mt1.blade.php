@extends('layouts.app')

@section('title', 'MT1 ก่อนเกิดภัย (Early Warning & Preparedness)')
@section('page-title', 'มิติที่ 1: ก่อนเกิดภัย')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="text-5xl mb-4">🌊</div>
            <h1 class="text-3xl font-bold mb-2">ก่อนเกิดภัย (Preparedness)</h1>
            <p class="text-blue-100 text-lg max-w-2xl">
                เตรียมความพร้อมก่อนเกิดเหตุการณ์ฉุกเฉิน ติดตามการแจ้งเตือนภัย ค้นหาจุดช่วยเหลือที่ใกล้ที่สุด และเช็คลิสต์สิ่งที่ต้องเตรียมพร้อม
            </p>
        </div>
    </div>

    {{-- Main Features Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <a href="{{ route('alerts.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                🚨
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">การแจ้งเตือนภัย</h2>
            <p class="text-sm text-gray-500">ติดตามประกาศเตือนภัยจากหน่วยงานรัฐ และดูพื้นที่เสี่ยงภัย</p>
        </a>

        <a href="{{ route('relief-points.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                🏥
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">จุดช่วยเหลือ & อพยพ</h2>
            <p class="text-sm text-gray-500">ค้นหาสถานที่หลบภัย ศูนย์อพยพ และจุดแจกจ่ายสิ่งของบรรเทาทุกข์</p>
        </a>

        <a href="{{ route('preparedness.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                📋
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">เตรียมพร้อมรับมือ</h2>
            <p class="text-sm text-gray-500">เช็คลิสต์กระเป๋าฉุกเฉิน และคำแนะนำการปฏิบัติตัวเมื่อเกิดภัย</p>
        </a>

    </div>

    {{-- Call to Action / Info --}}
    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 flex items-center gap-4">
        <div class="text-4xl">💡</div>
        <div>
            <h3 class="font-bold text-blue-900">รู้หรือไม่?</h3>
            <p class="text-sm text-blue-700">การเตรียมพร้อมล่วงหน้าสามารถลดความสูญเสียเมื่อเกิดภัยพิบัติได้มากกว่า 50% ตรวจสอบจุดอพยพใกล้บ้านคุณตั้งแต่วันนี้</p>
        </div>
    </div>

</div>
@endsection
