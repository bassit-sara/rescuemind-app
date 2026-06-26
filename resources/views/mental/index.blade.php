@extends('layouts.app')
@section('title', 'ศูนย์สุขภาพจิตและกาย')
@section('page-title')
    <x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" /> ศูนย์ฟื้นฟูสุขภาพกายและใจ
@endsection
@section('content')

<div x-data="{ tab: 'mental', showConsent: false, consentUrl: '', consentChecked: false, showMoodModal: false, selectedMood: {} }" class="space-y-6">
    
    {{-- Top Navigation --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('mt3') }}" class="flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-sm border border-gray-200 text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span class="text-gray-600 font-bold">กลับหน้าหลัก (MT3)</span>
    </div>

    {{-- Hero Section --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 via-purple-950 to-indigo-950 text-white shadow-xl border border-purple-900/30">
        {{-- Glowing background spheres --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-purple-600 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-indigo-500 rounded-full blur-[100px]"></div>
        </div>
        
        <div class="relative z-10 px-8 py-10 md:p-12 lg:p-16 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-xs font-semibold mb-6">
                <x-heroicon-o-sparkles class="w-5 h-5 inline-block shrink-0" /> Wellness & Resilience Center
            </div>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight mb-4 tracking-tight">
                เริ่มต้นดูแลสุขภาพ<br>
                <span class="bg-gradient-to-r from-purple-300 via-pink-300 to-indigo-200 bg-clip-text text-transparent">กายและจิต</span> ของคุณตั้งแต่วันนี้
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
            <span class="text-lg md:text-xl"><x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" /></span>
            <span>สุขภาพจิต</span>
            <span :class="tab === 'mental' ? 'bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-400' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'" class="text-xs px-2.5 py-0.5 rounded-full font-semibold">6</span>
        </button>
        
        <button @click="tab = 'physical'" 
                :class="tab === 'physical' ? 'bg-white dark:bg-gray-900 text-emerald-700 dark:text-emerald-400 shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'" 
                class="flex-1 flex items-center justify-center gap-2.5 py-3.5 rounded-xl font-bold transition-all duration-350 text-sm md:text-base focus:outline-none">
            <span class="text-lg md:text-xl"><x-heroicon-o-heart class="w-5 h-5 inline-block mr-1 -mt-1" /></span>
            <span>สุขภาพกาย</span>
            <span :class="tab === 'physical' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'" class="text-xs px-2.5 py-0.5 rounded-full font-semibold">4</span>
        </button>
    </div>

    {{-- Mental Tab Content --}}
    <div x-show="tab === 'mental'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                <x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" />
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">แบบประเมินสุขภาพจิต</h2>
                <p class="text-gray-500 dark:text-gray-400 text-xs md:text-sm">ตรวจสอบภาวะความเครียด ซึมเศร้า และสภาวะอารมณ์ของคุณอย่างใส่ใจ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(isset($customForms) && $customForms->count() > 0)
                @foreach($customForms as $form)
                    @if(($form->category ?? 'mental') === 'mental')
                        @php
                            $colorClass = match($form->color_theme) {
                                'pink' => 'pink',
                                'orange' => 'orange',
                                'emerald' => 'emerald',
                                'blue' => 'blue',
                                'purple' => 'purple',
                                'red' => 'red',
                                'teal' => 'teal',
                                'rose' => 'rose',
                                'amber' => 'amber',
                                default => 'indigo',
                            };
                        @endphp
                        <a href="{{ route('mental.assess.create', $form->slug) }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-{{ $colorClass }}-200 dark:hover:border-{{ $colorClass }}-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                            <div class="flex items-start justify-between mb-6">
                                <div class="w-12 h-12 bg-{{ $colorClass }}-50 dark:bg-{{ $colorClass }}-950/50 text-{{ $colorClass }}-600 dark:text-{{ $colorClass }}-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                    @if(str_starts_with($form->icon, 'o-') || str_starts_with($form->icon, 's-'))
                                        @svg('heroicon-' . $form->icon, 'w-5 h-5 inline-block mr-1 -mt-1')
                                    @else
                                        <x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block mr-1 -mt-1" />
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                                    <x-heroicon-o-clock class="w-5 h-5 inline-block mr-1 -mt-1" />️ {{ $form->time_estimate ?? '~3 นาที' }}
                                </div>
                            </div>
                            <div class="mt-auto">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-{{ $colorClass }}-600 dark:group-hover:text-{{ $colorClass }}-400 transition-colors">{{ $form->title }}</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ $form->description }}</p>
                            </div>
                        </a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

    {{-- Physical Tab Content --}}
    <div x-show="tab === 'physical'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                <x-heroicon-o-heart class="w-5 h-5 inline-block mr-1 -mt-1" />
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
                        <x-heroicon-o-heart class="w-5 h-5 inline-block mr-1 -mt-1" />
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        <x-heroicon-o-clock class="w-5 h-5 inline-block mr-1 -mt-1" />️ ~3 นาที
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
                        <x-heroicon-o-bug-ant class="w-5 h-5 inline-block mr-1 -mt-1" />
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                        <x-heroicon-o-clock class="w-5 h-5 inline-block mr-1 -mt-1" />️ ~4 นาที
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">ประเมินความเสี่ยงโรคติดต่อ</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">6 คำถามสำรวจพฤติกรรมและความเสี่ยงต่อโรคระบาดที่มากับน้ำหรือหลังอุทกภัย</p>
                </div>
            </a>

            @if(isset($customForms) && $customForms->count() > 0)
                @foreach($customForms as $form)
                    @if(($form->category ?? 'mental') === 'physical')
                        @php
                            $colorClass = match($form->color_theme) {
                                'pink' => 'pink',
                                'orange' => 'orange',
                                'emerald' => 'emerald',
                                'blue' => 'blue',
                                'purple' => 'purple',
                                'red' => 'red',
                                'teal' => 'teal',
                                'rose' => 'rose',
                                'amber' => 'amber',
                                default => 'indigo',
                            };
                        @endphp
                        <a href="{{ route('mental.assess.create', $form->slug) }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl hover:border-{{ $colorClass }}-200 dark:hover:border-{{ $colorClass }}-900/60 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                            <div class="flex items-start justify-between mb-6">
                                <div class="w-12 h-12 bg-{{ $colorClass }}-50 dark:bg-{{ $colorClass }}-950/50 text-{{ $colorClass }}-600 dark:text-{{ $colorClass }}-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                    @if(str_starts_with($form->icon, 'o-') || str_starts_with($form->icon, 's-'))
                                        @svg('heroicon-' . $form->icon, 'w-5 h-5 inline-block mr-1 -mt-1')
                                    @else
                                        <x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block mr-1 -mt-1" />
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-semibold rounded-full">
                                    <x-heroicon-o-clock class="w-5 h-5 inline-block mr-1 -mt-1" />️ {{ $form->time_estimate ?? '~3 นาที' }}
                                </div>
                            </div>
                            <div class="mt-auto">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-{{ $colorClass }}-600 dark:group-hover:text-{{ $colorClass }}-400 transition-colors">{{ $form->title }}</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ $form->description }}</p>
                            </div>
                        </a>
                    @endif
                @endforeach
            @endif
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
                        <span class="text-2xl"><x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" /></span> ประวัติการทำแบบประเมิน
                    </h2>
                </div>
                <div class="space-y-4">
                    @foreach($assessments->take(5) as $a)
                    @php
                        $bgClass = 'bg-red-50 dark:bg-red-950/50 text-red-600';
                        $iconName = 'heroicon-o-face-frown';
                        $typeName = strtoupper($a->type);
                        
                        switch ($a->type) {
                            case 'phq9':
                            case 'gad7':
                                $bgClass = $a->type == 'phq9' ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-600' : 'bg-blue-50 dark:bg-blue-950/50 text-blue-600';
                                $iconName = 'heroicon-o-face-frown';
                                break;
                            case 'physical':
                                $bgClass = 'bg-green-50 dark:bg-green-950/50 text-green-600';
                                $iconName = 'heroicon-o-heart';
                                $typeName = 'สุขภาพกายเบื้องต้น';
                                break;
                            case 'disaster_stress':
                                $bgClass = 'bg-orange-50 dark:bg-orange-950/50 text-orange-600';
                                $iconName = 'heroicon-o-arrow-path';
                                $typeName = 'เครียดภัยพิบัติ';
                                break;
                            case 'disease_risk':
                                $bgClass = 'bg-teal-50 dark:bg-teal-950/50 text-teal-600';
                                $iconName = 'heroicon-o-bug-ant';
                                $typeName = 'เสี่ยงโรคติดต่อ';
                                break;
                            case 'burnout':
                                $bgClass = 'bg-pink-50 dark:bg-pink-950/50 text-pink-600';
                                $iconName = 'heroicon-o-battery-0';
                                $typeName = 'ภาวะหมดไฟ';
                                break;
                            case 'sleep_quality':
                                $bgClass = 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600';
                                $iconName = 'heroicon-o-moon';
                                $typeName = 'คุณภาพการนอน';
                                break;
                            case 'injury_severity':
                                $bgClass = 'bg-red-50 dark:bg-red-950/50 text-red-600';
                                $iconName = 'heroicon-o-face-frown';
                                $typeName = 'ระดับบาดเจ็บ';
                                break;
                            case 'nutrition_status':
                                $bgClass = 'bg-amber-50 dark:bg-amber-950/50 text-amber-600';
                                $iconName = 'heroicon-o-cube';
                                $typeName = 'ขาดแคลนอาหาร';
                                break;
                        }
                    @endphp
                    <a href="{{ route('mental.assess.show', $a) }}" class="group flex items-center justify-between p-4 rounded-2xl border border-gray-50 dark:border-gray-700/30 hover:border-purple-150 dark:hover:border-purple-900/40 hover:bg-purple-50/20 dark:hover:bg-purple-950/20 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl {{ $bgClass }}">
                                <x-dynamic-component :component="$iconName" class="w-6 h-6 inline-block" />
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 dark:text-white group-hover:text-purple-700 dark:group-hover:text-purple-400 transition-colors">{{ $typeName }}</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1 mt-1">
                                    <span><x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /></span> {{ $a->created_at->format('d/m/Y H:i') }}
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
                        <span class="text-2xl"><x-heroicon-o-presentation-chart-line class="w-5 h-5 inline-block shrink-0" /></span> สถิติอารมณ์ล่าสุด
                    </h2>
                    <a href="{{ route('mental.mood.index') }}" class="text-xs md:text-sm font-semibold text-purple-600 dark:text-purple-400 hover:underline">ดูบันทึกทั้งหมด →</a>
                </div>
                <div class="flex gap-3 overflow-x-auto pb-4 pt-2 hide-scrollbar">
                    @foreach($moods->take(7) as $m)
                    <button type="button" data-mood="{{ json_encode([
                        'emoji' => ['','😭','😢','😐','🙂','😄'][$m->mood ?? 3],
                        'label' => $m->mood_label,
                        'date' => $m->logged_date->format('d M Y'),
                        'time' => $m->created_at->format('H:i'),
                        'note' => $m->note ?? '-'
                    ]) }}" @click="selectedMood = JSON.parse($event.currentTarget.dataset.mood); showMoodModal = true" class="flex-shrink-0 flex flex-col items-center p-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 min-w-[80px]">
                        <div class="text-3xl mb-3 transform hover:scale-110 transition-transform cursor-pointer">{{ ['','😭','😢','😐','🙂','😄'][$m->mood ?? 3] }}</div>
                        <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $m->logged_date->format('D') }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $m->logged_date->format('d/m') }}</div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- PDPA Consent Modal --}}
    <div x-show="showConsent" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            {{-- Background Overlay --}}
            <div x-show="showConsent" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900/70 backdrop-blur-sm" aria-hidden="true" @click="showConsent = false"></div>

            {{-- Modal Panel --}}
            <div x-show="showConsent" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block w-full max-w-xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:p-0 sm:align-middle border border-purple-100">
                
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-purple-700 to-indigo-700 px-5 py-4 flex items-start justify-between rounded-t-3xl text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                            <x-heroicon-o-lock-closed class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-purple-100 text-xs font-semibold mb-0.5">หนังสือให้ความยินยอม</p>
                            <h3 class="text-lg font-bold leading-5" id="modal-title">การเก็บรวบรวมข้อมูลส่วนบุคคล</h3>
                            <p class="text-purple-200 text-[10px] mt-0.5">ตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)</p>
                        </div>
                    </div>
                    <button type="button" @click="showConsent = false" class="text-purple-200 hover:text-white transition-colors p-1.5 bg-white/10 hover:bg-white/20 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Modal Content (Scrollable) --}}
                <div class="px-5 py-5 max-h-[55vh] overflow-y-auto space-y-4 text-gray-800 text-[13px] leading-relaxed bg-gray-50/80">
                    
                    {{-- 1 --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-start gap-2.5 mb-1.5">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-[11px] font-bold mt-0.5">1</span>
                            <h4 class="font-bold text-gray-900 text-sm">วัตถุประสงค์การเก็บรวบรวมข้อมูล</h4>
                        </div>
                        <p class="ml-7 text-gray-700">เพื่อประเมินสุขภาพจิตและสุขภาพกายเบื้องต้น คัดกรองความเสี่ยงต่อปัญหาสุขภาพ และติดตามดูแลช่วยเหลือผู้ที่เสี่ยงมีปัญหาสุขภาพด้วยระบบคอมพิวเตอร์ รวมถึงพัฒนาฐานข้อมูลด้านการดำเนินงานสาธารณสุข</p>
                    </div>

                    {{-- 2 --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-start gap-2.5 mb-2">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-[11px] font-bold mt-0.5">2</span>
                            <h4 class="font-bold text-gray-900 text-sm">ข้อมูลส่วนบุคคลที่เก็บรวบรวม</h4>
                        </div>
                        <ul class="ml-7 space-y-1.5 text-gray-700">
                            <li class="flex items-start gap-1.5"><span class="text-purple-600 mt-0.5">✓</span> <span><b>ข้อมูลระบุตัวตน</b> — ชื่อ นามสกุล เพศ อายุ ที่อยู่ เบอร์โทรศัพท์</span></li>
                            <li class="flex items-start gap-1.5"><span class="text-purple-600 mt-0.5">✓</span> <span><b>ข้อมูลสุขภาพ</b> — ภาวะความเครียด ภาวะซึมเศร้า ความเสี่ยงด้านสุขภาพ</span></li>
                            <li class="flex items-start gap-1.5"><span class="text-purple-600 mt-0.5">✓</span> <span><b>ข้อมูลปัจจัยเสี่ยง</b> — เช่น การเป็นผู้ประสบภัยน้ำท่วม หรือสถานการณ์วิกฤตอื่น ๆ</span></li>
                            <li class="flex items-start gap-1.5"><span class="text-purple-600 mt-0.5">✓</span> <span><b>ข้อมูลภาวะสุขภาพจากแบบประเมินมาตรฐาน</b> (ST-5, PHQ-9 ฯลฯ)</span></li>
                        </ul>
                    </div>

                    {{-- 3 --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-start gap-2.5 mb-1.5">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-[11px] font-bold mt-0.5">3</span>
                            <h4 class="font-bold text-gray-900 text-sm">การเก็บรักษาและการเปิดเผยข้อมูล</h4>
                        </div>
                        <p class="ml-7 text-gray-700">ข้อมูลจะถูกเก็บรักษาอย่างปลอดภัยตามมาตรฐาน และจะไม่ถูกเปิดเผยแก่บุคคลภายนอก เว้นแต่ได้รับความยินยอม หรือมีเหตุจำเป็นตามที่กฎหมายกำหนด โดยข้อมูลจะถูกเก็บไว้ตลอดระยะเวลาโครงการหรือนานกว่านั้นตามที่จำเป็น</p>
                    </div>

                    {{-- 4 --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-start gap-2.5 mb-2">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-[11px] font-bold mt-0.5">4</span>
                            <h4 class="font-bold text-gray-900 text-sm">สิทธิของเจ้าของข้อมูล (ตาม PDPA มาตรา 30–43)</h4>
                        </div>
                        <div class="ml-7 grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-gray-700 text-xs">
                            <div class="flex items-center gap-1.5"><x-heroicon-o-eye class="w-5 h-5 inline-block mr-1 -mt-1" /> สิทธิในการเข้าถึงข้อมูล</div>
                            <div class="flex items-center gap-1.5"><x-heroicon-o-pencil class="w-5 h-5 inline-block mr-1 -mt-1" />️ สิทธิในการแก้ไขข้อมูล</div>
                            <div class="flex items-center gap-1.5"><x-heroicon-o-trash class="w-5 h-5 inline-block mr-1 -mt-1" /> สิทธิในการลบข้อมูล</div>
                            <div class="flex items-center gap-1.5"><x-heroicon-o-no-symbol class="w-5 h-5 inline-block mr-1 -mt-1" /> สิทธิในการคัดค้าน</div>
                            <div class="flex items-center gap-1.5"><x-heroicon-o-archive-box class="w-5 h-5 inline-block mr-1 -mt-1" /> สิทธิในการโอนย้าย</div>
                            <div class="flex items-center gap-1.5"><x-heroicon-o-pause class="w-5 h-5 inline-block mr-1 -mt-1" /> สิทธิในการระงับการใช้</div>
                        </div>
                        <p class="ml-7 mt-2 text-gray-500 text-[11px]">ท่านสามารถใช้สิทธิ์ดังกล่าวได้โดยติดต่อผู้ควบคุมข้อมูลตามช่องทางที่ระบุในนโยบายคุ้มครองข้อมูลส่วนบุคคล</p>
                    </div>

                    {{-- 5 --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <h4 class="font-bold text-gray-900 text-sm mb-1.5">การถอนความยินยอม</h4>
                        <p class="text-gray-700">ท่านมีสิทธิ์ถอนความยินยอมได้ทุกเมื่อ โดยการถอนความยินยอมจะไม่กระทบต่อการประมวลผลข้อมูลที่ได้กระทำไปแล้วก่อนหน้าโดยชอบด้วยกฎหมาย ทั้งนี้การถอนความยินยอมอาจส่งผลให้ท่านไม่สามารถรับบริการบางส่วนหรือทั้งหมดได้</p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-white px-5 py-4 rounded-b-3xl border-t border-gray-100">
                    <label class="flex items-start gap-2.5 cursor-pointer group mb-4 bg-purple-50/60 p-3 rounded-xl border border-purple-200 hover:bg-purple-100/50 transition-colors">
                        <div class="flex items-center h-4 mt-0.5">
                            <input type="checkbox" x-model="consentChecked" class="w-4 h-4 text-purple-600 bg-white border-purple-300 rounded focus:ring-purple-500 cursor-pointer">
                        </div>
                        <div class="text-[13px] leading-snug">
                            <span class="text-gray-800 font-medium select-none">ข้าพเจ้าได้อ่านและเข้าใจเนื้อหาข้างต้นครบถ้วนแล้ว และ<span class="font-extrabold text-purple-700">ยินยอมให้เก็บรวบรวม ใช้ และเปิดเผยข้อมูลส่วนบุคคล</span> ตามวัตถุประสงค์ที่ระบุไว้</span>
                        </div>
                    </label>

                    <div class="flex flex-col sm:flex-row gap-2.5">
                        <button type="button" @click="showConsent = false" class="w-full sm:w-1/3 inline-flex justify-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none transition-colors">
                            ไม่ยินยอม / ย้อนกลับ
                        </button>
                        <button type="button" :disabled="!consentChecked" @click="if(consentChecked) window.location.href = consentUrl" 
                                :class="consentChecked ? 'bg-purple-600 hover:bg-purple-700 text-white shadow-md shadow-purple-200' : 'bg-gray-200 text-gray-400 cursor-not-allowed'" 
                                class="w-full sm:w-2/3 inline-flex justify-center rounded-xl px-4 py-2.5 text-sm font-bold transition-all duration-300">
                            <span x-show="!consentChecked">กรุณาทำเครื่องหมายยืนยันก่อน</span>
                            <span x-show="consentChecked" style="display: none;">รับทราบและดำเนินการต่อ</span>
                        </button>
                    </div>
                    <div class="text-center mt-3">
                        <a :href="'{{ route('privacy.policy') }}?from_pdpa=1&consent_url=' + encodeURIComponent(consentUrl)" class="text-[11px] text-gray-400 hover:text-purple-600 flex items-center justify-center gap-1 transition-colors">
                            <x-heroicon-o-lock-closed class="w-3 h-3" /> นโยบายคุ้มครองข้อมูลส่วนบุคคล (Privacy Policy)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mood Detail Modal --}}
    <div x-show="showMoodModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="mood-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showMoodModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900/70 backdrop-blur-sm" aria-hidden="true" @click="showMoodModal = false"></div>

            <div x-show="showMoodModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block w-full max-w-sm p-6 sm:p-8 overflow-hidden text-center align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle">
                
                <div class="flex flex-col items-center justify-center">
                    <div class="text-[4.5rem] leading-none mb-3 drop-shadow-md" x-text="selectedMood.emoji"></div>
                    <h3 class="text-xl font-extrabold text-gray-800 tracking-tight" id="mood-modal-title" x-text="selectedMood.label"></h3>
                    
                    <div class="flex items-center justify-center gap-1.5 mt-2 text-fuchsia-500 font-bold text-[13px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span x-text="selectedMood.date"></span>
                    </div>
                </div>
                
                <div class="relative mt-6 mb-6 text-left">
                    <fieldset class="border border-gray-100 rounded-2xl px-4 pb-5 pt-4 relative bg-gray-50/30">
                        <legend class="text-[11px] font-bold text-gray-400 px-2 ml-4 bg-white whitespace-nowrap">โน้ตของคุณ</legend>
                        <p class="text-[15px] text-gray-600 italic whitespace-pre-line leading-relaxed text-center" x-text="'&quot;' + selectedMood.note + '&quot;'"></p>
                    </fieldset>
                </div>
                
                <button type="button" @click="showMoodModal = false" class="w-full inline-flex justify-center items-center rounded-2xl bg-gray-100 px-4 py-3.5 text-[15px] font-bold text-gray-700 hover:bg-gray-200 focus:outline-none transition-colors">
                    ปิด
                </button>
            </div>
        </div>
    </div>

</div>

@endsection
