@extends('layouts.admin')
@section('title', 'เพิ่มบทความใหม่')
@section('page-title')
    <x-heroicon-o-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> เพิ่มบทความสุขภาพจิตใหม่
@endsection

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
    <div class="mb-8">
        <a href="{{ route('mental.articles') }}" class="text-sm text-gray-500 hover:text-purple-600 transition-colors flex items-center gap-1">
            <x-heroicon-o-arrow-left class="w-4 h-4" /> กลับไปหน้ารวมบทความ
        </a>
    </div>

    <form action="{{ route('mental-officer.articles.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">หัวข้อบทความ <span class="text-red-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="ระบุหัวข้อบทความ">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">หมวดหมู่ <span class="text-red-500">*</span></label>
                <select name="category" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="mental" {{ old('category') == 'mental' ? 'selected' : '' }}>สุขภาพจิต</option>
                    <option value="physical" {{ old('category') == 'physical' ? 'selected' : '' }}>สุขภาพกาย</option>
                    <option value="prevention" {{ old('category') == 'prevention' ? 'selected' : '' }}>การป้องกัน</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">คำอธิบายแบบย่อ (แสดงบนการ์ด) <span class="text-red-500">*</span></label>
            <textarea name="desc" rows="2" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="คำอธิบายสั้นๆ...">{{ old('desc') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">เวลาที่ใช้กะประมาณ (เช่น 5 นาที)</label>
                <input type="text" name="read_time" value="{{ old('read_time') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="5 นาที">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">ผู้เขียน / แหล่งที่มา</label>
                <input type="text" name="author" value="{{ old('author') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="นพ. วรุตม์...">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">ไอคอน (Emoji)</label>
                <input type="text" name="icon" value="{{ old('icon') ?? '📚' }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="📚">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">URL วิดีโอ YouTube (ถ้ามี)</label>
            <input type="text" name="video_url" value="{{ old('video_url') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="https://www.youtube.com/embed/...">
            <span class="text-xs text-gray-500 mt-1 block">ใช้ลิงก์แบบ Embed เช่น https://www.youtube.com/embed/xxxxx</span>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">เนื้อหาบทความ (HTML) <span class="text-red-500">*</span></label>
            <textarea name="content" required rows="12" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 font-mono text-sm" placeholder="<p>เนื้อหา...</p>">{{ old('content') }}</textarea>
            <span class="text-xs text-gray-500 mt-1 block">รองรับการเขียน HTML เช่น &lt;p&gt;, &lt;strong&gt;, &lt;h4&gt;, &lt;ul&gt;</span>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('mental.articles') }}" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition-colors">
                ยกเลิก
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 text-white font-bold hover:bg-purple-700 transition-colors shadow-md">
                บันทึกบทความ
            </button>
        </div>
    </form>
</div>
@endsection
