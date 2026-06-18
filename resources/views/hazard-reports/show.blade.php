@extends('layouts.app')
@section('title', 'รายละเอียดรายงานภัย')
@section('page-title', '⚠️ รายละเอียดจุดอันตราย')
@section('content')

<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('hazard-reports.index') }}" class="inline-flex items-center gap-1 text-sm text-orange-600 hover:underline">
        ← กลับไปที่รายการรายงานภัยทั้งหมด
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            {{-- Image / Icon Column --}}
            <div class="bg-gray-50 flex items-center justify-center min-h-[300px] relative">
                @if($hazardReport->photo)
                    <img src="{{ asset('storage/' . $hazardReport->photo) }}" alt="{{ $hazardReport->type_label }}" class="w-full h-full object-cover">
                @else
                    <div class="text-7xl">⚠️</div>
                @endif
                <span class="absolute top-4 left-4 inline-block px-3 py-1.5 rounded-full border text-xs font-bold bg-white text-gray-800 border-gray-200">
                    {{ $hazardReport->type_label }}
                </span>
            </div>

            {{-- Description Column --}}
            <div class="p-6 sm:p-8 flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">
                            แจ้งเมื่อ {{ $hazardReport->created_at->format('d/m/Y H:i') }} ({{ $hazardReport->created_at->diffForHumans() }})
                        </span>
                        @if($hazardReport->verified)
                            <span class="text-xs px-2.5 py-1 bg-green-50 text-green-700 rounded-full font-semibold border border-green-100">
                                ✅ ยืนยันแล้ว
                            </span>
                        @else
                            <span class="text-xs px-2.5 py-1 bg-yellow-50 text-yellow-700 rounded-full font-medium border border-yellow-100">
                                ⏳ รอติตตามตรวจสอบ
                            </span>
                        @endif
                    </div>

                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">
                        {{ $hazardReport->province ?? 'ไม่ได้ระบุจังหวัด' }}
                    </h1>

                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $hazardReport->description ?? 'ไม่มีรายละเอียดคำอธิบายเพิ่มเติม' }}
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>ผู้แจ้งรายงาน:</span>
                        <span class="font-semibold text-gray-700">{{ $hazardReport->reporter->name ?? 'ผู้ใช้ทั่วไป' }}</span>
                    </div>
                    @if($hazardReport->latitude && $hazardReport->longitude)
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>พิกัด GPS:</span>
                            <span class="font-semibold text-gray-700">{{ $hazardReport->latitude }}, {{ $hazardReport->longitude }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Map Visualization (if coordinates exist) --}}
    @if($hazardReport->latitude && $hazardReport->longitude)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-4">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                🗺️ ตำแหน่งพิกัดพื้นที่เสี่ยง
            </h2>
            <div id="map" class="w-full h-80 rounded-xl overflow-hidden border border-gray-100"></div>
            <div class="flex gap-2">
                <a href="https://www.google.com/maps?q={{ $hazardReport->latitude }},{{ $hazardReport->longitude }}" target="_blank"
                   class="w-full py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold text-center rounded-xl transition-colors text-sm shadow-sm">
                    🗺️ นำทางผ่าน Google Maps
                </a>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var lat = {{ $hazardReport->latitude }};
                var lng = {{ $hazardReport->longitude }};
                
                var map = L.map('map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                var marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup("<b>จุดเกิดภัย: {{ $hazardReport->type_label }}</b><br>{{ $hazardReport->province }}")
                    .openPopup();
            });
        </script>
        @endpush
    @endif
</div>

@endsection
