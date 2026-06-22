@extends('layouts.admin')
@section('title', 'งานช่วยเหลือทั้งหมด')
@section('page-title')
    <x-heroicon-o-user-group class="w-5 h-5 inline-block mr-1 -mt-1" /> รายการงานช่วยเหลือที่รอดำเนินการ
@endsection
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">งานช่วยเหลือภัยพิบัติ</h1>
            <p class="text-sm text-gray-500 mt-1">
                ค้นหางานกู้ชีพกู้ภัยหรือภารกิจช่วยเหลือผู้ประสบภัยที่รอดำเนินการในระบบ
            </p>
        </div>
        <a href="{{ route('volunteer.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition-colors">
            <x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" /> กลับหน้าหลักอาสาสมัคร
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-4">ความเร่งด่วน / วันที่ส่ง</th>
                        <th class="px-6 py-4">จังหวัด / พื้นที่</th>
                        <th class="px-6 py-4">จำนวนคน / ความต้องการพิเศษ</th>
                        <th class="px-6 py-4">ระดับน้ำ / สถานการณ์</th>
                        <th class="px-6 py-4 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($tasks as $task)
                        @php
                            $prioClass = match($task->priority) {
                                'critical' => 'bg-red-100 text-red-700 border-red-200',
                                'high' => 'bg-orange-100 text-orange-700 border-orange-200',
                                'medium' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                default => 'bg-green-100 text-green-700 border-green-200'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-bold uppercase {{ $prioClass }}">
                                    {{ strtoupper($task->priority) }}
                                </span>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $task->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $task->province ?? 'ไม่ได้ระบุจังหวัด' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5 max-w-xs truncate">{{ $task->address ?? 'ไม่มีที่อยู่ละเอียด' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">รวม {{ $task->num_people }} คน</div>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @if($task->has_elderly) <span class="text-[10px] bg-amber-50 border border-amber-200 text-amber-700 px-1.5 py-0.5 rounded"><x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" /> ผู้สูงอายุ</span> @endif
                                    @if($task->has_children) <span class="text-[10px] bg-blue-50 border border-blue-200 text-blue-700 px-1.5 py-0.5 rounded"><x-heroicon-o-face-smile class="w-5 h-5 inline-block mr-1 -mt-1" /> เด็กเล็ก</span> @endif
                                    @if($task->has_bedridden) <span class="text-[10px] bg-red-50 border border-red-200 text-red-700 px-1.5 py-0.5 rounded"><x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" />️ ผู้ป่วยติดเตียง</span> @endif
                                    @if($task->has_pregnant) <span class="text-[10px] bg-pink-50 border border-pink-200 text-pink-700 px-1.5 py-0.5 rounded"><x-heroicon-o-user-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> หญิงตั้งครรภ์</span> @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($task->water_level)
                                    <div class="text-xs text-blue-600 font-semibold"><x-heroicon-o-globe-asia-australia class="w-5 h-5 inline-block mr-1 -mt-1" /> ระดับน้ำ: {{ $task->water_level }}</div>
                                @endif
                                <div class="text-xs text-gray-600 mt-0.5 max-w-xs break-words">
                                    {{ Str::limit($task->description, 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($task->latitude && $task->longitude)
                                        <a href="{{ route('volunteer.tasks.navigate', $task) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 transition-colors">
                                            <x-heroicon-o-map class="w-5 h-5 inline-block mr-1 -mt-1" />️ นำทางด้วย AI
                                        </a>
                                    @endif
                                    <form action="{{ route('volunteer.tasks.accept', $task) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                            <x-heroicon-o-user-group class="w-5 h-5 inline-block mr-1 -mt-1" /> รับภารกิจนี้
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-3xl mb-2"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></div>
                                <div class="text-sm">ไม่มีคำขอ SOS ที่กำลังรอดำเนินการในพื้นที่ของคุณ</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tasks->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
