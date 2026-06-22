@extends('layouts.app')

@section('title', $title)
@section('page-title', $title)

@php
    $bg = 'bg-[#8b2ef0]';
    $hover = 'hover:bg-[#7221ca]';
    $border = 'border-[#8b2ef0]';
    $text = 'text-[#8b2ef0]';
    $shadow = 'shadow-purple-500/40';
    $gradient = 'from-[#8b2ef0] to-[#601bb0]';
    $icon = 'heroicon-o-face-frown';

    if ($type == 'physical') {
        $bg = 'bg-[#10b981]'; $hover = 'hover:bg-[#059669]'; $border = 'border-[#10b981]'; $text = 'text-[#10b981]'; $shadow = 'shadow-green-500/40';
        $gradient = 'from-[#10b981] to-[#047857]'; $icon = 'heroicon-o-heart';
    } elseif ($type == 'disease_risk') {
        $bg = 'bg-[#14b8a6]'; $hover = 'hover:bg-[#0d9488]'; $border = 'border-[#14b8a6]'; $text = 'text-[#14b8a6]'; $shadow = 'shadow-teal-500/40';
        $gradient = 'from-[#14b8a6] to-[#0f766e]'; $icon = 'heroicon-o-bug-ant';
    } elseif ($type == 'injury_severity') {
        $bg = 'bg-[#ef4444]'; $hover = 'hover:bg-[#dc2626]'; $border = 'border-[#ef4444]'; $text = 'text-[#ef4444]'; $shadow = 'shadow-red-500/40';
        $gradient = 'from-[#ef4444] to-[#b91c1c]'; $icon = 'heroicon-o-face-frown';
    } elseif ($type == 'nutrition_status') {
        $bg = 'bg-[#f59e0b]'; $hover = 'hover:bg-[#d97706]'; $border = 'border-[#f59e0b]'; $text = 'text-[#f59e0b]'; $shadow = 'shadow-amber-500/40';
        $gradient = 'from-[#f59e0b] to-[#b45309]'; $icon = 'heroicon-o-cube';
    } elseif ($type == 'disaster_stress') {
        $bg = 'bg-[#f97316]'; $hover = 'hover:bg-[#ea580c]'; $border = 'border-[#f97316]'; $text = 'text-[#f97316]'; $shadow = 'shadow-orange-500/40';
        $gradient = 'from-[#f97316] to-[#c2410c]'; $icon = 'heroicon-o-arrow-path';
    } elseif ($type == 'burnout') {
        $bg = 'bg-[#ec4899]'; $hover = 'hover:bg-[#db2777]'; $border = 'border-[#ec4899]'; $text = 'text-[#ec4899]'; $shadow = 'shadow-pink-500/40';
        $gradient = 'from-[#ec4899] to-[#be185d]'; $icon = 'heroicon-o-battery-0';
    } elseif ($type == 'sleep_quality') {
        $bg = 'bg-[#6366f1]'; $hover = 'hover:bg-[#4f46e5]'; $border = 'border-[#6366f1]'; $text = 'text-[#6366f1]'; $shadow = 'shadow-indigo-500/40';
        $gradient = 'from-[#6366f1] to-[#4338ca]'; $icon = 'heroicon-o-moon';
    } elseif ($type == 'phq9') {
        $icon = 'heroicon-o-face-frown';
    } elseif ($type == 'gad7') {
        $icon = 'heroicon-o-face-frown';
    }
@endphp

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="{ agreed: false, showConsent: true }">
    


    {{-- Premium Consent Modal (Matching Reference Layout) --}}
    <div x-show="showConsent" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-gray-900/40 backdrop-blur-sm" x-transition.opacity x-cloak>
        <div class="bg-white w-full max-w-3xl rounded-[1.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] md:max-h-[85vh]" @click.outside="if(agreed) showConsent = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            {{-- Header --}}
            <div class="pt-6 pb-5 px-6 md:px-8 relative z-10 bg-gradient-to-br {{ $gradient }} text-white flex justify-between items-start">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl shrink-0 border border-white/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <p class="text-white/90 text-sm font-medium mb-0.5">หนังสือให้ความยินยอม</p>
                        <h2 class="text-xl md:text-2xl font-bold tracking-tight mb-1">
                            การเก็บรวบรวมข้อมูลส่วนบุคคล
                        </h2>
                        <p class="text-white/80 text-xs md:text-sm">
                            ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)
                        </p>
                    </div>
                </div>
                <button type="button" @click="if(agreed) showConsent = false; else window.location.href='{{ route('mental.index') }}'" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            {{-- Content --}}
            <div class="px-6 md:px-8 pt-6 pb-4 overflow-y-auto flex-1 text-gray-700 leading-relaxed text-sm relative bg-white custom-scrollbar space-y-4">
                
                {{-- Card 1 --}}
                <div class="p-5 bg-white rounded-xl border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-3 text-base">
                        <span class="w-6 h-6 rounded-full {{ preg_replace('/text-[a-z]+-\d+/', 'bg-white/20', $bg) }} border border-[var(--tw-gradient-from)] text-[var(--tw-gradient-from)] flex items-center justify-center text-xs font-bold" style="background-color: #f3f4f6; color: inherit;">1</span> 
                        <span class="{{ $text }}">วัตถุประสงค์การเก็บรวบรวมข้อมูล</span>
                    </h4>
                    <p class="text-gray-600 ml-9">
                        เพื่อประเมินสุขภาพจิตและสุขภาพกายเบื้องต้น คัดกรองความเสี่ยงต่อปัญหาสุขภาพ และติดตามดูแลช่วยเหลือผู้ที่เสี่ยงมีปัญหาสุขภาพด้วยระบบคอมพิวเตอร์ รวมถึงพัฒนาฐานข้อมูลด้านการดำเนินงานสาธารณสุข
                    </p>
                </div>
                
                {{-- Card 2 --}}
                <div class="p-5 bg-white rounded-xl border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                    <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-3 text-base">
                        <span class="w-6 h-6 rounded-full {{ preg_replace('/text-[a-z]+-\d+/', 'bg-white/20', $bg) }} border border-[var(--tw-gradient-from)] text-[var(--tw-gradient-from)] flex items-center justify-center text-xs font-bold" style="background-color: #f3f4f6; color: inherit;">2</span> 
                        <span class="{{ $text }}">ข้อมูลส่วนบุคคลที่เก็บรวบรวม</span>
                    </h4>
                    <ul class="ml-9 space-y-2 text-gray-600">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 {{ $text }} mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span><strong class="font-semibold text-gray-700">ข้อมูลระบุตัวตน</strong> — ชื่อ นามสกุล เพศ อายุ ที่อยู่ เบอร์โทรศัพท์</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 {{ $text }} mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span><strong class="font-semibold text-gray-700">ข้อมูลสุขภาพ</strong> — ภาวะความเครียด ภาวะซึมเศร้า ความเสี่ยงด้านสุขภาพ</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 {{ $text }} mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span><strong class="font-semibold text-gray-700">ข้อมูลปัจจัยเสี่ยง</strong> — เช่น การเป็นผู้ประสบภัยน้ำท่วม หรือสถานการณ์วิกฤตอื่น ๆ</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 {{ $text }} mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span><strong class="font-semibold text-gray-700">ข้อมูลภาวะสุขภาพจากแบบประเมินมาตรฐาน</strong> (ST-5, PHQ-9 ฯลฯ)</span>
                        </li>
                    </ul>
                </div>

                {{-- Card 3 --}}
                <div class="p-5 bg-white rounded-xl border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-3 text-base">
                        <span class="w-6 h-6 rounded-full {{ preg_replace('/text-[a-z]+-\d+/', 'bg-white/20', $bg) }} border border-[var(--tw-gradient-from)] text-[var(--tw-gradient-from)] flex items-center justify-center text-xs font-bold" style="background-color: #f3f4f6; color: inherit;">3</span> 
                        <span class="{{ $text }}">การเก็บรักษาและการเปิดเผยข้อมูล</span>
                    </h4>
                    <p class="text-gray-600 ml-9">
                        ข้อมูลจะถูกเก็บรักษาอย่างปลอดภัยตามมาตรฐาน และจะไม่ถูกเปิดเผยแก่บุคคลภายนอก เว้นแต่ได้รับความยินยอม หรือมีเหตุจำเป็นตามที่กฎหมายกำหนด โดยข้อมูลจะถูกเก็บไว้ตลอดระยะเวลาโครงการหรือนานกว่านั้นตามที่จำเป็น
                    </p>
                </div>

                {{-- Card 4: PDPA Rights --}}
                <div class="p-5 rounded-xl border border-emerald-100 bg-emerald-50/30 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                    <h4 class="font-bold text-emerald-800 mb-3 flex items-center gap-3 text-base">
                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">4</span> 
                        <span>สิทธิของเจ้าของข้อมูลส่วนบุคคล (ตาม PDPA มาตรา 30-43)</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 ml-9 mb-3">
                        <div class="flex items-center gap-2 text-gray-600">
                            <span class="text-sm"><x-heroicon-o-eye class="w-5 h-5 inline-block mr-1 -mt-1" />️</span> <span>สิทธิในการเข้าถึงข้อมูล (ม.30)</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <span class="text-sm"><x-heroicon-o-pencil class="w-5 h-5 inline-block mr-1 -mt-1" />️</span> <span>สิทธิในการแก้ไขข้อมูล (ม.35)</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <span class="text-sm"><x-heroicon-o-trash class="w-5 h-5 inline-block mr-1 -mt-1" />️</span> <span>สิทธิในการลบข้อมูล (ม.33)</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <span class="text-sm"><x-heroicon-o-no-symbol class="w-5 h-5 inline-block mr-1 -mt-1" /></span> <span>สิทธิในการคัดค้านการประมวลผล (ม.38)</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <span class="text-sm"><x-heroicon-o-archive-box class="w-5 h-5 inline-block mr-1 -mt-1" /></span> <span>สิทธิในการโอนย้ายข้อมูล (ม.36)</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <span class="text-sm"><x-heroicon-o-pause class="w-5 h-5 inline-block mr-1 -mt-1" />️</span> <span>สิทธิในการระงับการใช้ข้อมูล (ม.34)</span>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm ml-9 mt-3 border-t border-emerald-100/50 pt-2">
                        ท่านสามารถใช้สิทธิ์ดังกล่าวได้โดยติดต่อผู้ควบคุมข้อมูลตามช่องทางที่ระบุในนโยบายคุ้มครองข้อมูลส่วนบุคคล
                    </p>
                </div>

                {{-- Warning Card: Withdraw Consent --}}
                <div class="p-5 rounded-xl border border-amber-200 bg-amber-50/50 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                    <h4 class="font-bold text-amber-800 mb-2 flex items-center gap-2 text-base">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>การถอนความยินยอม</span>
                    </h4>
                    <p class="text-gray-600 text-sm leading-relaxed ml-7">
                        ท่านมีสิทธิ์ถอนความยินยอมได้ทุกเมื่อ โดยการถอนความยินยอมจะไม่กระทบต่อการประมวลผลข้อมูลที่ได้กระทำไปแล้วก่อนหน้าโดยชอบด้วยกฎหมาย ทั้งนี้การถอนความยินยอมอาจส่งผลให้ท่านไม่สามารถรับบริการบางส่วนหรือทั้งหมดได้
                    </p>
                </div>

            </div>

            {{-- Footer / Actions --}}
            <div class="p-6 md:p-8 bg-white z-10 relative border-t border-gray-100">
                
                {{-- Checkbox --}}
                <label class="flex items-start gap-3 cursor-pointer mb-6 group">
                    <div class="relative flex items-center justify-center w-5 h-5 rounded border border-gray-400 mt-0.5 shrink-0 transition-colors"
                         :class="agreed ? '{{ $bg }} border-transparent' : 'bg-white group-hover:border-gray-500'">
                         <svg x-show="agreed" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" style="display: none;">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                         </svg>
                    </div>
                    <input type="checkbox" x-model="agreed" class="hidden">
                    
                    <span class="text-sm text-gray-700 leading-relaxed">
                        ข้าพเจ้าได้อ่านและเข้าใจเนื้อหาข้างต้นครบถ้วนแล้ว และ <strong class="text-gray-900 font-bold">ยินยอมให้เก็บรวบรวม ใช้ และเปิดเผยข้อมูลส่วนบุคคล</strong> ตามวัตถุประสงค์ที่ระบุไว้
                    </span>
                </label>

                {{-- Buttons --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                    <a href="{{ route('mental.index') }}" class="flex items-center justify-center py-3 rounded-lg border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition-colors text-sm md:text-base">
                        ไม่ยินยอม / ย้อนกลับ
                    </a>
                    <button @click="if(agreed) showConsent = false" 
                            :disabled="!agreed" 
                            :class="agreed ? '{{ $bg }} {{ $hover }} text-white shadow-sm' : 'bg-[#e2e8f0] text-gray-400 cursor-not-allowed'"
                            class="flex items-center justify-center py-3 rounded-lg font-semibold transition-all text-sm md:text-base">
                        <span x-text="agreed ? 'ยินยอมและทำแบบประเมิน' : 'กรุณาทำเครื่องหมายยืนยันก่อน'"></span>
                    </button>
                </div>
                
                <div class="text-center flex items-center justify-center gap-1.5 mt-4">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <a href="{{ route('privacy.policy', ['return_to' => request()->url()]) }}" class="text-xs font-medium text-gray-500 hover:text-gray-700 hover:underline transition-colors">
                        นโยบายคุ้มครองข้อมูลส่วนบุคคล (Privacy Policy)
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Assessment Form --}}
    <div :class="showConsent ? 'blur-md select-none pointer-events-none overflow-hidden h-[90vh] opacity-40 scale-[0.98]' : 'blur-none transition-all duration-700 h-auto opacity-100 scale-100 origin-top'">
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden mb-12">
            <div class="bg-gradient-to-br {{ $gradient }} p-8 md:p-12 text-white text-center relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex justify-center mb-4 drop-shadow-md">
                        <x-dynamic-component :component="$icon" class="w-16 h-16 md:w-20 md:h-20 mx-auto" />
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black mb-2 tracking-wide">{{ $title }}</h1>
                    <p class="text-white/90 mt-2 text-sm md:text-base font-medium">เลือกคำตอบที่ตรงกับความรู้สึกหรืออาการของคุณในช่วง 2 สัปดาห์ที่ผ่านมา</p>
                </div>
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full filter blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full filter blur-2xl"></div>
            </div>

            <form action="{{ route('mental.assess.store') }}" method="POST" class="p-6" x-data="{ currentStep: 0, totalSteps: {{ count($questions) }}, answers: {} }">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                {{-- Top Navigation (Back Buttons) --}}
                <div class="mb-6 flex justify-start">
                    <a href="{{ route('mental.index') }}" x-cloak x-show="currentStep === 0" class="flex items-center gap-2 text-gray-500 hover:text-gray-800 font-bold transition-colors bg-gray-50/80 hover:bg-gray-100 px-4 py-2 rounded-full border border-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        ย้อนกลับ
                    </a>
                    
                    <button type="button" x-cloak x-show="currentStep > 0" @click="currentStep--" class="flex items-center gap-2 text-gray-500 hover:text-gray-800 font-bold transition-colors bg-gray-50/80 hover:bg-gray-100 px-4 py-2 rounded-full border border-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        ย้อนกลับ
                    </button>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">ความคืบหน้า</span>
                        <span class="text-xs font-bold {{ $text }}" x-text="Math.round((currentStep / totalSteps) * 100) + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full {{ $bg }} transition-all duration-500 ease-out" :style="'width: ' + ((currentStep / totalSteps) * 100) + '%'"></div>
                    </div>
                </div>



                <div class="relative">
                    @foreach($questions as $i => $question)
                    <div x-cloak x-show="currentStep === {{ $i }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" class="p-5 md:p-8 border border-gray-100 bg-white shadow-[0_10px_40px_-15px_rgba(0,0,0,0.1)] rounded-[2rem]">
                        <p class="text-lg md:text-xl font-extrabold text-gray-800 mb-8 flex items-start gap-4">
                            <span class="{{ $bg }} text-white w-10 h-10 md:w-12 md:h-12 rounded-[1rem] flex items-center justify-center flex-shrink-0 text-xl md:text-2xl shadow-md">{{ $i + 1 }}</span> 
                            <span class="mt-1.5 leading-relaxed">{{ $question }}</span>
                        </p>
                        <div class="grid grid-cols-1 gap-3 md:gap-4">
                            @foreach([0 => 'ไม่เลย', 1 => 'เล็กน้อย', 2 => 'ปานกลาง', 3 => 'มาก'] as $val => $label)
                            <label class="cursor-pointer group block relative" :class="answers[{{ $i }}] == {{ $val }} ? 'scale-[1.02] z-10' : 'hover:scale-[1.01] transition-transform duration-300'">
                                <input type="radio" name="answers[{{ $i }}]" value="{{ $val }}" required
                                    @change="answers[{{ $i }}] = {{ $val }}" class="sr-only">
                                <div :class="answers[{{ $i }}] == {{ $val }} ? '{{ $bg }} {{ $shadow }} text-white border-transparent' : 'bg-gray-50/50 text-gray-600 border-2 border-gray-200 group-hover:border-gray-300 group-hover:bg-gray-100/50'"
                                    class="py-4 px-6 rounded-[1.25rem] text-left font-bold text-lg md:text-xl flex items-center justify-between transition-all duration-300">
                                    <span>{{ $label }}</span>
                                    
                                    {{-- Check Icon (Selected) --}}
                                    <div x-show="answers[{{ $i }}] == {{ $val }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100" class="flex items-center justify-center bg-white/20 rounded-full p-1 shrink-0" style="display: none;">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    
                                    {{-- Empty Circle (Unselected) --}}
                                    <div x-show="answers[{{ $i }}] != {{ $val }}" class="w-6 h-6 rounded-full border-2 border-gray-300 group-hover:border-gray-400 transition-colors shrink-0"></div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error("answers.$i") <p class="text-red-500 text-sm mt-4 font-bold bg-red-50 p-3 rounded-xl border border-red-100 flex items-center gap-2"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /> {{ $message }}</p> @enderror
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 flex items-center justify-between px-2">
                    {{-- Back Button Area --}}
                    <div class="w-28 flex justify-start">
                        <button type="button" x-cloak x-show="currentStep > 0" @click="currentStep--" class="flex items-center gap-1.5 text-gray-400 hover:text-gray-700 font-bold transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            ย้อนกลับ
                        </button>
                    </div>

                    {{-- Counter --}}
                    <div class="text-sm font-bold text-gray-400 bg-gray-50 px-4 py-2 rounded-full border border-gray-100 shrink-0 text-center">
                        ข้อ <span x-text="currentStep + 1" class="{{ $text }} text-base"></span> / <span x-text="totalSteps"></span>
                    </div>

                    {{-- Next/Submit Area --}}
                    <div class="w-28 flex justify-end">
                        <button type="button" x-show="currentStep < totalSteps - 1" 
                                @click="if(answers[currentStep] !== undefined) currentStep++" 
                                :disabled="answers[currentStep] === undefined"
                                :class="answers[currentStep] !== undefined ? 'text-gray-500 hover:{{ $text }} cursor-pointer' : 'text-gray-300 cursor-not-allowed'"
                                class="flex items-center gap-1.5 font-bold transition-colors">
                            ถัดไป
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        
                        <button type="submit" x-show="currentStep === totalSteps - 1" 
                                :disabled="answers[currentStep] === undefined"
                                :class="answers[currentStep] !== undefined ? '{{ $bg }} {{ $hover }} {{ $shadow }} text-white hover:-translate-y-1' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                class="px-5 py-2 rounded-full font-black transition-all duration-300 flex items-center gap-1.5" style="display: none;">
                            ส่งผล <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>


    </div>
</div>
@endsection
