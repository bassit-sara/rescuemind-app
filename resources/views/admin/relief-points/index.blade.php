@extends('layouts.admin')
@section('title', 'จัดการจุดช่วยเหลือ')
@section('page-title')
    <x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" /> จัดการจุดช่วยเหลือ
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">จุดช่วยเหลือทั้งหมด</h2>
            <p class="text-gray-500 text-sm mt-1">จัดการศูนย์พักพิง โรงพยาบาล ศูนย์อาหาร และจุดอพยพ</p>
        </div>
        <a href="{{ route('admin.relief-points.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg text-sm font-medium">
            <x-heroicon-o-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> เพิ่มจุดช่วยเหลือ
        </a>
    </div>

    {{-- Stats --}}
    @php
        $allPoints = App\Models\ReliefPoint::all();
        $shelters = $allPoints->where('type','shelter')->where('is_active', true);
        $hospitals = $allPoints->where('type','hospital')->where('is_active', true);
        $foods = $allPoints->where('type','food')->where('is_active', true);
        $parkings = $allPoints->where('type','parking')->where('is_active', true);
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="text-2xl mb-1"><x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /></div>
            <div class="text-2xl font-bold text-blue-600">{{ $shelters->count() }}</div>
            <div class="text-xs text-gray-500">ศูนย์พักพิง</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="text-2xl mb-1"><x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" /></div>
            <div class="text-2xl font-bold text-red-600">{{ $hospitals->count() }}</div>
            <div class="text-xs text-gray-500">โรงพยาบาล</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="text-2xl mb-1"><x-heroicon-o-cube class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
            <div class="text-2xl font-bold text-orange-500">{{ $foods->count() }}</div>
            <div class="text-xs text-gray-500">ศูนย์อาหาร</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="text-2xl mb-1">🅿️</div>
            <div class="text-2xl font-bold text-green-600">{{ $parkings->count() }}</div>
            <div class="text-xs text-gray-500">จุดจอดรถอพยพ</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">ชื่อ</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">ประเภท</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">จังหวัด</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">ความจุ</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">ผู้อยู่ปัจจุบัน</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">สถานะ</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reliefPoints ?? App\Models\ReliefPoint::orderBy('type')->paginate(20) as $point)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $point->name }}</div>
                            @if($point->phone)
                            <div class="text-xs text-gray-400"><x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /> {{ $point->phone }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $point->type === 'shelter' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $point->type === 'hospital' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $point->type === 'food' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $point->type === 'parking' ? 'bg-green-100 text-green-700' : '' }}
                            ">
                                {{ $point->type_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $point->province ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ number_format($point->capacity) }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($point->capacity > 0)
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-gray-700 font-medium">{{ number_format($point->current_occupancy) }}</span>
                                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $point->occupancy_percent > 80 ? 'bg-red-500' : ($point->occupancy_percent > 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                             style="width: {{ min($point->occupancy_percent, 100) }}%"></div>
                                    </div>
                                </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($point->is_active)
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">เปิด</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">ปิด</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.relief-points.edit', $point) }}"
                                   class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-600 transition-colors" title="แก้ไข">
                                    <x-heroicon-o-pencil class="w-5 h-5 inline-block mr-1 -mt-1" />️
                                </a>
                                <form action="{{ route('admin.relief-points.destroy', $point) }}" method="POST"
                                      onsubmit="return confirm('ต้องการปิดจุดช่วยเหลือนี้?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-red-600 transition-colors" title="ปิดการใช้งาน">
                                        <x-heroicon-o-trash class="w-5 h-5 inline-block mr-1 -mt-1" />️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                            <div class="text-3xl mb-2"><x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                            <p>ยังไม่มีจุดช่วยเหลือ</p>
                            <a href="{{ route('admin.relief-points.create') }}" class="text-blue-600 hover:underline text-sm">เพิ่มจุดช่วยเหลือ →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($reliefPoints) && $reliefPoints instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $reliefPoints->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
