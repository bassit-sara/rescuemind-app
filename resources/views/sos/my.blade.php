@extends('layouts.app')
@section('title', 'SOS ของฉัน')
@section('page-title', '📍 ติดตาม SOS ของฉัน')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('sos.create') }}" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-colors">+ ส่ง SOS ใหม่</a>
</div>
@forelse($sosRequests as $sos)
<a href="{{ route('sos.show', $sos) }}" class="block priority-{{ $sos->priority }} rounded-2xl p-4 mb-3 hover:shadow-md transition-all">
    <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 mb-1">#{{ $sos->id }} • {{ $sos->created_at->diffForHumans() }}</div>
            <div class="font-bold text-gray-800">{{ $sos->province ?? 'ไม่ระบุจังหวัด' }}</div>
            <div class="text-sm text-gray-600 line-clamp-2">{{ $sos->num_people }} คน {{ $sos->address ? '• '.$sos->address : '' }}</div>
        </div>
        <div class="text-right flex-shrink-0 mt-1">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap
                {{ $sos->status == 'safe' ? 'bg-green-100 text-green-700' : ($sos->status == 'in_progress' ? 'bg-blue-100 text-blue-700' : ($sos->status == 'pending' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                {{ $sos->status_label }}
            </span>
        </div>
    </div>
</a>
@empty
<div class="text-center py-16 text-gray-400">
    <div class="text-5xl mb-4">🕊️</div>
    <div class="text-lg font-medium">ยังไม่มีคำขอ SOS</div>
    <p class="text-sm mt-2">หากต้องการความช่วยเหลือ กดปุ่ม SOS ด้านล่าง</p>
    <a href="{{ route('sos.create') }}" class="inline-block mt-4 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">🆘 กด SOS</a>
</div>
@endforelse
{{ $sosRequests->links() }}
@endsection
