@extends('layouts.app')

@section('title', $news->title)
@section('page-title', '📰 ข่าวสาร')

@section('content')
@php $color = \App\Models\News::categoryColor($news->category); @endphp
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back --}}
    <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-red-600 transition-colors">
        ← กลับไปยังรายการข่าวสาร
    </a>

    {{-- Article --}}
    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($news->video_url)
        <div class="w-full bg-black">
            <video src="{{ asset('storage/' . $news->video_url) }}" class="w-full h-auto max-h-[500px]" controls preload="metadata"></video>
        </div>
        @elseif($news->image_url)
        <div class="w-full h-56 sm:h-72 bg-gray-100 overflow-hidden">
            <img src="{{ Str::startsWith($news->image_url, 'http') ? $news->image_url : asset('storage/' . $news->image_url) }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
        </div>
        @endif
        <div class="p-6 lg:p-8">
            {{-- Category & Meta --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700 uppercase tracking-wide">
                    {{ \App\Models\News::categoryLabel($news->category) }}
                </span>
                @if($news->is_pinned)
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">📌 ปักหมุด</span>
                @endif
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 leading-snug mb-4">{{ $news->title }}</h1>

            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                <div class="w-9 h-9 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr($news->author->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-700">{{ $news->author->name }}</div>
                    <div class="text-xs text-gray-400">{{ $news->created_at->format('d M Y · H:i') }}</div>
                </div>
                @hasanyrole('admin|super_admin')
                <div class="ml-auto flex gap-2">
                    <a href="{{ route('admin.news.edit', $news) }}" class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">✏️ แก้ไข</a>
                    <form action="{{ route('admin.news.destroy', $news) }}" method="POST" onsubmit="return confirm('ยืนยันการลบข่าวนี้?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">🗑️ ลบ</button>
                    </form>
                </div>
                @endhasanyrole
            </div>

            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed space-y-4">
                {!! nl2br(e($news->body)) !!}
            </div>
        </div>
    </article>

    {{-- Comments --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">💬 ความคิดเห็น ({{ $news->comments->count() }})</h3>

        @auth
        <form action="{{ route('news.comment', $news) }}" method="POST" class="mb-6">
            @csrf
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0 mt-1">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <textarea name="body" rows="2" placeholder="แสดงความคิดเห็น..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-300"></textarea>
                    @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <button type="submit" class="mt-2 px-4 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition">ส่งความคิดเห็น</button>
                </div>
            </div>
        </form>
        @else
        <p class="text-sm text-gray-500 mb-4"><a href="{{ route('login') }}" class="text-red-600 font-medium hover:underline">เข้าสู่ระบบ</a> เพื่อแสดงความคิดเห็น</p>
        @endauth

        <div class="space-y-4">
            @forelse($news->comments as $comment)
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                    {{ strtoupper(substr($comment->author->name, 0, 1)) }}
                </div>
                <div class="flex-1 bg-gray-50 rounded-xl px-4 py-3">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="text-xs font-semibold text-gray-700">{{ $comment->author->name }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            @if(auth()->id() === $comment->user_id || (auth()->check() && auth()->user()->hasAnyRole(['admin','super_admin'])))
                            <form action="{{ route('news.comment.destroy', $comment) }}" method="POST" onsubmit="return confirm('ลบความคิดเห็นนี้?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">ลบ</button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->body }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">ยังไม่มีความคิดเห็น เป็นคนแรกที่แสดงความคิดเห็นได้เลย!</p>
            @endforelse
        </div>
    </div>

    {{-- Related --}}
    @if($related->count())
    <div>
        <h3 class="font-bold text-gray-700 mb-3">📎 ข่าวที่เกี่ยวข้อง</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($related as $r)
            <a href="{{ route('news.show', $r) }}" class="bg-white rounded-xl border border-gray-100 p-4 hover:shadow-md transition">
                <div class="font-semibold text-sm text-gray-800 line-clamp-2">{{ $r->title }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ $r->created_at->diffForHumans() }}</div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
