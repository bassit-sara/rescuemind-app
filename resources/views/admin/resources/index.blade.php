@extends('layouts.admin')
@section('title', 'จัดการทรัพยากร')
@section('page-title', '📦 ระบบจัดการคลังทรัพยากรช่วยเหลือ')
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800">รายการอุปกรณ์และทรัพยากร</h1>
            <p class="text-sm text-gray-500 mt-1">
                บริหารจัดการสิ่งของช่วยเหลือ ยานพาหนะ ยา และอาหาร สำหรับผู้ประสบภัย
            </p>
        </div>
        <a href="{{ route('admin.resources.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm">
            ➕ เพิ่มทรัพยากรใหม่
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-4">ชื่อทรัพยากร / อุปกรณ์</th>
                        <th class="px-6 py-4">ประเภท</th>
                        <th class="px-6 py-4">จังหวัดที่จัดเก็บ</th>
                        <th class="px-6 py-4">จำนวนทั้งหมด</th>
                        <th class="px-6 py-4">จำนวนที่ว่าง / พร้อมใช้</th>
                        <th class="px-6 py-4 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($resources as $res)
                        @php
                            $badgeColor = match($res->type) {
                                'boat' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'truck' => 'bg-teal-50 text-teal-700 border-teal-200',
                                'medicine' => 'bg-red-50 text-red-700 border-red-200',
                                'food' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'water' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                default => 'bg-gray-50 text-gray-700 border-gray-200'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $res->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $res->location ?? 'ไม่มีข้อมูลสถานที่เก็บ' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-semibold {{ $badgeColor }}">
                                    {{ $res->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $res->province ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $res->quantity }} {{ $res->unit ?? 'หน่วย' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($res->available_quantity > 0)
                                    <span class="text-green-600 font-bold">
                                        {{ $res->available_quantity }} {{ $res->unit ?? 'หน่วย' }}
                                    </span>
                                @else
                                    <span class="text-red-500 font-bold">หมดชั่วคราว</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.resources.edit', $res) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 transition-colors">
                                        ✏️ แก้ไข
                                    </a>
                                    <form action="{{ route('admin.resources.destroy', $res) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบทรัพยากรนี้?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg transition-colors border border-red-100">
                                            🗑️ ลบ
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-3xl mb-2">📦</div>
                                <div class="text-sm">ไม่มีทรัพยากรช่วยเหลือลงระบบในขณะนี้</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($resources->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $resources->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
