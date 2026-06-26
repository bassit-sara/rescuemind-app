@extends('layouts.app')
@section('title', 'ติดตามการประเมินความต้องการชุมชน')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('mt3') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-teal-600 hover:bg-teal-50 shadow-sm transition-colors">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
            </a>
            <div>
                <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-check class="w-7 h-7 text-teal-500" />
                    ติดตามการประเมินความต้องการชุมชน
                </h2>
                <p class="text-gray-600 text-[15px] font-medium mt-1">เช็คสถานะคำขอทรัพยากร และติดตามความคืบหน้าการจัดสรรและจัดส่งสิ่งของ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Timeline Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-8">
                    <h3 class="text-lg font-black text-gray-800 mb-6 flex items-center gap-2">
                        <x-heroicon-s-clock class="w-6 h-6 text-teal-500" />
                        สถานะการดำเนินการล่าสุด
                    </h3>
                    
                    @php
                        $currentStatus = optional($latestNeed)->progress ?? 'pending';
                        $step = 1;
                        if($currentStatus === 'verifying') $step = 2;
                        if($currentStatus === 'delivering') $step = 3;
                        if($currentStatus === 'completed') $step = 4;
                    @endphp

                    <div class="relative border-l-2 border-teal-200 ml-4 space-y-8">
                        <!-- Step 4 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 4 ? 'bg-green-500' : ($step == 4 ? 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' : 'bg-gray-200') }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 4 ? 'text-green-600' : ($step == 4 ? 'text-teal-600' : 'text-gray-400') }} mb-1">{{ $step > 4 ? 'เสร็จสิ้น' : ($step == 4 ? 'ดำเนินการแล้ว' : 'ยังไม่เริ่มดำเนินการ') }}</div>
                                <h4 class="text-lg {{ $step >= 4 ? 'font-black text-gray-900' : 'font-bold text-gray-700' }}">ได้รับความช่วยเหลือแล้ว</h4>
                                <p class="{{ $step >= 4 ? 'text-gray-600' : 'text-gray-500' }} text-[15px] font-medium mt-2">ชุมชนได้รับสิ่งของบรรเทาทุกข์ครบถ้วนแล้ว</p>
                            </div>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 3 ? 'bg-green-500' : ($step == 3 ? 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' : 'bg-gray-200') }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 3 ? 'text-green-600' : ($step == 3 ? 'text-teal-600' : 'text-gray-400') }} mb-1">{{ $step > 3 ? 'เสร็จสิ้น' : ($step == 3 ? 'กำลังดำเนินการ...' : 'ยังไม่เริ่มดำเนินการ') }}</div>
                                <h4 class="text-lg {{ $step >= 3 ? 'font-black text-gray-900' : 'font-bold text-gray-700' }}">กำลังจัดส่ง</h4>
                                <p class="{{ $step >= 3 ? 'text-gray-600' : 'text-gray-500' }} text-sm mt-2">ทีมงานกำลังจัดส่งสิ่งของและทรัพยากรไปยังพื้นที่ชุมชน</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 2 ? 'bg-green-500' : ($step == 2 ? 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' : 'bg-gray-200') }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 2 ? 'text-green-600' : ($step == 2 ? 'text-teal-600' : 'text-gray-400') }} mb-1">{{ $step > 2 ? 'เสร็จสิ้น' : ($step == 2 ? 'กำลังตรวจสอบ...' : 'ยังไม่เริ่มดำเนินการ') }}</div>
                                <h4 class="text-lg {{ $step >= 2 ? 'font-black text-gray-900' : 'font-bold text-gray-700' }}">ตรวจสอบและจัดสรรทรัพยากร</h4>
                                <p class="{{ $step >= 2 ? 'text-gray-600' : 'text-gray-500' }} text-sm mt-2">เจ้าหน้าที่กำลังตรวจสอบข้อมูลความถูกต้อง และจัดสรรสิ่งของตามความจำเป็น</p>
                            </div>
                        </div>

                        <!-- Step 1 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 1 ? 'bg-green-500' : 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 1 ? 'text-green-600' : 'text-teal-600' }} mb-1">{{ $step > 1 ? 'เสร็จสิ้น ('.($latestNeed ? $latestNeed->created_at->diffForHumans() : '').')' : 'เริ่มต้นแล้ว' }}</div>
                                <h4 class="text-lg font-black text-gray-900">รับข้อมูลประเมิน</h4>
                                <p class="text-gray-600 text-sm mt-2">ระบบได้รับคำขอ หมายเลข #REQ-CM-{{ str_pad(optional($latestNeed)->id ?? 001, 3, '0', STR_PAD_LEFT) }} เรียบร้อยแล้ว</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Details Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-gradient-to-br from-teal-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white relative overflow-hidden">
                    <x-heroicon-o-megaphone class="absolute -right-6 -bottom-6 w-32 h-32 text-white opacity-10" />
                    <div class="relative z-10">
                        <div class="text-teal-100 text-sm font-medium mb-1">หมายเลขคำขอ</div>
                        <div class="flex justify-between items-start mb-6">
                            <div class="text-2xl font-black tracking-wider">#REQ-CM-{{ str_pad(optional($latestNeed)->id ?? 001, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="text-teal-200 text-xs uppercase tracking-wider mb-0.5">ชุมชน / หมู่บ้าน</div>
                                <div class="font-bold">
                                    {{ optional($latestNeed)->community_name ?? 'ไม่ได้ระบุชื่อชุมชน' }}
                                    (ประชากร {{ optional($latestNeed)->population ?? '-' }} คน)
                                </div>
                            </div>
                            <div>
                                <div class="text-teal-200 text-xs uppercase tracking-wider mb-0.5">รหัสไปรษณีย์</div>
                                <div class="font-medium text-sm leading-snug">{{ optional($latestNeed)->zip_code ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Needs List -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-black text-gray-800 mb-4 border-b pb-2">สิ่งที่ต้องการรับความช่วยเหลือเร่งด่วน</h3>
                    <ul class="space-y-3">
                        @if($latestNeed)
                            @if($latestNeed->food_sets > 0)
                            <li class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                                    <x-heroicon-s-shopping-bag class="w-4 h-4" />
                                </div>
                                <div>
                                    <span class="text-gray-900 text-[15px] font-bold block">อาหารและน้ำดื่ม</span>
                                    <span class="text-gray-500 text-xs font-medium">{{ $latestNeed->food_sets }} ชุด</span>
                                </div>
                            </li>
                            @endif
                            @if($latestNeed->medicine_sets > 0)
                            <li class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                                    <x-heroicon-s-beaker class="w-4 h-4" />
                                </div>
                                <div>
                                    <span class="text-gray-900 text-[15px] font-bold block">ยารักษาโรค</span>
                                    <span class="text-gray-500 text-xs font-medium">{{ $latestNeed->medicine_sets }} ชุด</span>
                                </div>
                            </li>
                            @endif
                            @if($latestNeed->cleaning_sets > 0)
                            <li class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <x-heroicon-s-sparkles class="w-4 h-4" />
                                </div>
                                <div>
                                    <span class="text-gray-900 text-[15px] font-bold block">อุปกรณ์ทำความสะอาด</span>
                                    <span class="text-gray-500 text-xs font-medium">{{ $latestNeed->cleaning_sets }} ชุด</span>
                                </div>
                            </li>
                            @endif
                            @if($latestNeed->clothing_sets > 0)
                            <li class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                    <x-heroicon-s-user-group class="w-4 h-4" />
                                </div>
                                <div>
                                    <span class="text-gray-900 text-[15px] font-bold block">เสื้อผ้า/เครื่องนุ่งห่ม</span>
                                    <span class="text-gray-500 text-xs font-medium">{{ $latestNeed->clothing_sets }} ชุด</span>
                                </div>
                            </li>
                            @endif
                            @if($latestNeed->food_sets == 0 && $latestNeed->medicine_sets == 0 && $latestNeed->cleaning_sets == 0 && $latestNeed->clothing_sets == 0)
                            <li class="text-gray-600 text-[15px] font-medium text-center py-4">ไม่ได้ระบุสิ่งที่ต้องการเฉพาะเจาะจง</li>
                            @endif
                        @else
                            <li class="text-gray-600 text-[15px] font-medium text-center py-4">ไม่พบข้อมูลคำขอ</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
