@extends('layouts.admin')
@section('title', 'เฝ้าระวังศูนย์พักพิงและโรงพยาบาล')
@section('page-title')
    <x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /> Shelter & Hospital Capacity Monitoring
@endsection
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-800">ระบบติดตามขีดความสามารถโรงพยาบาลและศูนย์พักพิง</h1>
            <p class="text-sm text-gray-500 mt-1">
                ติดตามจำนวนผู้ลี้ภัย อัตราครองเตียง และจุดอพยพทั่วทุกจังหวัดแบบเรียลไทม์
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.relief-points.create') }}" class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2">
                <x-heroicon-o-plus class="w-5 h-5 inline-block shrink-0" /> เพิ่มจุดช่วยเหลือ
            </a>
            <a href="{{ route('super-admin.dashboard') }}" class="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2">
                <x-heroicon-o-globe-alt class="w-5 h-5 inline-block shrink-0" /> กลับแดชบอร์ดหลัก
            </a>
        </div>
    </div>

    {{-- Shelters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-teal-50">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" /> ศูนย์พักพิงผู้ประสบภัย (Shelters)</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($shelters as $s)
                @php
                    $cap = max(1, $s->capacity);
                    $occ = $s->current_occupancy ?? 0;
                    $percent = min(100, round(($occ / $cap) * 100));
                    $color = $percent >= 90 ? 'bg-red-500' : ($percent >= 70 ? 'bg-orange-500' : 'bg-green-500');
                @endphp
                <div class="p-4 border border-gray-100 rounded-xl space-y-3 relative group">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">{{ $s->province }}</span>
                            <h3 class="font-bold text-gray-800 mt-1">{{ $s->name }}</h3>
                        </div>
                        <span class="text-xs font-bold text-gray-500">{{ $occ }} / {{ $cap }} คน</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="{{ $color }} h-full transition-all" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400">
                        <span>ติดต่อ: {{ $s->phone ?? '-' }}</span>
                        <span class="font-semibold text-gray-600">ใช้งานแล้ว {{ $percent }}%</span>
                    </div>
                    
                    {{-- Edit/Delete Actions --}}
                    <div class="absolute top-4 right-4 hidden group-hover:flex items-center gap-2 bg-white/90 backdrop-blur px-2 py-1 rounded-lg border border-gray-100 shadow-sm transition-opacity">
                        <a href="{{ route('admin.relief-points.edit', $s) }}" class="text-blue-600 hover:text-blue-800" title="แก้ไข">
                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                        </a>
                        <form action="{{ route('admin.relief-points.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="ลบ">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-400 text-sm">
                    ไม่มีศูนย์พักพิงที่เปิดทำการในขณะนี้
                </div>
            @endforelse
        </div>
    </div>

    {{-- Hospitals --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-pink-50">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" /> จุดบริการการแพทย์และสาธารณสุข (Hospitals)</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($hospitals as $h)
                @php
                    $beds = $h->available_beds ?? 0;
                    $icu = $h->has_icu ? 'มีบริการ ICU' : 'ไม่มี ICU';
                    $amb = $h->ambulance_count ?? 0;
                @endphp
                <div class="p-4 border border-gray-100 rounded-xl flex flex-col justify-between relative group">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold">{{ $h->province }}</span>
                            <h3 class="font-bold text-gray-800 mt-1">{{ $h->name }}</h3>
                        </div>
                        <span class="text-xs px-2.5 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg border border-blue-100">
                            เตียงว่าง: {{ $beds }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-4 text-xs text-gray-500 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                        <div><x-heroicon-o-home class="w-5 h-5 inline-block mr-1 -mt-1" /> ห้องฉุกเฉิน / ICU: <span class="font-bold text-gray-700">{{ $icu }}</span></div>
                        <div><x-heroicon-o-truck class="w-5 h-5 inline-block mr-1 -mt-1" /> รถพยาบาลสแตนด์บาย: <span class="font-bold text-gray-700">{{ $amb }} คัน</span></div>
                        <div class="col-span-2 pt-1"><x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /> ติดต่อด่วน: <span class="font-bold text-gray-700">{{ $h->phone ?? '-' }}</span></div>
                    </div>
                    
                    {{-- Edit/Delete Actions --}}
                    <div class="absolute top-4 right-4 hidden group-hover:flex items-center gap-2 bg-white/90 backdrop-blur px-2 py-1 rounded-lg border border-gray-100 shadow-sm transition-opacity">
                        <a href="{{ route('admin.relief-points.edit', $h) }}" class="text-blue-600 hover:text-blue-800" title="แก้ไข">
                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                        </a>
                        <form action="{{ route('admin.relief-points.destroy', $h) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="ลบ">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-400 text-sm">
                    ไม่มีหน่วยแพทย์บริการในระบบขณะนี้
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
