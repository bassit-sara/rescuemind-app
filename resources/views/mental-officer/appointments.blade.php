@extends('layouts.admin')
@section('title', 'จัดการการนัดหมาย')
@section('page-title', '📅 ตารางการนัดหมายผู้เชี่ยวชาญ')
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-purple-50 to-indigo-50">
            <div>
                <h1 class="text-xl font-bold text-gray-800">นัดหมายปรึกษาสุขภาพจิต</h1>
                <p class="text-sm text-gray-500 mt-1">
                    ตารางเวลานัดหมายดูแลสุขภาพจิตของประชาชนในพื้นที่รับผิดชอบ
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs bg-purple-100 text-purple-700 font-semibold px-3 py-1.5 rounded-full">
                    ทั้งหมด {{ $appointments->total() }} รายการ
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-4">ผู้ประเมิน / ข้อมูลติดต่อ</th>
                        <th class="px-6 py-4">ประเภทนัดหมาย</th>
                        <th class="px-6 py-4">วันและเวลานัดหมาย</th>
                        <th class="px-6 py-4">รายละเอียด / บันทึกเพิ่มเติม</th>
                        <th class="px-6 py-4">สถานะ</th>
                        <th class="px-6 py-4 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($appointments as $appt)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $appt->user->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $appt->user->phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($appt->type == 'video')
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-100 font-semibold">
                                        💻 ออนไลน์ (VDO Call)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 bg-teal-50 text-teal-700 rounded-full border border-teal-100 font-semibold">
                                        🏥 พบหน้า (Onsite)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $appt->scheduled_at?->format('d/m/Y H:i') ?? 'รอยืนยันเวลา' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-600 max-w-xs break-words">
                                    {{ $appt->notes ?? 'ไม่มีคำอธิบายเพิ่มเติม' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClass = match($appt->status) {
                                        'confirmed' => 'bg-green-50 text-green-700 border-green-200',
                                        'completed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-yellow-50 text-yellow-700 border-yellow-200'
                                    };
                                    $statusLabel = match($appt->status) {
                                        'confirmed' => 'ยืนยันแล้ว',
                                        'completed' => 'เสร็จสิ้น',
                                        'cancelled' => 'ยกเลิก',
                                        default => 'รอยืนยัน'
                                    };
                                @endphp
                                <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-medium {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($appt->status == 'pending')
                                        <form action="{{ route('mental-officer.appointments.status', $appt) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                                ยืนยันนัด
                                            </button>
                                        </form>
                                    @endif

                                    @if($appt->status == 'confirmed')
                                        <form action="{{ route('mental-officer.appointments.status', $appt) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                                เสร็จสิ้นภารกิจ
                                            </button>
                                        </form>
                                    @endif

                                    @if($appt->status != 'cancelled' && $appt->status != 'completed')
                                        <form action="{{ route('mental-officer.appointments.status', $appt) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg transition-colors border border-red-200">
                                                ยกเลิกนัด
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-3xl mb-2">📅</div>
                                <div class="text-sm">ไม่มีคำขอนัดหมายในขณะนี้</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
