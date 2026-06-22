@extends('layouts.admin')
@section('title', 'เฝ้าระวังคลังทรัพยากรช่วยเหลือภัยพิบัติ')
@section('page-title')
    <x-heroicon-o-archive-box class="w-5 h-5 inline-block shrink-0" /> Resource Monitoring Dashboard
@endsection
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-800 font-bold">ระบบเฝ้าระวังและจัดส่งทรัพยากรส่วนกลาง</h1>
            <p class="text-sm text-gray-500 mt-1">
                ติดตามตรวจสอบทรัพยากรช่วยเหลือฉุกเฉิน ยานพาหนะ และอาหารทุกจังหวัด ทั่วประเทศ
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.resources.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2">
                <x-heroicon-o-plus class="w-5 h-5 inline-block shrink-0" /> เพิ่มทรัพยากรใหม่
            </a>
            <a href="{{ route('super-admin.dashboard') }}" class="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2">
                <x-heroicon-o-globe-alt class="w-5 h-5 inline-block shrink-0" /> กลับแดชบอร์ดหลัก
            </a>
        </div>
    </div>

    {{-- Resources Grouped by Type --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><x-heroicon-o-archive-box class="w-5 h-5 inline-block shrink-0" /> คลังทรัพยากรช่วยเหลือรายพื้นที่</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-4">จังหวัด</th>
                        <th class="px-6 py-4">รายการอุปกรณ์ / สิ่งของ</th>
                        <th class="px-6 py-4">ประเภทคลัง</th>
                        <th class="px-6 py-4">สถานที่จัดเก็บ</th>
                        <th class="px-6 py-4">จำนวนรวม</th>
                        <th class="px-6 py-4">พร้อมใช้งาน</th>
                        <th class="px-6 py-4 text-right">จัดการ</th>
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
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $res->province ?? 'ส่วนกลาง / ไม่ได้ระบุ' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $res->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-semibold {{ $badgeColor }}">
                                    {{ $res->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $res->location ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $res->quantity }} {{ $res->unit ?? 'หน่วย' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($res->available_quantity > 0)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-green-50 text-green-700 border border-green-100 rounded-lg font-bold">
                                        <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1"></span> ว่างพร้อมใช้: {{ $res->available_quantity }} {{ $res->unit }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg font-bold">
                                        <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-1"></span> หมดคลัง
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.resources.edit', $res) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="แก้ไข">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('admin.resources.destroy', $res) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบทรัพยากรนี้?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="ลบ">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-3xl mb-2"><x-heroicon-o-archive-box class="w-5 h-5 inline-block shrink-0" /></div>
                                <div class="text-sm">ไม่มีข้อมูลทรัพยากรจากจังหวัดใดส่งเข้ามาในระบบ</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
