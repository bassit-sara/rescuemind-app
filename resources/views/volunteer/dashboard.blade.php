@extends('layouts.app')
@section('title', 'Volunteer Dashboard')
@section('page-title', '🙋 Volunteer Dashboard')
@section('content')

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-center">
        <div class="text-3xl font-black text-blue-600">{{ $stats['accepted'] ?? 0 }}</div>
        <div class="text-sm text-blue-700 font-medium">ภารกิจที่รับ</div>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-2xl p-5 text-center">
        <div class="text-3xl font-black text-green-600">{{ $stats['completed'] ?? 0 }}</div>
        <div class="text-sm text-green-700 font-medium">สำเร็จแล้ว</div>
    </div>
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 text-center">
        <div class="text-3xl font-black text-orange-600">{{ $stats['available'] ?? 0 }}</div>
        <div class="text-sm text-orange-700 font-medium">งานรอรับ</div>
    </div>
</div>

{{-- Weather Widget --}}
<div class="mb-6">
    <x-weather-widget />
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Available Tasks --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800">📋 งานที่ต้องการอาสา</h2>
            <a href="{{ route('volunteer.tasks') }}" class="text-sm text-blue-600 hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($availableTasks as $sos)
            <div class="priority-{{ $sos->priority }} mx-4 my-2 p-4 rounded-xl">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                {{ $sos->priority == 'critical' ? 'bg-red-100 text-red-700' : ($sos->priority == 'high' ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ strtoupper($sos->priority) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $sos->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="font-medium text-gray-800">{{ $sos->province }} • {{ $sos->num_people }} คน</div>
                        @if($sos->address)
                        <div class="text-xs text-gray-500 mt-0.5 truncate">📍 {{ $sos->address }}</div>
                        @endif
                    </div>
                    <form action="{{ route('volunteer.tasks.accept', $sos) }}" method="POST" class="ml-3">
                        @csrf
                        <button type="submit" class="px-3 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 whitespace-nowrap">รับงาน</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-gray-400">
                <div class="text-3xl mb-2">✅</div>
                <div class="text-sm">ไม่มีงานรอ</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- My Active Tasks --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800">🎯 งานของฉัน</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($myTasks as $sos)
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <div class="font-medium text-gray-800">{{ $sos->province }} • {{ $sos->num_people }} คน</div>
                        <div class="text-xs text-gray-500">{{ $sos->status_label }} • {{ $sos->updated_at->diffForHumans() }}</div>
                    </div>
                    @if($sos->latitude)
                    <a href="https://www.google.com/maps?q={{ $sos->latitude }},{{ $sos->longitude }}" target="_blank"
                       class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700">🗺️</a>
                    @endif
                </div>
                <form action="{{ route('volunteer.tasks.report', $sos) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="report" placeholder="รายงานความคืบหน้า..."
                           class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-xs">
                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700">ส่ง</button>
                </form>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-gray-400">
                <div class="text-sm">ยังไม่มีงานที่รับ</div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
