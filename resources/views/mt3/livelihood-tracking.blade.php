@extends('layouts.app')
@section('title', 'ติดตามสถานะการฟื้นฟูอาชีพ')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mt3.livelihood') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-emerald-600 hover:bg-emerald-50 shadow-sm transition-colors shrink-0">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-o-clipboard-document-check class="w-7 h-7 text-emerald-500" />
                        ติดตามสถานะการฟื้นฟูอาชีพ
                    </h2>
                    <p class="text-gray-600 text-[15px] font-medium mt-1">อัปเดตสถานะล่าสุดของคำขอฟื้นฟูอาชีพของคุณ</p>
                </div>
            </div>
        </div>

        @php
            $status = $latestLivelihood->status ?? 'รอตรวจสอบ';
            
            $step = 1;
            if ($status === 'รอตรวจสอบ') $step = 1;
            elseif ($status === 'อนุมัติแล้ว') $step = 2;
            elseif ($status === 'ช่วยเหลือแล้ว') $step = 3;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Timeline Column -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-8 border-b pb-4">ไทม์ไลน์สถานะ (คำขอ #LL-{{ str_pad($latestLivelihood->id, 3, '0', STR_PAD_LEFT) }})</h3>
                    
                    <div class="relative border-l-4 border-gray-100 ml-3 space-y-10 py-2">
                        
                        <!-- Step 3 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step >= 3 ? 'bg-green-500' : 'bg-gray-200' }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step >= 3 ? 'text-green-600' : 'text-gray-400' }} mb-1">{{ $step >= 3 ? 'เสร็จสิ้น' : 'ยังไม่ดำเนินการ' }}</div>
                                <h4 class="text-lg {{ $step >= 3 ? 'font-black text-gray-900' : 'font-bold text-gray-700' }}">ได้รับความช่วยเหลือแล้ว</h4>
                                <p class="{{ $step >= 3 ? 'text-gray-600' : 'text-gray-500' }} text-[15px] font-medium mt-2">คุณได้รับการสนับสนุนการฟื้นฟูอาชีพตามที่ร้องขอเรียบร้อยแล้ว</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 2 ? 'bg-green-500' : ($step == 2 ? 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' : 'bg-gray-200') }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 2 ? 'text-green-600' : ($step == 2 ? 'text-teal-600' : 'text-gray-400') }} mb-1">{{ $step > 2 ? 'เสร็จสิ้น' : ($step == 2 ? 'กำลังดำเนินการ...' : 'ยังไม่เริ่มดำเนินการ') }}</div>
                                <h4 class="text-lg {{ $step >= 2 ? 'font-black text-gray-900' : 'font-bold text-gray-700' }}">อนุมัติการช่วยเหลือ</h4>
                                <p class="{{ $step >= 2 ? 'text-gray-600' : 'text-gray-500' }} text-sm mt-2">คำขอของคุณได้รับการตรวจสอบและอนุมัติแล้ว เจ้าหน้าที่กำลังเตรียมการให้ความช่วยเหลือ</p>
                            </div>
                        </div>

                        <!-- Step 1 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 1 ? 'bg-green-500' : 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 1 ? 'text-green-600' : 'text-teal-600' }} mb-1">{{ $step > 1 ? 'เสร็จสิ้น ('.$latestLivelihood->created_at->diffForHumans().')' : 'เริ่มต้นแล้ว' }}</div>
                                <h4 class="text-lg font-black text-gray-900">รอการตรวจสอบ</h4>
                                <p class="text-gray-600 text-sm mt-2">ระบบได้รับคำขอฟื้นฟูอาชีพเรียบร้อยแล้ว กำลังรอเจ้าหน้าที่ตรวจสอบรายละเอียดความเสียหาย</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Data Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-bl-full -mr-16 -mt-16 opacity-50"></div>
                    
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2 relative">
                        <x-heroicon-o-document-text class="w-5 h-5 text-emerald-500" />
                        ข้อมูลคำขอ
                    </h3>

                    <div class="space-y-4 relative">
                        <div>
                            <div class="text-xs text-gray-500 mb-1 font-medium">สถานที่ประกอบการ</div>
                            <div class="font-bold text-gray-900">{{ $latestLivelihood->location }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1 font-medium">รายละเอียดความเสียหาย</div>
                            <div class="font-bold text-gray-900 text-sm leading-relaxed">{{ $latestLivelihood->damage_details }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1 font-medium">มูลค่าความเสียหาย (เบื้องต้น)</div>
                            <div class="font-bold text-gray-900">{{ number_format($latestLivelihood->damage_value, 2) }} บาท</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1 font-medium">ความประสงค์</div>
                            <ul class="list-disc list-inside text-sm font-bold text-gray-900 space-y-1">
                                @foreach($latestLivelihood->needs as $need)
                                <li>{{ $need }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
