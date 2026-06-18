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
    $icon = '😨';

    if ($type == 'physical') {
        $bg = 'bg-[#10b981]'; $hover = 'hover:bg-[#059669]'; $border = 'border-[#10b981]'; $text = 'text-[#10b981]'; $shadow = 'shadow-green-500/40';
        $gradient = 'from-[#10b981] to-[#047857]'; $icon = '🩺';
    } elseif ($type == 'disease_risk') {
        $bg = 'bg-[#14b8a6]'; $hover = 'hover:bg-[#0d9488]'; $border = 'border-[#14b8a6]'; $text = 'text-[#14b8a6]'; $shadow = 'shadow-teal-500/40';
        $gradient = 'from-[#14b8a6] to-[#0f766e]'; $icon = '🦠';
    } elseif ($type == 'injury_severity') {
        $bg = 'bg-[#ef4444]'; $hover = 'hover:bg-[#dc2626]'; $border = 'border-[#ef4444]'; $text = 'text-[#ef4444]'; $shadow = 'shadow-red-500/40';
        $gradient = 'from-[#ef4444] to-[#b91c1c]'; $icon = '🤕';
    } elseif ($type == 'nutrition_status') {
        $bg = 'bg-[#f59e0b]'; $hover = 'hover:bg-[#d97706]'; $border = 'border-[#f59e0b]'; $text = 'text-[#f59e0b]'; $shadow = 'shadow-amber-500/40';
        $gradient = 'from-[#f59e0b] to-[#b45309]'; $icon = '🍲';
    } elseif ($type == 'disaster_stress') {
        $bg = 'bg-[#f97316]'; $hover = 'hover:bg-[#ea580c]'; $border = 'border-[#f97316]'; $text = 'text-[#f97316]'; $shadow = 'shadow-orange-500/40';
        $gradient = 'from-[#f97316] to-[#c2410c]'; $icon = '🌪️';
    } elseif ($type == 'burnout') {
        $bg = 'bg-[#ec4899]'; $hover = 'hover:bg-[#db2777]'; $border = 'border-[#ec4899]'; $text = 'text-[#ec4899]'; $shadow = 'shadow-pink-500/40';
        $gradient = 'from-[#ec4899] to-[#be185d]'; $icon = '🪫';
    } elseif ($type == 'sleep_quality') {
        $bg = 'bg-[#6366f1]'; $hover = 'hover:bg-[#4f46e5]'; $border = 'border-[#6366f1]'; $text = 'text-[#6366f1]'; $shadow = 'shadow-indigo-500/40';
        $gradient = 'from-[#6366f1] to-[#4338ca]'; $icon = '😴';
    } elseif ($type == 'phq9') {
        $icon = '😔';
    } elseif ($type == 'gad7') {
        $icon = '😰';
    }
@endphp

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ agreed: false, showConsent: true }">
    
    {{-- Consent Modal Themed --}}
    <div x-show="showConsent" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-[#1a1f36]/80 backdrop-blur-md" x-transition.opacity x-cloak>
        <div class="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] md:max-h-[85vh]" @click.outside="if(agreed) showConsent = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            {{-- Header (Dynamic Gradient) --}}
            <div class="pt-8 pb-8 px-8 flex flex-col items-center justify-center relative z-10 bg-gradient-to-br {{ $gradient }} text-white overflow-hidden shadow-md">
                <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white opacity-10 rounded-full filter blur-xl"></div>
                <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-black opacity-10 rounded-full filter blur-xl"></div>
                
                <div class="relative flex items-center gap-4 z-10">
                    <div class="bg-white/20 backdrop-blur-md text-white w-14 h-14 rounded-[1rem] flex items-center justify-center text-3xl shadow-inner border border-white/20">
                        {{ $icon }}
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-black tracking-wide drop-shadow-sm">
                            ข้อกำหนดและเงื่อนไข
                        </h2>
                        <p class="text-white/80 text-sm mt-1 font-medium">RescueMind - {{ $title }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="px-8 pt-6 pb-4 overflow-y-auto flex-1 text-gray-600 leading-relaxed text-sm md:text-base relative bg-white custom-scrollbar">
                <div class="absolute top-0 left-0 right-0 h-4 bg-gradient-to-b from-white to-transparent z-10"></div>
                <div class="absolute bottom-0 left-0 right-0 h-4 bg-gradient-to-t from-white to-transparent z-10"></div>
                
                <p class="mb-5 font-bold text-gray-800 text-lg">สุขภาพจิตเบื้องต้นและคัดกรองความเสี่ยงด้วยระบบคอมพิวเตอร์</p>
                <div class="space-y-4 text-gray-600">
                    <p class="p-4 bg-gray-50 rounded-2xl border border-gray-100">1. <strong>"ข้อมูลส่วนบุคคล"</strong> หมายถึง ข้อมูลเกี่ยวกับบุคคลซึ่งทำให้สามารถระบุตัวบุคคลนั้นได้ไม่ว่าทางตรงหรือทางอ้อม เช่น ชื่อ สกุล เพศ อายุ ที่อยู่ เบอร์โทรศัพท์ ข้อมูลสุขภาพ และภาวะจิตใจ</p>
                    <p class="p-4 bg-gray-50 rounded-2xl border border-gray-100">2. ระบบ <strong>RescueMind</strong> รวบรวม จัดเก็บ ใช้ ข้อมูล ซึ่งประกอบด้วยข้อมูลส่วนบุคคล และข้อมูลการประเมินสุขภาพ เพื่อประโยชน์ในการจัดทำฐานข้อมูล พัฒนาระบบการช่วยเหลือผู้ประสบภัย</p>
                    <p class="p-4 bg-gray-50 rounded-2xl border border-gray-100">3. ผู้ใช้บริการมีสิทธิ์ถอนความยินยอมเกี่ยวกับข้อมูลส่วนบุคคลของตนเมื่อใดก็ได้ เว้นแต่การถอนความยินยอมนั้นจะกระทบต่อการให้บริการ</p>
                    <p class="p-4 bg-gray-50 rounded-2xl border border-gray-100">4. การตกลงให้เก็บรวบรวมข้อมูลส่วนบุคคลนี้ มีผลใช้บังคับตาม <strong>พระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)</strong></p>
                </div>
            </div>

            {{-- Footer / Actions --}}
            <div class="p-6 md:p-8 bg-white z-10 relative border-t border-gray-100 shadow-[0_-15px_30px_-15px_rgba(0,0,0,0.05)]">
                
                {{-- Checkbox --}}
                <label class="flex items-center gap-4 cursor-pointer p-5 rounded-[1.5rem] border-2 transition-all duration-300 mb-8" 
                       :class="agreed ? '{{ $border }} bg-gray-50/50 shadow-sm scale-[1.01]' : 'border-gray-200 hover:border-gray-300 bg-gray-50/50'">
                    
                    <div class="relative flex items-center justify-center w-8 h-8 rounded-xl border-2 flex-shrink-0 transition-colors"
                         :class="agreed ? '{{ $bg }} {{ $border }}' : 'bg-white border-gray-300'">
                         <svg x-show="agreed" x-transition class="w-5 h-5 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                         </svg>
                    </div>
                    <input type="checkbox" x-model="agreed" class="hidden">
                    
                    <span class="text-sm md:text-base font-bold" :class="agreed ? 'text-gray-900' : 'text-gray-600'">
                        ข้าพเจ้าได้อ่านและยอมรับเงื่อนไขในการจัดเก็บข้อมูลตามกฎหมาย PDPA
                    </span>
                </label>

                {{-- Buttons --}}
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('mental.index') }}" class="flex items-center justify-center py-4 rounded-[1.2rem] border-2 border-gray-200 text-gray-500 font-extrabold hover:bg-gray-100 hover:text-gray-900 hover:border-gray-300 transition-all text-lg">
                        ย้อนกลับ
                    </a>
                    <button @click="if(agreed) showConsent = false" 
                            :disabled="!agreed" 
                            :class="agreed ? '{{ $bg }} {{ $hover }} {{ $shadow }} text-white hover:-translate-y-1' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                            class="flex items-center justify-center py-4 rounded-[1.2rem] font-extrabold transition-all duration-300 text-lg">
                        รับทราบและเริ่มประเมิน
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Assessment Form --}}
    <div :class="showConsent ? 'blur-md select-none pointer-events-none overflow-hidden h-screen opacity-50 scale-95' : 'blur-none transition-all duration-700 h-auto opacity-100 scale-100 origin-top'">
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden mb-12">
            <div class="bg-gradient-to-br {{ $gradient }} p-8 md:p-12 text-white text-center relative overflow-hidden">
                <div class="relative z-10">
                    <div class="text-5xl md:text-6xl mb-4 drop-shadow-md">{{ $icon }}</div>
                    <h1 class="text-2xl md:text-3xl font-black mb-2 tracking-wide">{{ $title }}</h1>
                    <p class="text-white/90 mt-2 text-sm md:text-base font-medium">เลือกคำตอบที่ตรงกับความรู้สึกหรืออาการของคุณในช่วง 2 สัปดาห์ที่ผ่านมา</p>
                </div>
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full filter blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full filter blur-2xl"></div>
            </div>

            <form action="{{ route('mental.assess.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="mb-8 p-5 bg-gray-50/80 rounded-2xl border border-gray-100">
                    <div class="grid grid-cols-4 gap-2 text-center text-xs md:text-sm font-bold text-gray-600">
                        <div class="py-2.5 bg-green-50 rounded-xl text-green-700 shadow-sm border border-green-100/50">0 = ไม่เลย</div>
                        <div class="py-2.5 bg-yellow-50 rounded-xl text-yellow-700 shadow-sm border border-yellow-100/50">1 = บางวัน</div>
                        <div class="py-2.5 bg-orange-50 rounded-xl text-orange-700 shadow-sm border border-orange-100/50">2 = บ่อยครั้ง</div>
                        <div class="py-2.5 bg-red-50 rounded-xl text-red-700 shadow-sm border border-red-100/50">3 = แทบทุกวัน</div>
                    </div>
                </div>

                <div class="space-y-5">
                    @foreach($questions as $i => $question)
                    <div class="p-5 md:p-6 border border-gray-100 bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] rounded-3xl hover:border-gray-200 hover:shadow-md transition-all duration-300" x-data="{ selected: null }">
                        <p class="text-base font-extrabold text-gray-800 mb-5 flex items-start gap-3">
                            <span class="{{ $bg }} text-white w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-lg shadow-sm">{{ $i + 1 }}</span> 
                            <span class="mt-1.5 leading-relaxed">{{ $question }}</span>
                        </p>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach([0 => '0', 1 => '1', 2 => '2', 3 => '3'] as $val => $label)
                            <label class="cursor-pointer group" :class="selected == {{ $val }} ? 'opacity-100 scale-[1.02]' : 'opacity-70 hover:opacity-100 hover:scale-105 transition-all duration-200'">
                                <input type="radio" name="answers[{{ $i }}]" value="{{ $val }}" required
                                    @change="selected = {{ $val }}" class="sr-only">
                                <div :class="selected == {{ $val }} ? '{{ $val == 0 ? 'bg-green-500 shadow-green-500/30' : ($val == 1 ? 'bg-yellow-500 shadow-yellow-500/30' : ($val == 2 ? 'bg-orange-500 shadow-orange-500/30' : 'bg-red-500 shadow-red-500/30')) }} text-white shadow-lg border-transparent' : 'bg-white text-gray-500 border-2 border-gray-100 group-hover:border-gray-300'"
                                    class="py-3.5 md:py-4 rounded-[1.2rem] text-center font-black text-xl transition-all duration-300">
                                    {{ $label }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error("answers.$i") <p class="text-red-500 text-sm mt-3 font-bold bg-red-50 p-3 rounded-xl border border-red-100 flex items-center gap-2">⚠️ {{ $message }}</p> @enderror
                    </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    <button type="submit" class="w-full py-5 {{ $bg }} {{ $hover }} {{ $shadow }} text-white font-black rounded-[1.5rem] transition-all duration-300 text-xl hover:-translate-y-1 flex items-center justify-center gap-2">
                        ส่งผลการประเมิน <span>→</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
