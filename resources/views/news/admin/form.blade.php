@extends('layouts.admin')

@section('title', $news ? 'แก้ไขข่าวสาร' : 'เพิ่มข่าวสาร')
@section('page-title')
    @if($news)
        <x-heroicon-o-pencil class="w-5 h-5 inline-block mr-1 -mt-1" /> แก้ไขข่าวสาร
    @else
        <x-heroicon-o-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> เพิ่มข่าวสาร
    @endif
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $news ? 'แก้ไขข่าวสาร' : 'เพิ่มข่าวสารใหม่' }}</h2>
            <p class="text-gray-500 text-sm mt-1">ข่าวสารที่เผยแพร่จะปรากฎบนหน้าข่าวสารสาธารณะ</p>
        </div>

        <form action="{{ $news ? route('admin.news.update', $news) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if($news) @method('PUT') @endif

            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">หัวข้อข่าวสาร <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 @error('title') border-red-400 @enderror"
                       placeholder="หัวข้อข่าวหรือประกาศ...">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">หมวดหมู่ <span class="text-red-500">*</span></label>
                <select name="category" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                    @foreach(['general' => 'ทั่วไป', 'disaster' => 'ภัยพิบัติ', 'alert' => 'แจ้งเตือน', 'health' => 'สุขภาพ', 'community' => 'ชุมชน'] as $val => $label)
                    <option value="{{ $val }}" {{ old('category', $news->category ?? 'general') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Body --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">เนื้อหา <span class="text-red-500">*</span></label>
                <textarea name="body" rows="10"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-red-300 @error('body') border-red-400 @enderror"
                          placeholder="รายละเอียดข่าวสาร...">{{ old('body', $news->body ?? '') }}</textarea>
                @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Media Upload --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">อัปโหลดสื่อ (รูปภาพ หรือ วิดีโอ)</label>
                <input type="file" name="media" accept="image/*,video/mp4,video/avi,video/quicktime"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                <p class="text-xs text-gray-500 mt-1">อัปโหลดรูปภาพ (สูงสุด 10MB) หรือวิดีโอ (สูงสุด 50MB) 1 ไฟล์ หากอัปโหลดใหม่จะแทนที่สื่อเดิม</p>
                
                @if($news && $news->image_url)
                    <div class="mt-3 relative inline-block">
                        <img src="{{ Str::startsWith($news->image_url, 'http') ? $news->image_url : asset('storage/' . $news->image_url) }}" alt="Current Image" class="h-32 w-auto rounded-lg object-cover border border-gray-200 shadow-sm">
                        <span class="absolute top-2 right-2 bg-black/60 text-white text-[10px] font-bold px-2 py-1 rounded shadow"><x-heroicon-o-camera class="w-5 h-5 inline-block mr-1 -mt-1" /> รูปปัจจุบัน</span>
                    </div>
                @endif
                
                @if($news && $news->video_url)
                    <div class="mt-3 relative inline-block">
                        <video src="{{ asset('storage/' . $news->video_url) }}" class="h-32 w-auto rounded-lg bg-black object-cover shadow-sm" controls preload="metadata"></video>
                        <span class="absolute top-2 right-2 bg-black/60 text-white text-[10px] font-bold px-2 py-1 rounded shadow"><x-heroicon-o-video-camera class="w-5 h-5 inline-block mr-1 -mt-1" /> วิดีโอปัจจุบัน</span>
                    </div>
                @endif
                @error('media')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Toggles --}}
            <div class="flex flex-wrap gap-6 pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_pinned" value="1" class="sr-only peer" {{ old('is_pinned', $news->is_pinned ?? false) ? 'checked' : '' }}>
                        <div class="w-10 h-6 bg-gray-200 peer-checked:bg-yellow-400 rounded-full transition-colors"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> ปักหมุดที่ด้านบน</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', $news->is_published ?? true) ? 'checked' : '' }}>
                        <div class="w-10 h-6 bg-gray-200 peer-checked:bg-green-500 rounded-full transition-colors"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> เผยแพร่ทันที</span>
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition shadow-sm">
                    @if($news)
                        <x-heroicon-o-circle-stack class="w-5 h-5 inline-block mr-1 -mt-1" /> บันทึกการแก้ไข
                    @else
                        <x-heroicon-o-paper-airplane class="w-5 h-5 inline-block mr-1 -mt-1" /> เผยแพร่ข่าวสาร
                    @endif
                </button>
                <a href="{{ route('admin.news.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
