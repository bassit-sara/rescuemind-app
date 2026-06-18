@extends(auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer']) ? 'layouts.admin' : 'layouts.app')
@section('title', 'จุดช่วยเหลือและที่พักพิง')
@section('page-title', '🏕️ จุดช่วยเหลือและที่พักพิง')
@section('content')

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="province" class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            <option value="">ทุกจังหวัด</option>
            @foreach($provinces as $p)
            <option value="{{ $p }}" {{ request('province') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        <select name="type" class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            <option value="">ทุกประเภท</option>
            <option value="shelter" {{ request('type') == 'shelter' ? 'selected' : '' }}>🏠 ที่พักพิง</option>
            <option value="medical" {{ request('type') == 'medical' ? 'selected' : '' }}>🏥 จุดพยาบาล</option>
            <option value="food" {{ request('type') == 'food' ? 'selected' : '' }}>🍱 แจกอาหาร</option>
            <option value="water" {{ request('type') == 'water' ? 'selected' : '' }}>💧 แจกน้ำ</option>
        </select>
        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">ค้นหา</button>
    </form>
</div>

{{-- Stats Bar --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-green-50 rounded-xl p-4 text-center">
        <div class="text-2xl font-black text-green-600">{{ $reliefPoints->total() }}</div>
        <div class="text-xs text-green-700">จุดทั้งหมด</div>
    </div>
    <div class="bg-blue-50 rounded-xl p-4 text-center">
        <div class="text-2xl font-black text-blue-600">{{ $reliefPoints->where('type', 'shelter')->count() }}</div>
        <div class="text-xs text-blue-700">ที่พักพิง</div>
    </div>
    <div class="bg-red-50 rounded-xl p-4 text-center">
        <div class="text-2xl font-black text-red-600">{{ $reliefPoints->where('type', 'medical')->count() }}</div>
        <div class="text-xs text-red-700">จุดพยาบาล</div>
    </div>
</div>

{{-- Relief Points List --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse($reliefPoints as $rp)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
        <div class="flex items-start justify-between mb-3">
            <div>
                <span class="text-2xl">{{ $rp->type == 'shelter' ? '🏠' : ($rp->type == 'medical' ? '🏥' : ($rp->type == 'food' ? '🍱' : '💧')) }}</span>
                <h3 class="font-bold text-gray-800 mt-1">{{ $rp->name }}</h3>
                <div class="text-sm text-gray-500">{{ $rp->province }}</div>
            </div>
            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $rp->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $rp->is_active ? '✅ เปิด' : '❌ ปิด' }}
            </span>
        </div>

        @if($rp->address)
        <div class="text-sm text-gray-600 mb-3">📍 {{ $rp->address }}</div>
        @endif

        @if($rp->capacity)
        <div class="mb-3">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>ความจุ</span>
                <span>{{ $rp->current_count ?? 0 }} / {{ $rp->capacity }} คน</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                @php $pct = $rp->capacity > 0 ? min(100, round(($rp->current_count ?? 0) / $rp->capacity * 100)) : 0; @endphp
                <div class="h-2 rounded-full {{ $pct > 80 ? 'bg-red-500' : ($pct > 60 ? 'bg-orange-500' : 'bg-green-500') }}"
                     style="width: {{ $pct }}%"></div>
            </div>
        </div>
        @endif

        <div class="flex gap-2 mt-3">
            @if($rp->latitude && $rp->longitude)
            <a href="https://www.google.com/maps?q={{ $rp->latitude }},{{ $rp->longitude }}" target="_blank"
               class="flex-1 py-2 text-center text-sm font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                🗺️ นำทาง
            </a>
            @endif
            @if($rp->contact_phone)
            <a href="tel:{{ $rp->contact_phone }}" class="flex-1 py-2 text-center text-sm font-semibold bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                📞 โทร
            </a>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-2 text-center py-20 text-gray-400">
        <div class="text-5xl mb-4">🏕️</div>
        <div class="text-xl font-medium">ไม่พบจุดช่วยเหลือ</div>
        <p class="text-sm mt-2">ลองเปลี่ยนตัวกรองการค้นหา</p>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $reliefPoints->withQueryString()->links() }}</div>
@endsection
