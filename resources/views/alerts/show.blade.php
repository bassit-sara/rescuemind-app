@extends(auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer']) ? 'layouts.admin' : 'layouts.app')
@section('title', 'แจ้งเตือน: '.$alert->title)
@section('page-title', '🚨 รายละเอียดการแจ้งเตือน')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="rounded-3xl overflow-hidden shadow-sm border
        {{ $alert->level == 3 ? 'border-red-200' : ($alert->level == 2 ? 'border-orange-200' : 'border-yellow-200') }}">

        {{-- Header --}}
        <div class="p-8
            {{ $alert->level == 3 ? 'bg-gradient-to-br from-red-500 to-red-700' : ($alert->level == 2 ? 'bg-gradient-to-br from-orange-500 to-orange-700' : 'bg-gradient-to-br from-yellow-500 to-yellow-600') }}
            text-white">
            <div class="text-5xl mb-4">{{ $alert->level == 3 ? '🔴' : ($alert->level == 2 ? '🟠' : '🟡') }}</div>
            <div class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80">
                ระดับ {{ $alert->level }} • {{ $alert->level_label }} • {{ $alert->disaster_label }}
            </div>
            <h1 class="text-2xl font-black mb-2">{{ $alert->title }}</h1>
            <div class="flex items-center gap-4 text-sm opacity-90">
                <span>📍 {{ $alert->province ?? 'ทั่วประเทศ' }}</span>
                <span>🕐 {{ $alert->issued_at?->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div class="bg-white p-8">
            {{-- Description --}}
            <div class="prose max-w-none mb-8">
                <h3 class="text-gray-800 font-bold text-lg mb-3">รายละเอียด</h3>
                <p class="text-gray-600 leading-relaxed">{{ $alert->description }}</p>
            </div>

            @if($alert->instructions)
            <div class="p-5 bg-blue-50 rounded-2xl mb-6">
                <h3 class="font-bold text-blue-800 mb-3">📋 คำแนะนำและวิธีปฏิบัติ</h3>
                <div class="text-blue-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $alert->instructions }}</div>
            </div>
            @endif

            @if($alert->affected_areas)
            <div class="p-5 bg-orange-50 rounded-2xl mb-6">
                <h3 class="font-bold text-orange-800 mb-3">⚠️ พื้นที่ที่ได้รับผลกระทบ</h3>
                <p class="text-orange-700 text-sm">{{ $alert->affected_areas }}</p>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="grid grid-cols-2 gap-3">
                @if(auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'officer']))
                    <a href="{{ route('officer.sos.index', ['province' => $alert->province]) }}" class="py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-center transition-colors">
                        🚨 ลงพื้นที่ช่วยเหลือ
                    </a>
                @else
                    <a href="{{ route('sos.create') }}" class="py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-center transition-colors">
                        🆘 ขอความช่วยเหลือ
                    </a>
                @endif
                
                <a href="{{ route('relief-points.index') }}" class="py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-center transition-colors">
                    🏕️ จุดช่วยเหลือ
                </a>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('alerts.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← กลับหน้าแจ้งเตือนทั้งหมด</a>
            </div>
        </div>
    </div>
</div>
@endsection
