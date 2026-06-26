@extends('layouts.admin')

@section('page-title')
    <x-heroicon-o-home class="w-5 h-5 inline-block shrink-0" /> จัดการคำขอจองที่พักพิง
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">จัดการคำขอจองที่พักพิง (Admin)</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-2">
            <x-heroicon-s-check-circle class="w-5 h-5" />
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm">
                        <th class="py-4 px-6 font-semibold">ผู้ขอจอง</th>
                        <th class="py-4 px-6 font-semibold">ที่พักพิง</th>
                        <th class="py-4 px-6 font-semibold">จำนวนคน</th>
                        <th class="py-4 px-6 font-semibold">ข้อมูลเพิ่มเติม</th>
                        <th class="py-4 px-6 font-semibold">สถานะ</th>
                        <th class="py-4 px-6 font-semibold">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900">{{ $booking->reliefPoint->name }}</div>
                                <div class="text-xs text-gray-500">จ.{{ $booking->reliefPoint->province }}</div>
                            </td>
                            <td class="py-4 px-6 text-gray-700">
                                {{ $booking->number_of_people }} คน
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                <div><span class="font-semibold text-gray-700">จาก:</span> {{ Str::limit($booking->current_address, 30) }}</div>
                                @if($booking->additional_info)
                                    <div><span class="font-semibold text-gray-700">เพิ่ม:</span> {{ Str::limit($booking->additional_info, 30) }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($booking->status === 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-bold text-xs inline-flex items-center gap-1">
                                        <x-heroicon-s-check-circle class="w-3 h-3" /> อนุมัติ
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1 font-mono">{{ $booking->room_key }}</div>
                                @elseif($booking->status === 'rejected')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full font-bold text-xs inline-flex items-center gap-1">
                                        <x-heroicon-s-x-circle class="w-3 h-3" /> ปฏิเสธ
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full font-bold text-xs inline-flex items-center gap-1">
                                        <x-heroicon-s-clock class="w-3 h-3" /> รอตรวจสอบ
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($booking->status === 'pending')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="p-2 bg-green-100 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors" title="อนุมัติ">
                                                <x-heroicon-s-check class="w-5 h-5" />
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="p-2 bg-red-100 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors" title="ปฏิเสธ">
                                                <x-heroicon-s-x-mark class="w-5 h-5" />
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                ยังไม่มีรายการคำขอจองที่พักพิง
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
