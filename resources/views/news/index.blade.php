@extends('layouts.app')

@section('title', 'ข่าวสาร & ประกาศ')
@section('page-title')
    <x-heroicon-o-newspaper class="w-5 h-5 inline-block shrink-0" /> ข่าวสาร & ประกาศ
@endsection

@push('styles')
<style>
.news-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.news-card:hover { transform: translateY(-4px); }
.cat-badge { font-size: 0.7rem; font-weight: 800; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; }
.cat-badge { font-size: .7rem; font-weight: 700; padding: 2px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: .05em; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Header Hero --}}
    <div class="relative bg-gradient-to-br from-teal-600 via-emerald-600 to-indigo-800 rounded-[2rem] p-8 sm:p-12 text-white shadow-xl shadow-teal-900/10 overflow-hidden mb-6">
        <x-heroicon-o-newspaper class="absolute -right-12 -bottom-16 w-64 h-64 text-white opacity-10 transform -rotate-12" />
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold tracking-wider uppercase mb-4 border border-white/20">
                <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span> อัปเดตล่าสุด
            </div>
            <h2 class="text-3xl sm:text-5xl font-black mb-4 tracking-tight">ข่าวสาร & ประกาศจากชุมชน</h2>
            <p class="text-teal-50 text-[15px] sm:text-lg font-medium max-w-2xl leading-relaxed">อัปเดตสถานการณ์ภัยพิบัติ เคล็ดลับการดูแลสุขภาพ และเรื่องราวดีๆ จากชุมชน เพื่อเตรียมความพร้อมและก้าวผ่านทุกวิกฤตไปด้วยกัน</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex flex-wrap items-center gap-3 mb-10 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-2 px-3 border-r border-gray-200">
            <x-heroicon-o-funnel class="w-5 h-5 text-gray-400" />
            <span class="text-xs font-black text-gray-500 uppercase tracking-widest">หมวดหมู่</span>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach([
                'all' => ['label' => 'ทั้งหมด', 'icon' => 'o-squares-2x2'], 
                'disaster' => ['label' => 'ภัยพิบัติ', 'icon' => 's-exclamation-triangle'], 
                'alert' => ['label' => 'แจ้งเตือน', 'icon' => 's-bell-alert'], 
                'health' => ['label' => 'สุขภาพ', 'icon' => 's-heart'], 
                'community' => ['label' => 'ชุมชน', 'icon' => 's-users'], 
                'general' => ['label' => 'ทั่วไป', 'icon' => 's-information-circle']
            ] as $val => $data)
            <a href="{{ $val === 'all' ? route('news.index') : route('news.index', ['cat' => $val]) }}"
               class="px-4 py-2 text-[13px] font-bold rounded-xl transition-all flex items-center gap-1.5
               {{ (request('cat', 'all') === $val) ? 'bg-gray-900 text-white shadow-md transform scale-105' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
               @if($val === 'all')
                   <x-heroicon-o-squares-2x2 class="w-4 h-4 {{ (request('cat', 'all') === $val) ? 'text-gray-300' : 'text-gray-400' }}" />
               @elseif($val === 'disaster')
                   <x-heroicon-s-exclamation-triangle class="w-4 h-4 text-red-500" />
               @elseif($val === 'alert')
                   <x-heroicon-s-bell-alert class="w-4 h-4 text-orange-500" />
               @elseif($val === 'health')
                   <x-heroicon-s-heart class="w-4 h-4 text-purple-500" />
               @elseif($val === 'community')
                   <x-heroicon-s-users class="w-4 h-4 text-green-500" />
               @else
                   <x-heroicon-s-information-circle class="w-4 h-4 text-blue-500" />
               @endif
               {{ $data['label'] }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Pinned --}}
    @if($pinned->count())
    <div class="space-y-4 mb-10">
        <h3 class="text-sm font-black text-gray-800 flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-500">
                <x-heroicon-s-map-pin class="w-4 h-4" />
            </div>
            ปักหมุดโดยทีมงาน
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($pinned as $p)
            @php $color = \App\Models\News::categoryColor($p->category); @endphp
            <a href="{{ route('news.show', $p) }}" class="news-card relative group block bg-white border border-gray-200 rounded-[1.5rem] p-6 sm:p-7 shadow-sm hover:shadow-xl hover:border-orange-300 overflow-hidden z-0">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-orange-50 rounded-full -z-10 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="absolute top-6 right-6">
                    <x-heroicon-s-map-pin class="w-6 h-6 text-orange-400 group-hover:text-orange-500 transition-colors transform group-hover:rotate-12" />
                </div>
                
                <div class="pr-10">
                    <span class="cat-badge bg-{{ $color }}-100 text-{{ $color }}-700 mb-4 inline-block shadow-sm">{{ \App\Models\News::categoryLabel($p->category) }}</span>
                    <h4 class="text-xl font-black text-gray-900 leading-snug line-clamp-2 mb-3 group-hover:text-orange-600 transition-colors">{{ $p->title }}</h4>
                    <p class="text-gray-500 text-sm mt-2 line-clamp-2 leading-relaxed">{{ strip_tags($p->body) }}</p>
                </div>
                
                <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-2.5 text-xs font-bold text-gray-500">
                        <div class="w-7 h-7 bg-gradient-to-br from-gray-700 to-gray-900 rounded-full flex items-center justify-center text-white text-[10px] shadow-sm">
                            {{ strtoupper(substr($p->author->name, 0, 1)) }}
                        </div>
                        <span>{{ $p->author->name }}</span>
                    </div>
                    <span class="text-[11px] font-bold text-orange-500 bg-orange-50 px-2.5 py-1 rounded-lg">{{ $p->created_at->diffForHumans() }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- News Grid --}}
    @if($news->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($news as $item)
        @php $color = \App\Models\News::categoryColor($item->category); @endphp
        <a href="{{ route('news.show', $item) }}" class="news-card group flex flex-col bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
            @if($item->image_url)
            <div class="h-48 bg-gray-100 overflow-hidden relative">
                <img src="{{ Str::startsWith($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.closest('.h-48').classList.add('hidden')">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>
            @endif
            <div class="p-6 flex-1 flex flex-col">
                <div>
                    <span class="cat-badge bg-{{ $color }}-100 text-{{ $color }}-700 mb-4 inline-block shadow-sm">{{ \App\Models\News::categoryLabel($item->category) }}</span>
                    <h3 class="text-lg font-black text-gray-900 leading-snug line-clamp-2 mb-3 group-hover:text-teal-600 transition-colors">{{ $item->title }}</h3>
                    <p class="text-gray-500 text-[13px] line-clamp-3 leading-relaxed mb-6">{{ strip_tags($item->body) }}</p>
                </div>
                <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
                        <div class="w-6 h-6 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center text-white text-[9px] shadow-sm">
                            {{ strtoupper(substr($item->author->name, 0, 1)) }}
                        </div>
                        <span class="truncate max-w-[120px]">{{ $item->author->name }}</span>
                    </div>
                    <span class="text-[11px] font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">{{ $item->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    {{ $news->links() }}
    @else
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-3"><x-heroicon-o-inbox class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
        <p class="font-medium">ยังไม่มีข่าวสารในหมวดนี้</p>
    </div>
    @endif
</div>
@endsection
