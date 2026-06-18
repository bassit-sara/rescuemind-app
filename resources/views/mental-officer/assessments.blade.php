@extends('layouts.admin')
@section('title', 'รายการประเมินสุขภาพจิต')
@section('page-title', '🧠 รายการประเมินสุขภาพจิต')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ผู้ใช้</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ประเภท</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">คะแนน</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ระดับ</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">วันที่</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($assessments as $a)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-medium text-gray-800">{{ $a->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $a->user->email }}</div>
                    </td>
                    <td class="px-5 py-4 text-sm font-medium text-gray-700">{{ strtoupper($a->type) }}</td>
                    <td class="px-5 py-4 font-bold text-gray-800">{{ $a->score }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            {{ $a->severity == 'severe' ? 'bg-red-100 text-red-700' : ($a->severity == 'moderate' ? 'bg-orange-100 text-orange-700' : ($a->severity == 'mild' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')) }}">
                            {{ $a->severity_label }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-500">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-4">
                        <a href="{{ route('mental-officer.assessments.show', $a) }}" class="px-3 py-1.5 bg-purple-100 text-purple-700 text-xs rounded-lg hover:bg-purple-200 font-medium">ดูผล</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $assessments->links() }}
    </div>
</div>
@endsection
