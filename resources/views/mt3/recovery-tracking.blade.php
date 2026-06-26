@extends('layouts.app')
@section('title', 'ติดตามการฟื้นฟู')

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
                    ติดตามการฟื้นฟู (Household Recovery Tracking)
                </h2>
                <p class="text-gray-600 text-[15px] font-medium mt-1">เช็คสถานะคำขอความช่วยเหลือ และติดตามความคืบหน้าการทำงานของทีมอาสาสมัครฟื้นฟูบ้านคุณ</p>
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
                        $currentStatus = optional($latestRecovery)->status ?? 'pending';
                        $step = 1;
                        if($currentStatus === 'matching') $step = 2;
                        if($currentStatus === 'in_progress') $step = 3;
                        if($currentStatus === 'completed') $step = 4;
                    @endphp

                    <div class="relative border-l-2 {{ $step >= 4 ? 'border-teal-200' : 'border-teal-200' }} ml-4 space-y-8">
                        <!-- Step 4 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 4 ? 'bg-green-500' : ($step == 4 ? 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' : 'bg-gray-200') }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 4 ? 'text-green-600' : ($step == 4 ? 'text-teal-600' : 'text-gray-400') }} mb-1">{{ $step > 4 ? 'เสร็จสิ้น' : ($step == 4 ? 'ดำเนินการแล้ว' : 'ยังไม่เริ่มดำเนินการ') }}</div>
                                <h4 class="text-lg {{ $step >= 4 ? 'font-black text-gray-900' : 'font-bold text-gray-700' }}">การฟื้นฟูเสร็จสมบูรณ์</h4>
                                <p class="{{ $step >= 4 ? 'text-gray-600' : 'text-gray-500' }} text-[15px] font-medium mt-2">เจ้าของบ้านยืนยันการรับความช่วยเหลือเรียบร้อย</p>
                            </div>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 3 ? 'bg-green-500' : ($step == 3 ? 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' : 'bg-gray-200') }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 3 ? 'text-green-600' : ($step == 3 ? 'text-teal-600' : 'text-gray-400') }} mb-1">{{ $step > 3 ? 'เสร็จสิ้น' : ($step == 3 ? 'กำลังดำเนินการลงพื้นที่...' : 'ยังไม่เริ่มดำเนินการ') }}</div>
                                <h4 class="text-lg {{ $step >= 3 ? 'font-black text-gray-900' : 'font-bold text-gray-700' }}">ทีมอาสาสมัครกำลังลงพื้นที่</h4>
                                <p class="{{ $step >= 3 ? 'text-gray-600' : 'text-gray-500' }} text-sm mt-2">รอการลงพื้นที่จากทีมอาสาสมัคร</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 2 ? 'bg-green-500' : ($step == 2 ? 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' : 'bg-gray-200') }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 2 ? 'text-green-600' : ($step == 2 ? 'text-teal-600' : 'text-gray-400') }} mb-1">{{ $step > 2 ? 'เสร็จสิ้น' : ($step == 2 ? 'กำลังดำเนินการจับคู่...' : 'ยังไม่เริ่มดำเนินการ') }}</div>
                                <h4 class="text-lg {{ $step >= 2 ? 'font-black text-gray-900' : 'font-bold text-gray-700' }}">จับคู่ทีมอาสาสมัคร</h4>
                                <p class="{{ $step >= 2 ? 'text-gray-600' : 'text-gray-500' }} text-sm mt-2">ระบบกำลังค้นหาและจับคู่ทีมอาสาสมัครที่เหมาะสมกับปัญหาของคุณ</p>
                            </div>
                        </div>

                        <!-- Step 1 -->
                        <div class="flex items-start">
                            <div class="shrink-0 w-5 h-5 {{ $step > 1 ? 'bg-green-500' : 'bg-teal-500 shadow-[0_0_0_3px_rgba(20,184,166,0.2)] animate-pulse' }} border-4 border-white rounded-full" style="margin-left: -11px; margin-top: 4px;"></div>
                            <div class="ml-6">
                                <div class="text-sm font-bold {{ $step > 1 ? 'text-green-600' : 'text-teal-600' }} mb-1">{{ $step > 1 ? 'เสร็จสิ้น ('.$latestRecovery->created_at->diffForHumans().')' : 'เริ่มต้นแล้ว' }}</div>
                                <h4 class="text-lg font-black text-gray-900">รับคำขอความช่วยเหลือ</h4>
                                <p class="text-gray-600 text-sm mt-2">ระบบได้รับคำขอ หมายเลข #REQ-{{ str_pad(optional($latestRecovery)->id ?? 8472, 4, '0', STR_PAD_LEFT) }} เรียบร้อยแล้ว</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Details Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-gradient-to-br from-teal-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white relative overflow-hidden">
                    <x-heroicon-o-clipboard-document-list class="absolute -right-6 -bottom-6 w-32 h-32 text-white opacity-10" />
                    <div class="relative z-10">
                        <div class="text-teal-100 text-sm font-medium mb-1">หมายเลขคำขอ</div>
                        <div class="flex justify-between items-start mb-6">
                            <div class="text-2xl font-black tracking-wider">#REQ-{{ str_pad(optional($latestRecovery)->id ?? 8472, 4, '0', STR_PAD_LEFT) }}</div>
                            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                            <div class="flex gap-2">
                                <button class="p-2 bg-white/20 hover:bg-white/30 rounded-lg transition-colors cursor-pointer" title="แก้ไขข้อมูล">
                                    <x-heroicon-o-pencil-square class="w-5 h-5 text-white" />
                                </button>
                                <button class="p-2 bg-red-500/80 hover:bg-red-500 rounded-lg transition-colors cursor-pointer" title="ลบข้อมูล">
                                    <x-heroicon-o-trash class="w-5 h-5 text-white" />
                                </button>
                            </div>
                            @endif
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="text-teal-200 text-xs uppercase tracking-wider mb-0.5">ประเภท</div>
                                <div class="font-bold">
                                @if($latestRecovery)
                                    @php
                                        $types = [];
                                        if($latestRecovery->need_cleaning) $types[] = 'ทำความสะอาด/ล้างโคลน';
                                        if($latestRecovery->need_electric) $types[] = 'ระบบไฟฟ้า';
                                        if($latestRecovery->need_plumbing) $types[] = 'ระบบประปา';
                                        if($latestRecovery->need_structure) $types[] = 'ซ่อมแซมโครงสร้าง';
                                    @endphp
                                    {{ empty($types) ? 'ขอบริการฟื้นฟู' : implode(', ', $types) }}
                                @else
                                    ทำความสะอาด / ล้างโคลน
                                @endif
                                </div>
                            </div>
                            <div>
                                <div class="text-teal-200 text-xs uppercase tracking-wider mb-0.5">สถานที่</div>
                                <div class="font-medium text-sm leading-snug">{{ optional($latestRecovery)->address ?? '123/45 หมู่บ้านสุขใจ ต.ในเมือง อ.เมือง จ.เชียงราย 57000' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Needs List -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-black text-gray-800 mb-4 border-b pb-2">สิ่งที่ต้องการรับความช่วยเหลือ</h3>
                    <ul class="space-y-3">
                        @if($latestRecovery)
                            @if($latestRecovery->need_cleaning)
                            <li class="flex items-start gap-2">
                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 shrink-0" />
                                <span class="text-gray-700 text-sm font-medium">ทำความสะอาด / ล้างโคลน</span>
                            </li>
                            @endif
                            @if($latestRecovery->need_electric)
                            <li class="flex items-start gap-2">
                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 shrink-0" />
                                <span class="text-gray-700 text-sm font-medium">ซ่อมแซมระบบไฟฟ้าเบื้องต้น</span>
                            </li>
                            @endif
                            @if($latestRecovery->need_plumbing)
                            <li class="flex items-start gap-2">
                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 shrink-0" />
                                <span class="text-gray-700 text-sm font-medium">ซ่อมแซมระบบประปา</span>
                            </li>
                            @endif
                            @if($latestRecovery->need_structure)
                            <li class="flex items-start gap-2">
                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 shrink-0" />
                                <span class="text-gray-700 text-sm font-medium">ซ่อมแซมโครงสร้างเบื้องต้น</span>
                            </li>
                            @endif
                            @if($latestRecovery->additional_details)
                            <li class="flex items-start gap-2 mt-4 pt-3 border-t border-gray-100">
                                <x-heroicon-o-information-circle class="w-5 h-5 text-teal-500 shrink-0" />
                                <span class="text-gray-600 text-xs italic">เพิ่มเติม: {{ $latestRecovery->additional_details }}</span>
                            </li>
                            @endif
                            @if(!$latestRecovery->need_cleaning && !$latestRecovery->need_electric && !$latestRecovery->need_plumbing && !$latestRecovery->need_structure)
                            <li class="text-gray-600 text-[15px] font-medium text-center py-4">ไม่ได้ระบุสิ่งที่ต้องการเฉพาะเจาะจง</li>
                            @endif
                        @else
                            <li class="flex items-start gap-2">
                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 shrink-0" />
                                <span class="text-gray-700 text-sm font-medium">ทำความสะอาดพื้นและผนัง</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 shrink-0" />
                                <span class="text-gray-700 text-sm font-medium">ขนย้ายเฟอร์นิเจอร์ที่เสียหายทิ้ง</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <x-heroicon-s-clock class="w-5 h-5 text-orange-400 shrink-0" />
                                <span class="text-gray-700 text-sm font-medium">รอการตรวจสอบระบบไฟ (รอช่างไฟ)</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



