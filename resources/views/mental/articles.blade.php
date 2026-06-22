@extends(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin', 'mental_officer', 'volunteer']) ? 'layouts.admin' : 'layouts.app')
@section('title', 'บทความและคำแนะนำ')
@section('page-title')
    <x-heroicon-o-book-open class="w-5 h-5 inline-block shrink-0" /> คลังความรู้ บทความ & คำแนะนำ
@endsection
@section('content')

<div class="max-w-6xl mx-auto space-y-8" x-data="{ activeCategory: 'all', search: '' }">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('mt3') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition-colors group">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-250 dark:border-gray-750 bg-white dark:bg-gray-800 group-hover:border-purple-300 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
            ย้อนกลับไปที่ MT3
        </a>
    </div>

    {{-- Hero Section --}}
    <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-rose-400 via-pink-500 to-purple-600 text-white shadow-lg border border-pink-200/50">
        <div class="absolute inset-0 opacity-20 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute inset-0 opacity-30 pointer-events-none">
            <div class="absolute top-0 right-10 w-96 h-96 bg-orange-300 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-10 w-96 h-96 bg-purple-400 rounded-full blur-[100px]"></div>
        </div>
        <div class="relative z-10 px-8 py-12 md:p-16 text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 shadow-inner mb-6">
                <x-heroicon-o-book-open class="w-8 h-8 text-white drop-shadow-sm" />
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold mb-5 tracking-tight drop-shadow-sm">บทความ & คำแนะนำ</h1>
            <p class="text-pink-50 text-sm md:text-lg leading-relaxed font-medium drop-shadow-sm">
                คลังความรู้ที่รวบรวมแนวทางการดูแลสุขภาพกายและจิตใจสำหรับผู้ประสบภัยและบุคคลทั่วไป โดยผู้เชี่ยวชาญทางการแพทย์และสุขภาพจิต
            </p>
            
            @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin', 'mental_officer', 'volunteer']))
            <div class="mt-6">
                <a href="{{ route('mental-officer.articles.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-rose-600 rounded-full font-bold shadow-md hover:bg-rose-50 transition-colors">
                    <x-heroicon-o-plus class="w-5 h-5" /> เพิ่มบทความใหม่
                </a>
            </div>
            @endif

            {{-- Search Bar --}}
            <div class="mt-10 max-w-lg mx-auto relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-pink-500 transition-colors">
                    <x-heroicon-o-magnifying-glass class="w-6 h-6" />
                </span>
                <input type="text" x-model="search" placeholder="ค้นหาบทความหรือคำแนะนำ..." 
                       class="w-full pl-12 pr-6 py-4 bg-white/90 backdrop-blur-sm focus:bg-white text-gray-800 border-2 border-white/50 focus:border-pink-300 focus:ring-4 focus:ring-pink-100/50 rounded-2xl text-base font-medium transition-all shadow-md placeholder-gray-400">
            </div>
        </div>
    </div>

    {{-- Categories & Filters --}}
    <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center justify-center gap-3 mb-10 px-2 sm:px-0">
        <button @click="activeCategory = 'all'"
                :class="activeCategory === 'all' ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-md' : 'bg-white text-gray-600 border border-rose-100 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 shadow-sm'"
                class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-2.5 px-3 py-4 sm:px-6 sm:py-3 rounded-[1.25rem] text-[13px] sm:text-sm font-bold transition-all duration-300 focus:outline-none w-full sm:w-auto group">
            <x-heroicon-o-globe-alt class="w-7 h-7 sm:w-5 sm:h-5 shrink-0 group-hover:scale-110 transition-transform" />
            <span class="text-center leading-tight">ทั้งหมด</span>
        </button>

        <button @click="activeCategory = 'mental'"
                :class="activeCategory === 'mental' ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-md' : 'bg-white text-gray-600 border border-purple-100 hover:bg-purple-50 hover:text-purple-600 hover:border-purple-200 shadow-sm'"
                class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-2.5 px-3 py-4 sm:px-6 sm:py-3 rounded-[1.25rem] text-[13px] sm:text-sm font-bold transition-all duration-300 focus:outline-none w-full sm:w-auto group">
            <x-heroicon-o-light-bulb class="w-7 h-7 sm:w-5 sm:h-5 shrink-0 group-hover:scale-110 transition-transform" />
            <span class="text-center leading-tight">การดูแลจิตใจ</span>
        </button>

        <button @click="activeCategory = 'physical'"
                :class="activeCategory === 'physical' ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-md' : 'bg-white text-gray-600 border border-blue-100 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 shadow-sm'"
                class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-2.5 px-3 py-4 sm:px-6 sm:py-3 rounded-[1.25rem] text-[13px] sm:text-sm font-bold transition-all duration-300 focus:outline-none w-full sm:w-auto group">
            <x-heroicon-o-heart class="w-7 h-7 sm:w-5 sm:h-5 shrink-0 group-hover:scale-110 transition-transform" />
            <span class="text-center leading-tight">ดูแลสุขภาพกาย</span>
        </button>

        <button @click="activeCategory = 'prevention'"
                :class="activeCategory === 'prevention' ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-md' : 'bg-white text-gray-600 border border-green-100 hover:bg-green-50 hover:text-green-600 hover:border-green-200 shadow-sm'"
                class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-2.5 px-3 py-4 sm:px-6 sm:py-3 rounded-[1.25rem] text-[13px] sm:text-sm font-bold transition-all duration-300 focus:outline-none w-full sm:w-auto group">
            <x-heroicon-o-shield-check class="w-7 h-7 sm:w-5 sm:h-5 shrink-0 group-hover:scale-110 transition-transform" />
            <span class="text-center leading-tight">ปฐมพยาบาล</span>
        </button>
    </div>

    {{-- Articles Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-cloak>
        @foreach($articles as $a)
        <div x-show="(activeCategory === 'all' || activeCategory === '{{ $a->category }}') && ('{{ strtolower($a->title) }} {{ strtolower($a->desc) }}'.includes(search.toLowerCase()))" class="relative group">
            <a href="{{ route('mental.articles.show', $a->id) }}"
               class="bg-white rounded-[2rem] p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-rose-50 hover:shadow-xl transition-all duration-300 flex flex-col justify-between h-full transform hover:-translate-y-2">
                
                <div class="space-y-5">
                    {{-- Header strip --}}
                    <div class="flex items-center justify-between">
                        @if($a->category === 'mental')
                            <span class="text-xs font-bold px-3.5 py-1.5 bg-pink-50 text-pink-600 rounded-full flex items-center gap-1">
                                <span class="text-sm"><x-heroicon-o-light-bulb class="w-5 h-5 inline-block mr-1 -mt-1" /></span> สุขภาพจิต
                            </span>
                        @elseif($a->category === 'physical')
                            <span class="text-xs font-bold px-3.5 py-1.5 bg-blue-50 text-blue-600 rounded-full flex items-center gap-1">
                                <span class="text-sm"><x-heroicon-o-heart class="w-5 h-5 inline-block mr-1 -mt-1" /></span> สุขภาพกาย
                            </span>
                        @else
                            <span class="text-xs font-bold px-3.5 py-1.5 bg-green-50 text-green-600 rounded-full flex items-center gap-1">
                                <x-heroicon-o-shield-check class="w-4 h-4" /> การป้องกัน
                            </span>
                        @endif
                        <span class="text-[11px] font-bold text-gray-400 flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-full">
                            <x-heroicon-o-clock class="w-3.5 h-3.5" /> {{ $a->read_time }}
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="space-y-3">
                        <h3 class="text-[1.1rem] font-bold text-gray-800 group-hover:text-rose-600 transition-colors leading-snug line-clamp-2">
                            {{ $a->title }}
                        </h3>
                        <p class="text-gray-500 text-[13px] leading-relaxed line-clamp-3 font-medium">
                            {{ $a->desc }}
                        </p>
                    </div>
                </div>

                {{-- Footer / Author --}}
                <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-lg shadow-sm border border-gray-100">
                            {{ $a->icon }}
                        </div>
                        <span class="text-[11px] text-gray-500 font-bold max-w-[120px] truncate">ผู้เขียน: {{ $a->author }}</span>
                    </div>
                    <span class="text-[12px] font-bold text-white bg-gray-900 px-3 py-1.5 rounded-xl group-hover:bg-rose-500 transition-colors flex items-center gap-1 shadow-sm">
                        อ่านต่อ
                        <svg class="w-3 h-3 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                </div>
            </a>
            
            @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin', 'mental_officer', 'volunteer']))
            <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('mental-officer.articles.edit', $a->id) }}" class="p-2 bg-white/90 backdrop-blur text-blue-600 hover:text-blue-700 rounded-full shadow-md hover:bg-blue-50 transition-colors">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                </a>
                <form action="{{ route('mental-officer.articles.destroy', $a->id) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบบทความนี้?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 bg-white/90 backdrop-blur text-red-600 hover:text-red-700 rounded-full shadow-md hover:bg-red-50 transition-colors">
                        <x-heroicon-o-trash class="w-5 h-5" />
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>

</div>

@endsection
