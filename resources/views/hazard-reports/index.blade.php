@extends('layouts.app')
@section('title', 'รายงานภัยพิบัติ')
@section('page-title')
    <x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /> รายงานภัยในชุมชน
@endsection
@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800">รายงานภัยพิบัติและสิ่งกีดขวาง</h1>
            <p class="text-sm text-gray-500 mt-1">
                ติดตามจุดอันตราย น้ำท่วม ทางขาด ดินถล่ม หรือสิ่งกีดขวางในพื้นที่ของคุณ
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('hazard-reports.create') }}" class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm">
                <x-heroicon-o-plus class="w-5 h-5 inline-block mr-1 -mt-1" /> รายงานจุดอันตราย
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $r)
            @php
                $badgeColor = match($r->type) {
                    'flood' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'landslide' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'fire' => 'bg-red-50 text-red-700 border-red-200',
                    default => 'bg-orange-50 text-orange-700 border-orange-200'
                };
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-all">
                <div>
                    @if($r->photo)
                        <img src="{{ asset('storage/' . $r->photo) }}" alt="{{ $r->type_label }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-orange-50 to-red-50 flex items-center justify-center text-4xl">
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" />
                        </div>
                    @endif

                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-block px-2.5 py-1 rounded-full border text-xs font-semibold {{ $badgeColor }}">
                                {{ $r->type_label }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $r->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <div class="font-bold text-gray-800 leading-tight">
                            {{ $r->province ?? 'ไม่ระบุจังหวัด' }}
                        </div>
                        
                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                            {{ $r->description ?? 'ไม่มีคำอธิบายเพิ่มเติม' }}
                        </p>
                    </div>
                </div>

                <div class="p-5 border-t border-gray-50 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-1.5">
                        @if($r->verified)
                            <span class="text-xs text-green-600 font-semibold flex items-center gap-0.5">
                                <x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> ยืนยันแล้ว
                            </span>
                        @else
                            <span class="text-xs text-gray-400 font-medium">
                                <x-heroicon-o-clock class="w-5 h-5 inline-block mr-1 -mt-1" /> รอติตตาม
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('hazard-reports.show', $r) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 transition-colors">
                            ดูรายละเอียด
                        </a>
                        @if($r->latitude && $r->longitude)
                            <a href="https://www.google.com/maps?q={{ $r->latitude }},{{ $r->longitude }}" target="_blank" class="px-3 py-1.5 bg-orange-50 hover:bg-orange-100 text-orange-700 text-xs font-bold rounded-lg transition-colors">
                                <x-heroicon-o-map class="w-5 h-5 inline-block mr-1 -mt-1" />️ นำทาง
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-gray-100 rounded-2xl p-12 text-center text-gray-400">
                <div class="text-4xl mb-3"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></div>
                <div class="text-sm">ไม่มีรายงานภัยในชุมชน ณ ขณะนี้</div>
            </div>
        @endforelse
    </div>

    @if($reports->hasPages())
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    @endif
</div>

@endsection
