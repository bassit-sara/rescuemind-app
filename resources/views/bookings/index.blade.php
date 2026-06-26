@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8 gap-2">
        <h1 class="text-xl sm:text-3xl font-bold text-gray-900 truncate">ประวัติการจองที่พักพิง</h1>
        <a href="{{ route('relief-points.index') }}" class="px-3 py-2 sm:px-4 sm:py-2 bg-blue-50 text-blue-600 rounded-xl text-sm sm:text-base font-semibold hover:bg-blue-100 transition-colors whitespace-nowrap shrink-0">
            หาที่พักพิงเพิ่ม
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-2">
            <x-heroicon-s-check-circle class="w-5 h-5" />
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse($bookings as $booking)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <x-heroicon-s-home class="w-6 h-6 text-orange-500" />
                            {{ $booking->reliefPoint->name }}
                        </h2>
                        <div class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                            <x-heroicon-s-map-pin class="w-4 h-4" />
                            {{ $booking->reliefPoint->address }} จ.{{ $booking->reliefPoint->province }}
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end">
                        @if($booking->status === 'approved')
                            <span class="px-4 py-1.5 bg-green-100 text-green-700 rounded-full font-bold text-sm flex items-center gap-1">
                                <x-heroicon-s-check-circle class="w-4 h-4" /> อนุมัติแล้ว
                            </span>
                        @elseif($booking->status === 'rejected')
                            <span class="px-4 py-1.5 bg-red-100 text-red-700 rounded-full font-bold text-sm flex items-center gap-1">
                                <x-heroicon-s-x-circle class="w-4 h-4" /> ปฏิเสธการจอง
                            </span>
                        @else
                            <span class="px-4 py-1.5 bg-yellow-100 text-yellow-700 rounded-full font-bold text-sm flex items-center gap-1">
                                <x-heroicon-s-clock class="w-4 h-4" /> รอการอนุมัติ
                            </span>
                        @endif
                    </div>
                </div>

                @if($booking->status === 'approved')
                <!-- ID Card Style Room Key -->
                <div class="mt-6 p-1 bg-gradient-to-br from-blue-400 via-blue-500 to-blue-700 rounded-2xl shadow-lg relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-4 sm:p-6 relative">
                        <!-- Card Header -->
                        <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center border-b border-gray-200 pb-4 mb-4 gap-4 sm:gap-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                                    <x-heroicon-s-identification class="w-6 h-6" />
                                </div>
                                <div>
                                    <div class="font-bold text-blue-900 text-base sm:text-lg leading-tight">บัตรผู้พักพิง (Shelter Pass)</div>
                                    <div class="text-[10px] sm:text-xs text-blue-600 leading-tight">RescueMind Disaster Management</div>
                                </div>
                            </div>
                            <div class="text-left sm:text-right w-full sm:w-auto bg-gray-50 sm:bg-transparent p-3 sm:p-0 rounded-lg sm:rounded-none text-center sm:text-right">
                                <div class="text-[10px] sm:text-xs text-gray-500 uppercase tracking-widest font-bold">ROOM KEY</div>
                                <div class="text-2xl sm:text-3xl font-black text-gray-900 tracking-widest font-mono">{{ $booking->room_key }}</div>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-center sm:items-start">
                            <!-- Photo -->
                            <div class="w-24 h-28 bg-gray-100 border-2 border-gray-200 rounded-lg overflow-hidden shrink-0">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <x-heroicon-s-user class="w-12 h-12" />
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-1 space-y-3 w-full">
                                <div class="text-center sm:text-left">
                                    <div class="text-xs text-gray-500 mb-0.5">ชื่อ-นามสกุล (Name)</div>
                                    <div class="font-bold text-gray-900 text-base sm:text-lg">{{ auth()->user()->name }}</div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 sm:gap-4">
                                    <div class="text-center sm:text-left">
                                        <div class="text-xs text-gray-500 mb-0.5">สถานที่ (Location)</div>
                                        <div class="font-semibold text-gray-800 text-sm truncate max-w-full">{{ $booking->reliefPoint->name }}</div>
                                    </div>
                                    <div class="text-center sm:text-left">
                                        <div class="text-xs text-gray-500 mb-0.5">จำนวนผู้เข้าพัก (Persons)</div>
                                        <div class="font-semibold text-gray-800 text-sm">{{ $booking->number_of_people }} คน</div>
                                    </div>
                                </div>
                                <div class="text-center sm:text-left">
                                    <div class="text-xs text-gray-500 mb-0.5">วันที่ออกบัตร (Issued Date)</div>
                                    <div class="font-mono text-gray-800 text-sm">{{ $booking->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Barcode Decor -->
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-center">
                            <div class="flex gap-1 h-8 opacity-40">
                                <div class="w-1 bg-gray-800 h-full"></div><div class="w-2 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div><div class="w-3 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div><div class="w-2 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div><div class="w-4 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div><div class="w-2 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div><div class="w-2 bg-gray-800 h-full"></div><div class="w-3 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div><div class="w-1 bg-gray-800 h-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="text-sm text-gray-500 mb-1">รายละเอียดการจอง</div>
                        <div class="font-semibold text-gray-900">จำนวน: {{ $booking->number_of_people }} คน</div>
                        <div class="text-sm text-gray-700 mt-1">ที่อยู่ปัจจุบัน: {{ $booking->current_address }}</div>
                        @if($booking->additional_info)
                            <div class="text-sm text-gray-700 mt-1">เพิ่มเติม: {{ $booking->additional_info }}</div>
                        @endif
                        <div class="text-xs text-gray-400 mt-3">จองเมื่อ: {{ $booking->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="flex flex-col gap-3 justify-center">
                        @if($booking->reliefPoint->latitude && $booking->reliefPoint->longitude)
                            <a href="https://www.google.com/maps?q={{ $booking->reliefPoint->latitude }},{{ $booking->reliefPoint->longitude }}" target="_blank" class="w-full py-2.5 flex items-center justify-center gap-2 text-sm font-bold bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                <x-heroicon-s-map class="w-5 h-5" /> นำทางด้วย Google Maps
                            </a>
                        @endif

                        <button type="button" onclick="evaluateRoute({{ $booking->id }})" class="w-full py-2.5 flex items-center justify-center gap-2 text-sm font-bold bg-purple-50 text-purple-700 rounded-xl hover:bg-purple-600 hover:text-white transition-all">
                            <x-heroicon-s-sparkles class="w-5 h-5" /> ให้ AI ประเมินเส้นทางอพยพ
                        </button>
                    </div>
                </div>

                <!-- AI Response Container -->
                <div id="ai-response-{{ $booking->id }}" class="hidden mt-4 bg-purple-50 border border-purple-100 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-3 text-purple-800 font-bold">
                        <x-heroicon-s-sparkles class="w-5 h-5" />
                        RescueMind AI Route Evaluation
                    </div>
                    <div id="ai-content-{{ $booking->id }}" class="text-sm text-purple-900 whitespace-pre-wrap leading-relaxed">
                        กำลังวิเคราะห์เส้นทาง...
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-gray-300">
                <div class="text-gray-300 mb-4 flex justify-center"><x-heroicon-o-document-text class="w-16 h-16" /></div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">ยังไม่มีประวัติการจอง</h3>
                <p class="text-gray-500 mb-6">คุณยังไม่ได้จองที่พักพิงใดๆ</p>
                <a href="{{ route('relief-points.index') }}" class="inline-flex px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors">
                    ค้นหาที่พักพิง
                </a>
            </div>
        @endforelse
    </div>
</div>

<script>
function evaluateRoute(bookingId) {
    const responseContainer = document.getElementById(`ai-response-${bookingId}`);
    const contentContainer = document.getElementById(`ai-content-${bookingId}`);
    
    responseContainer.classList.remove('hidden');
    contentContainer.innerHTML = '<div class="flex items-center gap-2"><div class="w-4 h-4 border-2 border-purple-500 border-t-transparent rounded-full animate-spin"></div> กำลังให้ AI วิเคราะห์เส้นทาง...</div>';

    fetch(`/bookings/${bookingId}/route-ai`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        contentContainer.innerText = data.evaluation;
    })
    .catch(error => {
        contentContainer.innerText = 'เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง';
    });
}
</script>
@endsection
