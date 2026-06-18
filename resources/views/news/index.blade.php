@extends('layouts.app')

@section('title', 'ข่าวสาร & ประกาศ')
@section('page-title', '📰 ข่าวสาร & ประกาศ')

@push('styles')
<style>
.news-card { transition: transform .2s, box-shadow .2s; }
.news-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.1); }
.cat-badge { font-size: .7rem; font-weight: 700; padding: 2px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: .05em; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">ข่าวสาร & ประกาศจากชุมชน</h2>
            <p class="text-gray-500 text-sm mt-1">ติดตามข่าวสารภัยพิบัติ สุขภาพ และชุมชนจากผู้ใช้งานและทีมงาน</p>
        </div>
        {{-- Filter --}}
        <div class="flex flex-wrap gap-2">
            @foreach(['all' => 'ทั้งหมด', 'disaster' => '🔴 ภัยพิบัติ', 'alert' => '🟠 แจ้งเตือน', 'health' => '🟣 สุขภาพ', 'community' => '🟢 ชุมชน', 'general' => '🔵 ทั่วไป'] as $val => $label)
            <a href="{{ $val === 'all' ? route('news.index') : route('news.index', ['cat' => $val]) }}"
               class="px-3 py-1.5 text-xs font-semibold rounded-full border transition-colors
               {{ (request('cat', 'all') === $val) ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-200 hover:border-red-400 hover:text-red-600' }}">
               {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Pinned --}}
    @if($pinned->count())
    <div class="space-y-3">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
            <span>📌</span> ปักหมุดโดยทีมงาน
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($pinned as $p)
            @php $color = \App\Models\News::categoryColor($p->category); @endphp
            <a href="{{ route('news.show', $p) }}" class="news-card block bg-gradient-to-br from-{{ $color }}-50 to-white border border-{{ $color }}-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <span class="cat-badge bg-{{ $color }}-100 text-{{ $color }}-700 mb-2 inline-block">{{ \App\Models\News::categoryLabel($p->category) }}</span>
                        <div class="font-bold text-gray-800 leading-snug line-clamp-2">{{ $p->title }}</div>
                        <p class="text-gray-500 text-xs mt-2 line-clamp-2">{{ strip_tags($p->body) }}</p>
                    </div>
                    <span class="text-2xl">📌</span>
                </div>
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                    <div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-[10px]">
                        {{ strtoupper(substr($p->author->name, 0, 1)) }}
                    </div>
                    {{ $p->author->name }} · {{ $p->created_at->diffForHumans() }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- News Grid --}}
    @if($news->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($news as $item)
        @php $color = \App\Models\News::categoryColor($item->category); @endphp
        <a href="{{ route('news.show', $item) }}" class="news-card block bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
            @if($item->image_url)
            <div class="h-40 bg-gray-100 overflow-hidden">
                <img src="{{ Str::startsWith($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}" class="w-full h-full object-cover" onerror="this.closest('.h-40').classList.add('hidden')">
            </div>
            @endif
            <div class="p-5">
                <span class="cat-badge bg-{{ $color }}-100 text-{{ $color }}-700 mb-2 inline-block">{{ \App\Models\News::categoryLabel($item->category) }}</span>
                <div class="font-bold text-gray-800 leading-snug line-clamp-2 mb-2">{{ $item->title }}</div>
                <p class="text-gray-500 text-xs line-clamp-3 leading-relaxed">{{ strip_tags($item->body) }}</p>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-gray-400">
                        <div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-[10px]">
                            {{ strtoupper(substr($item->author->name, 0, 1)) }}
                        </div>
                        <span>{{ $item->author->name }}</span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    {{ $news->links() }}
    @else
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-3">📭</div>
        <p class="font-medium">ยังไม่มีข่าวสารในหมวดนี้</p>
    </div>
    @endif
</div>
@endsection
