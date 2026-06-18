@extends('layouts.admin')
@section('title', 'จัดการแจ้งเตือน')
@section('page-title', '🚨 จัดการแจ้งเตือนภัย')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">การแจ้งเตือนทั้งหมด</h2>
            <p class="text-gray-500 text-sm mt-1">สร้าง แก้ไข และจัดการการแจ้งเตือนภัยพิบัติ</p>
        </div>
        <a href="{{ route('admin.alerts.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg text-sm font-medium">
            ➕ สร้างแจ้งเตือนใหม่
        </a>
    </div>

    {{-- Summary stats --}}
    @php
        $activeAlerts = App\Models\Alert::where('is_active', true)->count();
        $level3 = App\Models\Alert::where('is_active', true)->where('level', 3)->count();
        $level2 = App\Models\Alert::where('is_active', true)->where('level', 2)->count();
        $level1 = App\Models\Alert::where('is_active', true)->where('level', 1)->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="text-2xl mb-1">🚨</div>
            <div class="text-2xl font-bold text-gray-800">{{ $activeAlerts }}</div>
            <div class="text-xs text-gray-500">แจ้งเตือนที่ใช้งาน</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-red-100 shadow-sm">
            <div class="text-2xl mb-1">🔴</div>
            <div class="text-2xl font-bold text-red-600">{{ $level3 }}</div>
            <div class="text-xs text-gray-500">อพยพทันที</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-orange-100 shadow-sm">
            <div class="text-2xl mb-1">🟠</div>
            <div class="text-2xl font-bold text-orange-500">{{ $level2 }}</div>
            <div class="text-xs text-gray-500">เตรียมอพยพ</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-yellow-100 shadow-sm">
            <div class="text-2xl mb-1">🟡</div>
            <div class="text-2xl font-bold text-yellow-500">{{ $level1 }}</div>
            <div class="text-xs text-gray-500">เฝ้าระวัง</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">ระดับ</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">หัวข้อ</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">ประเภทภัย</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">จังหวัด</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">สถานะ</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">ออกเมื่อ</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($alerts as $alert)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$alert->is_active ? 'opacity-50' : '' }}">
                        <td class="px-4 py-3">
                            @php
                                $levelColors = [1 => 'bg-yellow-100 text-yellow-700', 2 => 'bg-orange-100 text-orange-700', 3 => 'bg-red-100 text-red-700'];
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $levelColors[$alert->level] ?? 'bg-gray-100' }}">
                                ระดับ {{ $alert->level }} — {{ $alert->level_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $alert->title }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $alert->disaster_label }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $alert->province ?? 'ทั่วประเทศ' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($alert->is_active)
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">เปิด</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">ปิด</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $alert->issued_at ? $alert->issued_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.alerts.edit', $alert) }}"
                                   class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-600 transition-colors" title="แก้ไข">
                                    ✏️
                                </a>
                                @if($alert->is_active)
                                <form action="{{ route('admin.alerts.destroy', $alert) }}" method="POST"
                                      onsubmit="return confirm('ต้องการยกเลิกการแจ้งเตือนนี้?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-red-600 transition-colors" title="ยกเลิก">
                                        ❌
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                            <div class="text-3xl mb-2">🚨</div>
                            <p>ยังไม่มีการแจ้งเตือน</p>
                            <a href="{{ route('admin.alerts.create') }}" class="text-red-600 hover:underline text-sm">สร้างแจ้งเตือนใหม่ →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($alerts->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $alerts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
