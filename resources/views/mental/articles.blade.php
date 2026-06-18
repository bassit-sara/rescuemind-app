@extends('layouts.app')
@section('title', 'บทความและคำแนะนำ')
@section('page-title', '📔 คลังความรู้ บทความ & คำแนะนำ')
@section('content')

<div class="max-w-6xl mx-auto space-y-8" x-data="{ activeCategory: 'all', search: '' }">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('mental.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition-colors group">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-250 dark:border-gray-750 bg-white dark:bg-gray-800 group-hover:border-purple-300 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
            ย้อนกลับไปยังศูนย์ฟื้นฟูสุขภาพ
        </a>
    </div>

    {{-- Hero Section --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-purple-900 via-indigo-950 to-gray-900 text-white shadow-xl border border-purple-900/30">
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <div class="absolute top-10 right-10 w-80 h-80 bg-purple-500 rounded-full blur-[80px]"></div>
            <div class="absolute bottom-10 left-10 w-80 h-80 bg-indigo-500 rounded-full blur-[80px]"></div>
        </div>
        <div class="relative z-10 px-8 py-10 md:p-12 text-center max-w-3xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-4">บทความ & คำแนะนำในการดูแลตัวเอง</h1>
            <p class="text-purple-200 text-sm md:text-base leading-relaxed">
                คลังความรู้ที่รวบรวมแนวทางการดูแลสุขภาพกายและจิตใจสำหรับผู้ประสบภัยและบุคคลทั่วไป โดยผู้เชี่ยวชาญทางการแพทย์และสุขภาพจิต
            </p>
            
            {{-- Search Bar --}}
            <div class="mt-8 max-w-md mx-auto relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    🔍
                </span>
                <input type="text" x-model="search" placeholder="ค้นหาบทความหรือคำแนะนำ..." 
                       class="w-full pl-10 pr-4 py-3 bg-white/10 hover:bg-white/15 focus:bg-white focus:text-gray-900 border border-white/20 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 rounded-2xl text-sm transition-all placeholder-gray-400 focus:placeholder-gray-500">
            </div>
        </div>
    </div>

    {{-- Categories & Filters --}}
    <div class="flex flex-wrap items-center justify-center gap-3">
        @foreach([
            ['all', '🌐 ทั้งหมด'],
            ['mental', '🧠 การดูแลจิตใจ'],
            ['physical', '🩺 การดูแลสุขภาพกาย'],
            ['prevention', '🛡️ การปฐมพยาบาล & ป้องกัน']
        ] as [$id, $label])
        <button @click="activeCategory = '{{ $id }}'"
                :class="activeCategory === '{{ $id }}' ? 'bg-purple-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-gray-700 hover:bg-gray-50'"
                class="px-5 py-2.5 rounded-full text-sm font-bold transition-all focus:outline-none">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Articles Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-cloak>
        @foreach($articles as $a)
        <a href="{{ route('mental.articles.show', $a['id']) }}"
           x-show="(activeCategory === 'all' || activeCategory === '{{ $a['category'] }}') && ('{{ strtolower($a['title']) }} {{ strtolower($a['desc']) }}'.includes(search.toLowerCase()))"
           class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300 flex flex-col justify-between transform hover:-translate-y-1">
            
            <div class="space-y-4">
                {{-- Header strip --}}
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold px-3 py-1 bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 rounded-full">
                        {{ $a['category'] === 'mental' ? '🧠 สุขภาพจิต' : ($a['category'] === 'physical' ? '🩺 สุขภาพกาย' : '🛡️ การป้องกัน') }}
                    </span>
                    <span class="text-xs text-gray-400 flex items-center gap-1 font-medium">
                        ⏱️ {{ $a['read_time'] }}
                    </span>
                </div>

                {{-- Content --}}
                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors line-clamp-2">
                        {{ $a['title'] }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3">
                        {{ $a['desc'] }}
                    </p>
                </div>
            </div>

            {{-- Footer / Author --}}
            <div class="mt-6 pt-4 border-t border-gray-50 dark:border-gray-700/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">{{ $a['icon'] }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">ผู้เขียน: {{ $a['author'] }}</span>
                </div>
                <span class="text-xs font-bold text-purple-600 dark:text-purple-400 group-hover:underline flex items-center gap-1">
                    อ่านเพิ่มเติม
                    <span>→</span>
                </span>
            </div>
        </a>
        @endforeach
    </div>

</div>

@endsection
