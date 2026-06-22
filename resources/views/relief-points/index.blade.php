@extends(auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer', 'volunteer']) ? 'layouts.admin' : 'layouts.app')
@section('title', 'จุดช่วยเหลือและที่พักพิง')
@section('page-title')
    <x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /> จุดช่วยเหลือและที่พักพิง
@endsection
@section('content')

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('mt1') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ย้อนกลับ
        </a>
    </div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="province" class="flex-1 min-w-[150px] px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 hover:border-blue-300 transition-colors bg-gray-50 focus:bg-white">
            <option value="">ทุกจังหวัด</option>
            @php
                $defaultProvinces = ['ยะลา', 'ปัตตานี', 'นราธิวาส', 'สงขลา', 'สตูล'];
                $allProvinces = collect($defaultProvinces)->merge($provinces ?? [])->unique()->values();
            @endphp
            @foreach($allProvinces as $p)
            <option value="{{ $p }}" {{ request('province') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        
        {{-- Custom Type Dropdown using Alpine.js for SVG support --}}
        <div x-data="{ open: false, type: '{{ request('type', '') }}' }" class="relative flex-1 min-w-[150px]">
            <input type="hidden" name="type" :value="type">
            <button type="button" @click="open = !open" @click.away="open = false" 
                    class="w-full flex items-center justify-between px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50 focus:bg-white hover:border-blue-300 transition-colors text-left">
                <span class="flex items-center gap-2">
                    <template x-if="type === ''"><span class="font-medium text-gray-700">ทุกประเภท</span></template>
                    <template x-if="type === 'shelter'"><span class="flex items-center gap-2 font-medium text-gray-700"><x-heroicon-s-home class="w-5 h-5 text-orange-500"/> ที่พักพิง</span></template>
                    <template x-if="type === 'medical'"><span class="flex items-center gap-2 font-medium text-gray-700"><x-heroicon-s-plus-circle class="w-5 h-5 text-red-500"/> จุดพยาบาล</span></template>
                    <template x-if="type === 'food'"><span class="flex items-center gap-2 font-medium text-gray-700"><x-heroicon-s-cake class="w-5 h-5 text-yellow-500"/> แจกอาหาร</span></template>
                    <template x-if="type === 'water'"><span class="flex items-center gap-2 font-medium text-gray-700"><x-heroicon-s-beaker class="w-5 h-5 text-blue-500"/> แจกน้ำ</span></template>
                </span>
                <x-heroicon-m-chevron-down class="w-4 h-4 text-gray-400 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" style="display: none;" x-transition
                 class="absolute z-20 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl py-2 text-sm max-h-60 overflow-auto">
                <button type="button" @click="type = ''; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-blue-50 transition-colors text-left">
                    <span class="font-medium text-gray-700 ml-7">ทุกประเภท</span>
                </button>
                <button type="button" @click="type = 'shelter'; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-orange-50 transition-colors text-left">
                    <x-heroicon-s-home class="w-5 h-5 text-orange-500"/> <span class="font-medium text-gray-700">ที่พักพิง</span>
                </button>
                <button type="button" @click="type = 'medical'; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-red-50 transition-colors text-left">
                    <x-heroicon-s-plus-circle class="w-5 h-5 text-red-500"/> <span class="font-medium text-gray-700">จุดพยาบาล</span>
                </button>
                <button type="button" @click="type = 'food'; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-yellow-50 transition-colors text-left">
                    <x-heroicon-s-cake class="w-5 h-5 text-yellow-500"/> <span class="font-medium text-gray-700">แจกอาหาร</span>
                </button>
                <button type="button" @click="type = 'water'; open = false" class="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-blue-50 transition-colors text-left">
                    <x-heroicon-s-beaker class="w-5 h-5 text-blue-500"/> <span class="font-medium text-gray-700">แจกน้ำ</span>
                </button>
            </div>
        </div>
        
        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 hover:shadow-md transition-all">ค้นหา</button>
    </form>
</div>

{{-- Stats Bar --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-4 text-center border border-green-200">
        <div class="text-3xl font-black text-green-600 drop-shadow-sm">{{ $reliefPoints->total() }}</div>
        <div class="text-xs font-semibold text-green-700 mt-1">จุดทั้งหมด</div>
    </div>
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-4 text-center border border-orange-200">
        <div class="text-3xl font-black text-orange-600 drop-shadow-sm">{{ $reliefPoints->where('type', 'shelter')->count() }}</div>
        <div class="text-xs font-semibold text-orange-700 mt-1">ที่พักพิง</div>
    </div>
    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-4 text-center border border-red-200">
        <div class="text-3xl font-black text-red-600 drop-shadow-sm">{{ $reliefPoints->where('type', 'medical')->count() }}</div>
        <div class="text-xs font-semibold text-red-700 mt-1">จุดพยาบาล</div>
    </div>
</div>

{{-- Relief Points List --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($reliefPoints as $rp)
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full relative overflow-hidden group">
        {{-- Status badge absolute top right --}}
        <div class="absolute top-4 right-4 z-10">
            <span class="px-3 py-1 text-xs font-bold rounded-full flex items-center gap-1 shadow-sm {{ $rp->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                @if($rp->is_active)
                    <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div> เปิด
                @else
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div> ปิด
                @endif
            </span>
        </div>

        <div class="flex items-start gap-4 mb-4 pt-2">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-inner
                @if($rp->type == 'shelter') bg-orange-100 text-orange-600
                @elseif($rp->type == 'medical') bg-red-100 text-red-600
                @elseif($rp->type == 'food') bg-yellow-100 text-yellow-600
                @else bg-blue-100 text-blue-600 @endif
            ">
                @if($rp->type == 'shelter')
                    <x-heroicon-s-home class="w-7 h-7" />
                @elseif($rp->type == 'medical')
                    <x-heroicon-s-plus-circle class="w-7 h-7" />
                @elseif($rp->type == 'food')
                    <x-heroicon-s-cake class="w-7 h-7" />
                @else
                    <x-heroicon-s-beaker class="w-7 h-7" />
                @endif
            </div>
            <div class="pr-12">
                <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $rp->name }}</h3>
                <div class="text-xs font-medium text-gray-500 mt-1 bg-gray-100 w-fit px-2 py-0.5 rounded-md">{{ $rp->province }}</div>
            </div>
        </div>

        @if($rp->address)
        <div class="text-sm text-gray-600 mb-4 flex items-start gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
            <x-heroicon-s-map-pin class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" /> 
            <span class="leading-snug">{{ $rp->address }}</span>
        </div>
        @endif

        <div class="mt-auto">
            @if($rp->capacity)
            <div class="mb-5 bg-white border border-gray-100 shadow-sm p-3 rounded-xl">
                <div class="flex justify-between text-xs font-semibold text-gray-600 mb-2">
                    <span class="flex items-center gap-1"><x-heroicon-s-users class="w-3.5 h-3.5 text-gray-400"/> ความจุพื้นที่</span>
                    <span class="{{ ($rp->current_count ?? 0) >= $rp->capacity ? 'text-red-600 font-bold' : 'text-blue-600' }}">{{ $rp->current_count ?? 0 }} <span class="text-gray-400 font-normal">/ {{ $rp->capacity }} คน</span></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden border border-gray-200/50">
                    @php $pct = $rp->capacity > 0 ? min(100, round(($rp->current_count ?? 0) / $rp->capacity * 100)) : 0; @endphp
                    <div class="h-full rounded-full transition-all duration-1000 relative {{ $pct > 90 ? 'bg-red-500' : ($pct > 75 ? 'bg-orange-500' : 'bg-green-500') }}"
                         style="width: {{ $pct }}%">
                         <div class="absolute inset-0 bg-white/20 w-full" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex gap-2 pt-2 border-t border-gray-100">
                @if($rp->latitude && $rp->longitude)
                <a href="https://www.google.com/maps?q={{ $rp->latitude }},{{ $rp->longitude }}" target="_blank"
                   class="flex-1 py-2.5 flex items-center justify-center gap-2 text-sm font-bold bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                    <x-heroicon-s-map class="w-4 h-4" /> นำทาง
                </a>
                @endif
                @if($rp->contact_phone)
                <a href="tel:{{ $rp->contact_phone }}" class="flex-1 py-2.5 flex items-center justify-center gap-2 text-sm font-bold bg-green-50 text-green-700 rounded-xl hover:bg-green-600 hover:text-white transition-all">
                    <x-heroicon-s-phone class="w-4 h-4" /> โทร
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-24 bg-white rounded-3xl border border-dashed border-gray-300">
        <div class="text-gray-300 flex justify-center mb-4"><x-heroicon-o-home-modern class="w-16 h-16" /></div>
        <div class="text-xl font-bold text-gray-700">ไม่พบจุดช่วยเหลือ</div>
        <p class="text-sm text-gray-500 mt-2">ลองเปลี่ยนตัวกรองการค้นหา หรือยังไม่มีจุดช่วยเหลือในขณะนี้</p>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $reliefPoints->withQueryString()->links() }}</div>
@endsection
