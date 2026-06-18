@extends(auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer']) ? 'layouts.admin' : 'layouts.app')
@section('title', 'การแจ้งเตือนภัย')
@section('page-title', '🚨 การแจ้งเตือนภัย')
@section('content')
@forelse($alerts as $alert)
<a href="{{ route('alerts.show', $alert) }}" class="block rounded-2xl p-5 mb-3 transition-all hover:shadow-md
    {{ $alert->level == 3 ? 'bg-red-50 border-l-4 border-red-500' : ($alert->level == 2 ? 'bg-orange-50 border-l-4 border-orange-500' : 'bg-yellow-50 border-l-4 border-yellow-500') }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-3xl">{{ $alert->level == 3 ? '🔴' : ($alert->level == 2 ? '🟠' : '🟡') }}</span>
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
    <div class="text-5xl mb-4">✅</div>
    <div class="text-xl font-medium">ไม่มีการแจ้งเตือนขณะนี้</div>
    <p class="text-sm mt-2">ระบบจะแจ้งเตือนทันทีเมื่อมีสถานการณ์</p>
</div>
@endforelse
@endsection
