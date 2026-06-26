@extends('layouts.admin')
@section('title', 'จัดการรายชื่ออาสาสมัคร (MT3)')
@section('page-title')
    <x-heroicon-o-users class="w-5 h-5 inline-block shrink-0" /> จัดการรายชื่ออาสาสมัคร
@endsection
@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h2 class="font-bold text-gray-800">รายชื่ออาสาสมัครฟื้นฟูทั้งหมด</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ชื่ออาสาสมัคร</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ทักษะ/ความเชี่ยวชาญ</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">พื้นที่ปฏิบัติงาน</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">สถานะการอนุมัติ</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($volunteers as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500">#VL-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $item->name ?? $item->user->name ?? 'ไม่พบข้อมูลผู้ใช้' }}</div>
                            <div class="text-xs text-gray-500">{{ $item->phone ?? $item->user->phone ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @php
                                $skillsArr = json_decode($item->skills, true);
                                $skillsStr = is_array($skillsArr) && count($skillsArr) > 0 ? implode(', ', $skillsArr) : ($item->skills ?? '-');
                            @endphp
                            {{ $skillsStr }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->province ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($item->approval_status === 'approved')
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                                    <x-heroicon-s-check-circle class="w-3.5 h-3.5 inline-block mr-0.5 -mt-0.5" /> อนุมัติแล้ว
                                </span>
                            @elseif($item->approval_status === 'rejected')
                                <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold border border-red-100">
                                    <x-heroicon-s-x-circle class="w-3.5 h-3.5 inline-block mr-0.5 -mt-0.5" /> ไม่อนุมัติ
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">
                                    <x-heroicon-s-clock class="w-3.5 h-3.5 inline-block mr-0.5 -mt-0.5" /> รอการอนุมัติ
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <!-- View Button -->
                            <button type="button" onclick="viewDetails('{{ addslashes($item->name ?? $item->user->name ?? 'ไม่พบข้อมูล') }}', '{{ addslashes($item->phone ?? $item->user->phone ?? '-') }}', '{{ addslashes($item->volunteer_type ?? 'อาสาสมัครรายบุคคล') }}', '{{ addslashes($item->province ?? '-') }}', '{{ addslashes($skillsStr) }}', '{{ addslashes($item->duration_days ?? '1') }}')" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                <x-heroicon-o-eye class="w-3 h-3" /> ดูข้อมูล
                            </button>
                            
                            <!-- Approve Button -->
                            <form action="{{ route('super-admin.mt3.volunteer.approval', $item->id) }}" method="POST" class="inline-block" onsubmit="confirmApproval(event, this, 'approved')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="approval_status" value="approved">
                                <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors inline-flex items-center gap-1">
                                    <x-heroicon-o-check class="w-3 h-3" /> อนุมัติ
                                </button>
                            </form>
                            
                            <!-- Reject Button -->
                            <form action="{{ route('super-admin.mt3.volunteer.approval', $item->id) }}" method="POST" class="inline-block" onsubmit="confirmApproval(event, this, 'rejected')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="approval_status" value="rejected">
                                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                    <x-heroicon-o-x-mark class="w-3 h-3" /> ไม่อนุมัติ
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <x-heroicon-o-inbox class="w-12 h-12 mx-auto text-gray-300 mb-3" />
                            <p>ยังไม่มีข้อมูลอาสาสมัครในระบบ</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
function viewDetails(name, phone, type, province, skills, duration) {
    Swal.fire({
        title: 'ข้อมูลอาสาสมัคร',
        html: `
            <div class="text-left space-y-3 mt-4 text-[15px] p-4 bg-gray-50 rounded-xl border border-gray-100">
                <p class="flex items-start border-b border-gray-200 pb-2"><span class="font-bold w-1/3 text-gray-700">ชื่อ-นามสกุล:</span> <span class="w-2/3 text-gray-600">${name}</span></p>
                <p class="flex items-start border-b border-gray-200 pb-2"><span class="font-bold w-1/3 text-gray-700">เบอร์โทรศัพท์:</span> <span class="w-2/3 text-gray-600">${phone}</span></p>
                <p class="flex items-start border-b border-gray-200 pb-2"><span class="font-bold w-1/3 text-gray-700">รูปแบบ:</span> <span class="w-2/3 text-gray-600">${type}</span></p>
                <p class="flex items-start border-b border-gray-200 pb-2"><span class="font-bold w-1/3 text-gray-700">ระยะเวลา:</span> <span class="w-2/3 text-gray-600">${duration} วัน</span></p>
                <p class="flex items-start border-b border-gray-200 pb-2"><span class="font-bold w-1/3 text-gray-700">จังหวัด:</span> <span class="w-2/3 text-gray-600">${province}</span></p>
                <p class="flex items-start"><span class="font-bold w-1/3 text-gray-700">ทักษะ:</span> <span class="w-2/3 text-gray-600 leading-relaxed">${skills}</span></p>
            </div>
        `,
        confirmButtonColor: '#3b82f6',
        confirmButtonText: 'ปิด'
    });
}

function confirmApproval(event, form, type) {
    event.preventDefault();
    let textStr = type === 'approved' ? 'คุณต้องการยืนยันการอนุมัติอาสาสมัครท่านนี้ใช่หรือไม่?' : 'คุณต้องการปฏิเสธอาสาสมัครท่านนี้ใช่หรือไม่?';
    let confirmBtnText = type === 'approved' ? 'ยืนยันอนุมัติ' : 'ยืนยันไม่อนุมัติ';
    let confirmBtnColor = type === 'approved' ? '#10b981' : '#ef4444';
    
    Swal.fire({
        title: 'ยืนยันการดำเนินการ',
        text: textStr,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: confirmBtnColor,
        cancelButtonColor: '#6b7280',
        confirmButtonText: confirmBtnText,
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endpush