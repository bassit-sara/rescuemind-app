@extends(auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer', 'volunteer']) ? 'layouts.admin' : 'layouts.app')
@section('title', 'การแจ้งเตือนภัย')
@section('page-title')
    <x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /> การแจ้งเตือนภัย
@endsection
@section('content')
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('mt1') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-red-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ย้อนกลับ
        </a>
    </div>

@forelse($alerts as $alert)
<a href="{{ route('alerts.show', $alert) }}" class="block rounded-2xl p-5 mb-3 transition-all hover:shadow-md
    {{ $alert->level == 3 ? 'bg-red-50 border-l-4 border-red-500' : ($alert->level == 2 ? 'bg-orange-50 border-l-4 border-orange-500' : 'bg-yellow-50 border-l-4 border-yellow-500') }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-3xl">{!! $alert->level == 3 ? '<span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-1"></span>' : ($alert->level == 2 ? '<span class="inline-block w-3 h-3 rounded-full bg-orange-500 mr-1"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-1"></span>') !!}</span>
            <div>
                <div class="font-bold text-gray-800 text-lg">{{ $alert->title }}</div>
                <div class="text-sm text-gray-600">{{ $alert->province ?? 'ทั่วประเทศ' }} • {{ $alert->disaster_label }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ $alert->issued_at?->format('d/m/Y H:i') }}</div>
            </div>
        </div>
        <span class="px-3 py-1.5 text-xs font-bold rounded-full whitespace-nowrap
            {{ $alert->level == 3 ? 'bg-red-200 text-red-800' : ($alert->level == 2 ? 'bg-orange-200 text-orange-800' : 'bg-yellow-200 text-yellow-800') }}">
            ระดับ {{ $alert->level }}: {{ $alert->level_label }}
        </span>
    </div>
</a>
@empty
<div class="text-center py-20 text-gray-400">
    <div class="text-5xl mb-4"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></div>
    <div class="text-xl font-medium">ไม่มีการแจ้งเตือนขณะนี้</div>
    <p class="text-sm mt-2">ระบบจะแจ้งเตือนทันทีเมื่อมีสถานการณ์</p>
</div>
@endforelse
@endsection
