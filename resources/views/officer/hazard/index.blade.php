@extends('layouts.admin')
@section('title', 'จัดการรายงานจุดเสี่ยงภัย')
@section('page-title', '⚠️ ตรวจสอบจุดอันตราย & กีดขวาง')
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-orange-50 to-red-50">
            <div>
                <h1 class="text-xl font-bold text-gray-800">จัดการข้อมูลรายงานภัยชุมชน</h1>
                <p class="text-sm text-gray-500 mt-1">
                    ตรวจสอบรายงานสิ่งกีดขวาง จุดอันตราย และยืนยันข้อมูลเพื่อแจ้งเตือนประชาชนในพื้นที่
                </p>
            </div>
            <span class="text-xs bg-orange-100 text-orange-700 font-semibold px-3 py-1.5 rounded-full">
                ทั้งหมด {{ $reports->total() }} รายการ
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-4">รูปภาพ</th>
                        <th class="px-6 py-4">ประเภทเหตุ</th>
                        <th class="px-6 py-4">จังหวัด / พื้นที่</th>
                        <th class="px-6 py-4">คำอธิบายรายละเอียด</th>
                        <th class="px-6 py-4">สถานะการตรวจสอบ</th>
                        <th class="px-6 py-4 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($reports as $r)
                        @php
                            $badgeColor = match($r->type) {
                                'flood' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'landslide' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'fire' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-orange-50 text-orange-700 border-orange-200'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                @if($r->photo)
                                    <img src="{{ asset('storage/' . $r->photo) }}" alt="{{ $r->type_label }}" class="w-14 h-14 rounded-lg object-cover border border-gray-100">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center text-xl text-gray-400 border border-gray-100">
                                        ⚠️
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-semibold {{ $badgeColor }}">
                                    {{ $r->type_label }}
                                </span>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $r->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $r->province ?? 'ไม่ระบุจังหวัด' }}</div>
                                @if($r->latitude && $r->longitude)
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $r->latitude }}, {{ $r->longitude }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-600 max-w-xs break-words">
                                    {{ $r->description ?? 'ไม่มีรายละเอียดเพิ่มเติม' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($r->verified)
                                    <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-semibold bg-green-50 text-green-700 border-green-200">
                                        ✅ ยืนยันแล้ว
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-medium bg-yellow-50 text-yellow-700 border-yellow-200">
                                        ⏳ รอติตตามตรวจสอบ
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($r->latitude && $r->longitude)
                                        <a href="https://www.google.com/maps?q={{ $r->latitude }},{{ $r->longitude }}" target="_blank" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 transition-colors">
                                            🗺️ แผนที่
                                        </a>
                                    @endif

                                    @if(!$r->verified)
                                        <form action="{{ route('officer.hazard.verify', $r) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                                ✅ ยืนยันรายงาน
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-3xl mb-2">✅</div>
                                <div class="text-sm">ไม่มีคำขอยืนยันรายงานเหตุภัยพิบัติใดๆ ในขณะนี้</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
