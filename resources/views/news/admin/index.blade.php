@extends('layouts.admin')

@section('title', 'จัดการข่าวสาร')
@section('page-title', '⚙️ จัดการข่าวสาร')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">จัดการข่าวสาร</h2>
            <p class="text-gray-500 text-sm">เพิ่ม แก้ไข และลบข่าวสารที่เผยแพร่บนระบบ</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition shadow-sm">
            ➕ เพิ่มข่าวสาร
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">หัวข้อ</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">หมวด</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">ผู้โพสต์</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">วันที่</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">สถานะ</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($news as $item)
                @php $color = \App\Models\News::categoryColor($item->category); @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-800 line-clamp-1">
                            @if($item->is_pinned)<span class="text-yellow-500 mr-1">📌</span>@endif
                            {{ $item->title }}
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                            {{ \App\Models\News::categoryLabel($item->category) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-500 text-xs">{{ $item->author->name }}</td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-400 text-xs">{{ $item->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($item->is_published)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">เผยแพร่</span>
                        @else
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">ฉบับร่าง</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('news.show', $item) }}" class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">👁️</a>
                            <a href="{{ route('admin.news.edit', $item) }}" class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">✏️</a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('ลบข่าวนี้?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-xs font-medium bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-gray-400">ยังไม่มีข่าวสาร คลิก "เพิ่มข่าวสาร" เพื่อเริ่มต้น</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-50">{{ $news->links() }}</div>
    </div>
</div>
@endsection
