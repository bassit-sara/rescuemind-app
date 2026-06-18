@extends('layouts.app')
@section('title', $article['title'])
@section('page-title', '📖 อ่านบทความ')
@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('mental.articles') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition-colors group">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-250 dark:border-gray-750 bg-white dark:bg-gray-800 group-hover:border-purple-300 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
            ย้อนกลับไปยังบทความทั้งหมด
        </a>
    </div>

    {{-- Article Content Container --}}
    <article class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-750 overflow-hidden">
        {{-- Hero Strip with category color --}}
        <div class="p-8 md:p-12 bg-gradient-to-br from-purple-900 via-indigo-950 to-gray-900 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <div class="absolute top-10 right-10 w-64 h-64 bg-purple-500 rounded-full blur-[80px]"></div>
                <div class="absolute bottom-10 left-10 w-64 h-64 bg-indigo-500 rounded-full blur-[80px]"></div>
            </div>
            
            <div class="relative z-10 space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-semibold px-3 py-1 bg-purple-500/20 border border-purple-500/30 text-purple-300 rounded-full">
                        {{ $article['category'] === 'mental' ? '🧠 สุขภาพจิต' : ($article['category'] === 'physical' ? '🩺 สุขภาพกาย' : '🛡️ การป้องกัน') }}
                    </span>
                    <span class="text-xs text-purple-200 font-semibold flex items-center gap-1">
                        ⏱️ เวลาอ่าน: {{ $article['read_time'] }}
                    </span>
                </div>
                
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold leading-tight tracking-tight">
                    {{ $article['title'] }}
                </h1>
                
                <div class="pt-4 border-t border-white/10 flex items-center gap-3">
                    <span class="text-3xl">{{ $article['icon'] }}</span>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $article['author'] }}</p>
                        <p class="text-xs text-gray-400">บทความคำแนะนำฉบับฟื้นฟูสุขภาพภัยพิบัติ</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Article Text Body --}}
        <div class="p-8 md:p-12">
            @if(isset($article['video_url']))
            <div class="mb-8 rounded-2xl overflow-hidden aspect-video shadow-md border border-gray-150 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <iframe class="w-full h-full" src="{{ $article['video_url'] }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            @endif

            <div class="text-gray-750 dark:text-gray-300 text-base leading-relaxed space-y-6">
                {!! $article['content'] !!}
            </div>

            {{-- Article Disclaimer footer --}}
            <div class="mt-12 pt-6 border-t border-gray-100 dark:border-gray-700/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
                <p>แหล่งอ้างอิง: คลังความรู้สุขภาพจิตและการปฐมพยาบาลเบื้องต้น (RescueMind Wellness)</p>
                <p class="font-medium text-purple-600 dark:text-purple-400">พ.ร.บ. คุ้มครองข้อมูลและสาธารณสุขเพื่อประชาชน</p>
            </div>
        </div>
    </article>

    {{-- Bottom Back Navigation --}}
    <div class="flex justify-center pt-4">
        <a href="{{ route('mental.articles') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl hover:bg-purple-50 dark:hover:bg-purple-950/20 hover:border-purple-300 transition-all shadow-sm text-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            ย้อนกลับไปยังหน้าบทความทั้งหมด
        </a>
    </div>

</div>

@endsection
