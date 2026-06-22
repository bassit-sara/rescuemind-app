@extends('layouts.admin')
@section('title', 'AI Donation Matching')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mt3') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-teal-600 hover:bg-teal-50 shadow-sm transition-colors shrink-0">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-o-cpu-chip class="w-7 h-7 text-teal-500" />
                        AI Donation Matching
                    </h2>
                    <p class="text-gray-600 text-[15px] font-medium mt-1">ระบบอัจฉริยะวิเคราะห์เพื่อจับคู่ผู้บริจาคกับพื้นที่ที่กำลังขาดแคลน ช่วยลดปัญหาของล้นคลัง</p>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-teal-50 rounded-full text-teal-700 text-sm font-bold border border-teal-100">
                    <span class="w-2 h-2 rounded-full bg-teal-500 animate-ping"></span>
                    AI กำลังทำงานแบบ Real-time
                </div>
                @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                <button class="px-4 py-2 bg-teal-100 text-teal-700 rounded-xl text-sm font-bold shadow-sm hover:bg-teal-200 flex items-center gap-2 transition-colors">
                    <x-heroicon-o-cog-6-tooth class="w-4 h-4" /> ตั้งค่า AI
                </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- AI Analysis -->
            <div class="lg:col-span-1 space-y-6">
                <!-- AI Status -->
                <div class="bg-gradient-to-b from-teal-600 to-purple-700 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <x-heroicon-s-sparkles class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">AI Analysis</h3>
                                <div class="text-teal-200 text-xs">อัปเดตเมื่อ 1 นาทีที่แล้ว</div>
                            </div>
                        </div>
                        <p class="text-sm text-teal-50 leading-relaxed">
                            ระบบ AI วิเคราะห์จากคำขอความช่วยเหลือ (SOS) และรายงานความต้องการของชุมชน พบว่าขณะนี้ **พื้นที่อำเภอแม่สาย** ขาดแคลนอาหารแห้งอย่างหนัก ในขณะที่ **ศูนย์รับบริจาค A** มีอาหารแห้งจำนวนมากเกินความต้องการ
                        </p>
                    </div>
                </div>

                <!-- Live Feed -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-black text-gray-800 mb-4 flex items-center gap-2">
                        <x-heroicon-o-bolt class="w-5 h-5 text-yellow-500" />
                        การจับคู่ล่าสุด (Live)
                    </h3>
                    <div class="space-y-4">
                        <div class="border-l-2 border-teal-200 pl-4 py-1">
                            <div class="text-xs font-bold text-teal-600 mb-1">แมตช์สำเร็จ (ความแม่นยำ 98%)</div>
                            <div class="text-sm font-medium text-gray-800">ผู้บริจาค: บริษัท ก. ส่งน้ำดื่ม 500 แพ็ค</div>
                            <div class="text-sm text-gray-600 font-medium mt-1">→ จัดส่งไปยัง: ชุมชนบ้านดอย (แจ้งขาดน้ำ 2 วัน)</div>
                        </div>
                        <div class="border-l-2 border-teal-200 pl-4 py-1">
                            <div class="text-xs font-bold text-teal-600 mb-1">แมตช์สำเร็จ (ความแม่นยำ 95%)</div>
                            <div class="text-sm font-medium text-gray-800">ผู้บริจาค: คุณสมหญิง ส่งยารักษาโรค</div>
                            <div class="text-sm text-gray-600 font-medium mt-1">→ จัดส่งไปยัง: ศูนย์อพยพวัดป่า (มีผู้ป่วยน้ำกัดเท้า 50 คน)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard/Map Area -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 h-full flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-black text-gray-800 text-lg">รายการแนะนำพื้นที่บริจาค (AI Recommendations)</h3>
                        <span class="text-xs font-bold bg-gray-100 text-gray-600 px-3 py-1 rounded-lg">เรียงตามความเร่งด่วน</span>
                    </div>

                    <div class="space-y-4 flex-1">
                        <!-- Item 1 -->
                        <div class="p-5 border border-red-100 bg-red-50 rounded-2xl flex items-center justify-between group hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center shrink-0">
                                    <span class="font-black text-xl">1</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 text-lg">ชุมชนริมน้ำ อ.เมือง จ.ยะลา</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-xs font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded-md">วิกฤต (รอความช่วยเหลือ 48 ชม.)</span>
                                        <span class="text-sm text-gray-600 flex items-center gap-1"><x-heroicon-o-archive-box class="w-4 h-4"/> ต้องการ: อาหารแห้ง, น้ำดื่ม</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('mt3.donation') }}" class="px-6 py-2 bg-red-600 text-white text-sm font-bold rounded-xl shadow-sm hover:bg-red-700 transition-colors shrink-0">บริจาคให้พื้นที่นี้</a>
                        </div>

                        <!-- Item 2 -->
                        <div class="p-5 border border-emerald-100 bg-white hover:bg-orange-50 rounded-2xl flex items-center justify-between group transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-emerald-100 text-orange-600 rounded-xl flex items-center justify-center shrink-0">
                                    <span class="font-black text-xl">2</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 text-lg">ศูนย์อพยพโรงเรียนบ้านนา จ.ยะลา</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-xs font-bold bg-emerald-100 text-orange-700 px-2 py-0.5 rounded-md">เร่งด่วนปานกลาง</span>
                                        <span class="text-sm text-gray-600 flex items-center gap-1"><x-heroicon-o-archive-box class="w-4 h-4"/> ต้องการ: เสื้อผ้า, ผ้าห่ม</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('mt3.donation') }}" class="px-6 py-2 bg-teal-50 text-teal-700 border border-teal-100 text-sm font-bold rounded-xl shadow-sm hover:bg-teal-100 transition-colors shrink-0">บริจาคให้พื้นที่นี้</a>
                        </div>

                        <!-- Item 3 -->
                        <div class="p-5 border border-yellow-100 bg-white hover:bg-yellow-50 rounded-2xl flex items-center justify-between group transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center shrink-0">
                                    <span class="font-black text-xl">3</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 text-lg">หมู่บ้านคลองสอง จ.พระนครศรีอยุธยา</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-xs font-bold bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-md">เฝ้าระวัง</span>
                                        <span class="text-sm text-gray-600 flex items-center gap-1"><x-heroicon-o-archive-box class="w-4 h-4"/> ต้องการ: ยารักษาโรค</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('mt3.donation') }}" class="px-6 py-2 bg-teal-50 text-teal-700 border border-teal-100 text-sm font-bold rounded-xl shadow-sm hover:bg-teal-100 transition-colors shrink-0">บริจาคให้พื้นที่นี้</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



