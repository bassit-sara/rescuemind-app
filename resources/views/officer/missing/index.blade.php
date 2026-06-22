@extends('layouts.admin')
@section('title', 'จัดการข้อมูลคนหาย')
@section('page-title')
    <x-heroicon-o-magnifying-glass class="w-5 h-5 inline-block mr-1 -mt-1" /> รายการแจ้งคนหาย (สำหรับเจ้าหน้าที่)
@endsection
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-red-50 to-orange-50">
            <div>
                <h1 class="text-xl font-bold text-gray-800 font-bold">จัดการข้อมูลผู้สูญหาย</h1>
                <p class="text-sm text-gray-500 mt-1">
                    ตรวจสอบและปรับปรุงสถานะข้อมูลคนหายเพื่อให้ความช่วยเหลือในระดับพื้นที่
                </p>
            </div>
            <span class="text-xs bg-red-100 text-red-700 font-semibold px-3 py-1.5 rounded-full">
                ทั้งหมด {{ $missingPeople->total() }} เคส
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-4">รูปผู้สูญหาย</th>
                        <th class="px-6 py-4">ข้อมูลผู้สูญหาย</th>
                        <th class="px-6 py-4">พื้นที่ล่าสุด / วันที่หาย</th>
                        <th class="px-6 py-4">ผู้แจ้งรายงาน / ติดต่อ</th>
                        <th class="px-6 py-4">สถานะปัจจุบัน</th>
                        <th class="px-6 py-4 text-right">ปรับปรุงสถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($missingPeople as $person)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                @if($person->photo)
                                    <img src="{{ asset('storage/' . $person->photo) }}" alt="{{ $person->name }}" class="w-14 h-14 rounded-lg object-cover border border-gray-100">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center text-xl text-gray-400 border border-gray-100">
                                        <x-heroicon-o-user class="w-5 h-5 inline-block shrink-0" />
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $person->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    อายุ {{ $person->age ?? 'ไม่ระบุ' }} ปี • เพศ: {{ ['male'=>'ชาย','female'=>'หญิง','other'=>'อื่นๆ'][$person->gender] ?? 'ไม่ระบุ' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $person->province ?? 'ไม่ได้ระบุ' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $person->last_seen_at ? $person->last_seen_at->format('d/m/Y H:i') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $person->reporter->name ?? 'ผู้ใช้ทั่วไป' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $person->reporter->phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClass = match($person->status) {
                                        'searching' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'found' => 'bg-green-50 text-green-700 border-green-200',
                                        'safe' => 'bg-teal-50 text-teal-700 border-teal-200',
                                        default => 'bg-red-50 text-red-700 border-red-200'
                                    };
                                    $statusLabel = match($person->status) {
                                        'searching' => 'กำลังค้นหา',
                                        'found' => 'พบตัวแล้ว',
                                        'safe' => 'ปลอดภัยแล้ว',
                                        default => 'สูญหาย'
                                    };
                                @endphp
                                <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('officer.missing.status', $person) }}" method="POST" class="inline-flex gap-1">
                                    @csrf @method('PATCH')
                                    <select name="status" class="text-xs border border-gray-300 rounded-lg px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-red-500">
                                        <option value="missing" {{ $person->status=='missing'?'selected':'' }}>สูญหาย</option>
                                        <option value="searching" {{ $person->status=='searching'?'selected':'' }}>กำลังค้นหา</option>
                                        <option value="found" {{ $person->status=='found'?'selected':'' }}>พบตัวแล้ว</option>
                                        <option value="safe" {{ $person->status=='safe'?'selected':'' }}>ปลอดภัยแล้ว</option>
                                    </select>
                                    <button type="submit" class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                        อัปเดต
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-3xl mb-2"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></div>
                                <div class="text-sm">ไม่มีคำขอค้นหาคนหายในขณะนี้</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($missingPeople->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $missingPeople->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
