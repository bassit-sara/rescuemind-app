@extends('layouts.app')
@section('title', 'ศูนย์สุขภาพจิตและกาย')
@section('page-title', '🧠 ศูนย์ฟื้นฟูสุขภาพกายและใจ')
@section('content')

<div x-data="{ tab: 'mental' }" class="space-y-8">
    
    {{-- Hero Section --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 via-purple-950 to-indigo-950 text-white shadow-xl border border-purple-900/30">
        {{-- Glowing background spheres --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-purple-600 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-indigo-500 rounded-full blur-[100px]"></div>
        </div>
        
        <div class="relative z-10 px-8 py-10 md:p-12 lg:p-16 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-xs font-semibold mb-6">
                ✨ Wellness & Resilience Center
            </div>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight mb-4 tracking-tight">
                เริ่มต้นดูแลสุขภาพ<br>
                <span class="bg-gradient-to-r from-purple-300 via-pink-300 to-indigo-200 bg-clip-text text-transparent">กายและใจ</span> ของคุณตั้งแต่วันนี้
            </h1>
            <p class="text-gray-300 text-sm md:text-base leading-relaxed max-w-2xl">
                ทำแบบประเมินสุขภาพเพื่อสำรวจภาวะความเครียด ซึมเศร้า หรือความเหนื่อยล้าทางร่างกายหลังเกิดภัยพิบัติ พร้อมรับคำแนะนำเบื้องต้นและการดูแลที่เหมาะสม ออกแบบเป็นพิเศษเพื่อช่วยฟื้นฟูสุขภาพจิตใจและสุขภาพกายของคุณ
            </p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-gray-100/80 dark:bg-gray-800/50 p-1.5 rounded-2xl flex gap-2 w-full max-w-2xl mx-auto shadow-inner">
        <button @click="tab = 'mental'" 
                :class="tab === 'mental' ? 'bg-white dark:bg-gray-900 text-purple-700 dark:text-purple-400 shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'" 
                class="flex-1 flex items-center justify-center gap-2.5 py-3.5 rounded-xl font-bold transition-all duration-350 text-sm md:text-base focus:outline-none">
            <span class="text-lg md:text-xl">🧠</span>
            <span>สุขภาพจิต</span>
            <span :class="tab === 'mental' ? 'bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-400' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'" class="text-xs px-2.5 py-0.5 rounded-full font-semibold">6</span>
        </button>
        
        <button @click="tab = 'physical'" 
                :class="tab === 'physical' ? 'bg-white dark:bg-gray-900 text-emerald-700 dark:text-emerald-400 shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'" 
                class="flex-1 flex items-center justify-center gap-2.5 py-3.5 rounded-xl font-bold transition-all duration-350 text-sm md:text-base focus:outline-none">
            <span class="text-lg md:text-xl">🩺</span>
            <span>สุขภาพกาย</span>
            <span :class="tab === 'physical' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'" class="text-xs px-2.5 py-0.5 rounded-full font-semibold">4</span>
        </button>
    </div>

    {{-- Mental Tab Content --}}
    <div x-show="tab === 'mental'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                🧠
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">แบบประเมินสุขภาพจิต</h2>
                <p class="text-gray-500 dark:text-gray-400 text-xs md:text-sm">ตรวจสอบภาวะความเครียด ซึมเศร้า และสภาวะอารมณ์ของคุณอย่างใส่ใจ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- PHQ9 --}}
            <a href="{{ route('mental.assess.create', 'phq9') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-purple-200 dark:hover:border-purple-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        😔
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~5 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">ประเมินภาวะซึมเศร้า (PHQ-9)</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">9 คำถามมาตรฐานระดับสากล เพื่อสำรวจและประเมินภาวะความซึมเศร้าเบื้องต้น</p>
                </div>
            </a>

            {{-- GAD7 --}}
            <a href="{{ route('mental.assess.create', 'gad7') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-blue-200 dark:hover:border-blue-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        😰
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~3 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">ประเมินความกังวล (GAD-7)</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">7 คำถามตรวจวัดระดับความวิตกกังวล ความกระวนกระวายใจ และการควบคุมอารมณ์</p>
                </div>
            </a>

            {{-- PTSD --}}
            <a href="{{ route('mental.assess.create', 'ptsd') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-red-200 dark:hover:border-red-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        😨
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~5 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">ประเมินความเครียด (PTSD)</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">8 คำถามคัดกรองภาวะความเครียดและผลกระทบทางจิตใจรุนแรงหลังเหตุการณ์สะเทือนใจ</p>
                </div>
            </a>

            {{-- Disaster Stress --}}
            <a href="{{ route('mental.assess.create', 'disaster_stress') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-orange-200 dark:hover:border-orange-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-orange-50 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🌪️
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~4 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">ประเมินความเครียดจากภัยพิบัติ</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">6 คำถามเจาะลึกผลกระทบด้านความกลัวและความวิตกกังวลที่เกิดขึ้นจากภัยพิบัติโดยตรง</p>
                </div>
            </a>

            {{-- Burnout --}}
            <a href="{{ route('mental.assess.create', 'burnout') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-pink-200 dark:hover:border-pink-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-pink-50 dark:bg-pink-950/50 text-pink-600 dark:text-pink-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🪫
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~3 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">ประเมินภาวะหมดไฟ (Burnout)</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">5 คำถามประเมินระดับความเหนื่อยล้าทางอารมณ์และสภาวะหมดพลังในการก้าวข้ามปัญหา</p>
                </div>
            </a>

            {{-- Sleep Quality --}}
            <a href="{{ route('mental.assess.create', 'sleep_quality') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-indigo-200 dark:hover:border-indigo-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        😴
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~3 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">ประเมินคุณภาพการนอนหลับ</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">5 คำถามสำรวจสภาวะการนอนไม่หลับ ปัญหาการนอนหลับยากสะสมหลังเผชิญวิกฤตภัยพิบัติ</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Physical Tab Content --}}
    <div x-show="tab === 'physical'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                🩺
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">แบบประเมินสุขภาพกาย</h2>
                <p class="text-gray-500 dark:text-gray-400 text-xs md:text-sm">ตรวจเช็คอาการบาดเจ็บ ความเสี่ยง และความต้องการทางโภชนาการเบื้องต้น</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Physical General --}}
            <a href="{{ route('mental.assess.create', 'physical') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-emerald-200 dark:hover:border-emerald-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🩺
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~3 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">ประเมินสุขภาพกายเบื้องต้น</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">7 คำถามคัดกรองอาการเจ็บป่วย ไข้หวัด ท้องร่วง และอาการผิดปกติทางกายทั่วไป</p>
                </div>
            </a>

            {{-- Disease Risk --}}
            <a href="{{ route('mental.assess.create', 'disease_risk') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-teal-200 dark:hover:border-teal-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🦠
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~4 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">ประเมินความเสี่ยงโรคติดต่อ</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">6 คำถามสำรวจพฤติกรรมและความเสี่ยงต่อโรคระบาดที่มากับน้ำหรือหลังอุทกภัย</p>
                </div>
            </a>

            {{-- Injury Severity --}}
            <a href="{{ route('mental.assess.create', 'injury_severity') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-red-200 dark:hover:border-red-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🤕
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~3 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">ประเมินระดับความรุนแรงของแผล</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">5 คำถามประเมินอาการบาดเจ็บ บาดแผลติดเชื้อ และประเมินความต้องการการปฐมพยาบาล</p>
                </div>
            </a>

            {{-- Nutrition Status --}}
            <a href="{{ route('mental.assess.create', 'nutrition_status') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-amber-200 dark:hover:border-amber-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🍲
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        ⏱️ ~3 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">ประเมินความมั่นคงด้านอาหาร</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">5 คำถามสำรวจการเข้าถึงอาหารโภชนาการ และน้ำดื่มที่สะอาดระหว่างช่วงฟื้นฟูภัยพิบัติ</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Services Section --}}
    <div class="pt-6">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
            <span class="text-2xl">🌟</span> บริการฟื้นฟูสุขภาพแบบครบวงจร
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            {{-- Mood Tracker --}}
            <a href="{{ route('mental.mood.index') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-yellow-250 dark:hover:border-yellow-900/60 transition-all duration-300 text-center flex flex-col items-center transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-yellow-50 dark:bg-yellow-950/40 text-yellow-500 dark:text-yellow-400 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    😊
                </div>
                <div class="font-bold text-gray-800 dark:text-white group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">Mood Tracker</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">บันทึกและติดตามสภาวะอารมณ์รายวันเพื่อดูแนวโน้มการฟื้นฟู</div>
            </a>
            
            {{-- Journal --}}
            <a href="{{ route('mental.journal.index') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-indigo-200 dark:hover:border-indigo-900/60 transition-all duration-300 text-center flex flex-col items-center transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 dark:text-indigo-400 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    📔
                </div>
                <div class="font-bold text-gray-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Journal</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">เขียนไดอารี่ถ่ายทอดความรู้สึกและลดความเครียดที่สะสมอยู่ภายใน</div>
            </a>
            
            {{-- Appointment --}}
            <a href="{{ route('mental.appointments.create') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-blue-200 dark:hover:border-blue-900/60 transition-all duration-300 text-center flex flex-col items-center transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-blue-50 dark:bg-blue-950/40 text-blue-500 dark:text-blue-400 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    📅
                </div>
                <div class="font-bold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">นัดหมายผู้เชี่ยวชาญ</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">นัดหมายพูดคุยและปรึกษากับนักจิตวิทยาหรือจิตแพทย์อาสา</div>
            </a>

            {{-- Articles & Guidelines --}}
            <a href="{{ route('mental.articles') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-purple-250 dark:hover:border-purple-900/60 transition-all duration-300 text-center flex flex-col items-center transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-purple-50 dark:bg-purple-950/40 text-purple-650 dark:text-purple-400 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    📚
                </div>
                <div class="font-bold text-gray-800 dark:text-white group-hover:text-purple-600 dark:hover:text-purple-400 transition-colors">บทความ & คำแนะนำ</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">คลังบทความและแนวทางปฐมพยาบาลดูแลสุขภาพกายและใจ</div>
            </a>
            
            {{-- AI Companion (Interactive click) --}}
            <button onclick="document.querySelector('[x-data=\'aiCompanion()\'] button')?.click()" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-purple-200 dark:hover:border-purple-900/60 transition-all duration-300 text-center flex flex-col items-center w-full transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-purple-50 dark:bg-purple-950/40 text-purple-500 dark:text-purple-400 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    🤖
                </div>
                <div class="font-bold text-gray-800 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">AI Companion</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">พูดคุยปรึกษากับระบบ AI อัจฉริยะที่จะอยู่เคียงข้างคุณตลอด 24 ชม.</div>
            </button>
        </div>
    </div>

    {{-- Bottom Section (History & Mood) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Assessment History --}}
        @if(isset($assessments) && $assessments->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700/50 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg md:text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="text-2xl">📋</span> ประวัติการทำแบบประเมิน
                    </h2>
                </div>
                <div class="space-y-4">
                    @foreach($assessments->take(5) as $a)
                    <a href="{{ route('mental.assess.show', $a) }}" class="group flex items-center justify-between p-4 rounded-2xl border border-gray-50 dark:border-gray-700/30 hover:border-purple-150 dark:hover:border-purple-900/40 hover:bg-purple-50/20 dark:hover:bg-purple-950/20 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl {{ $a->type == 'phq9' ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-600' : ($a->type == 'gad7' ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-600' : ($a->type == 'physical' ? 'bg-green-50 dark:bg-green-950/50 text-green-600' : ($a->type == 'disaster_stress' ? 'bg-orange-50 dark:bg-orange-950/50 text-orange-600' : ($a->type == 'disease_risk' ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-600' : ($a->type == 'burnout' ? 'bg-pink-50 dark:bg-pink-950/50 text-pink-600' : ($a->type == 'sleep_quality' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600' : ($a->type == 'injury_severity' ? 'bg-red-50 dark:bg-red-950/50 text-red-600' : ($a->type == 'nutrition_status' ? 'bg-amber-50 dark:bg-amber-950/50 text-amber-600' : 'bg-red-50 dark:bg-red-950/50 text-red-600')))))))) }}">
                                {{ $a->type == 'phq9' ? '😔' : ($a->type == 'gad7' ? '😰' : ($a->type == 'physical' ? '🩺' : ($a->type == 'disaster_stress' ? '🌪️' : ($a->type == 'disease_risk' ? '🦠' : ($a->type == 'burnout' ? '🪫' : ($a->type == 'sleep_quality' ? '😴' : ($a->type == 'injury_severity' ? '🤕' : ($a->type == 'nutrition_status' ? '🍲' : '😨')))))))) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 dark:text-white group-hover:text-purple-700 dark:group-hover:text-purple-400 transition-colors">{{ $a->type == 'physical' ? 'สุขภาพกายเบื้องต้น' : ($a->type == 'disaster_stress' ? 'เครียดภัยพิบัติ' : ($a->type == 'disease_risk' ? 'เสี่ยงโรคติดต่อ' : ($a->type == 'burnout' ? 'ภาวะหมดไฟ' : ($a->type == 'sleep_quality' ? 'คุณภาพการนอน' : ($a->type == 'injury_severity' ? 'ระดับบาดเจ็บ' : ($a->type == 'nutrition_status' ? 'ขาดแคลนอาหาร' : strtoupper($a->type))))))) }}</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1 mt-1">
                                    <span>📅</span> {{ $a->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-800 dark:text-white">{{ $a->score }} คะแนน</div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full inline-block mt-1.5
                                {{ $a->severity == 'severe' ? 'bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400' : ($a->severity == 'moderate' ? 'bg-orange-100 dark:bg-orange-950 text-orange-700 dark:text-orange-400' : ($a->severity == 'mild' ? 'bg-yellow-100 dark:bg-yellow-950 text-yellow-700 dark:text-yellow-400' : 'bg-green-100 dark:bg-green-950 text-green-700 dark:text-green-400')) }}">
                                {{ $a->severity_label }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Mood History --}}
        @if(isset($moods) && $moods->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700/50 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg md:text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="text-2xl">📈</span> สถิติอารมณ์ล่าสุด
                    </h2>
                    <a href="{{ route('mental.mood.index') }}" class="text-xs md:text-sm font-semibold text-purple-600 dark:text-purple-400 hover:underline">ดูบันทึกทั้งหมด →</a>
                </div>
                <div class="flex gap-3 overflow-x-auto pb-4 pt-2 hide-scrollbar">
                    @foreach($moods->take(7) as $m)
                    <div class="flex-shrink-0 flex flex-col items-center p-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 min-w-[80px]">
                        <div class="text-3xl mb-3 transform hover:scale-110 transition-transform cursor-default">{{ ['','😢','😞','😐','😊','😄'][$m->mood] }}</div>
                        <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $m->logged_date->format('D') }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $m->logged_date->format('d/m') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

</div>

@endsection
