@extends('layouts.app')
@section('title', 'นัดหมายผู้เชี่ยวชาญ')
@section('page-title')
    <x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> นัดหมายผู้เชี่ยวชาญสุขภาพจิต
@endsection
@section('content')
<div class="max-w-xl mx-auto space-y-6">
    {{-- Back Button --}}
    <div>
        <a href="{{ route('mt3') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-pink-600 transition-colors group">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 bg-white group-hover:border-pink-300 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
            ย้อนกลับ
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-pink-50 overflow-hidden relative">
        <div class="bg-gradient-to-br from-rose-400 via-pink-500 to-purple-600 p-8 text-white relative">
            <div class="absolute inset-0 opacity-20 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 shadow-inner mb-4">
                    <x-heroicon-o-calendar-days class="w-6 h-6 text-white drop-shadow-sm" />
                </div>
                <h1 class="text-2xl font-bold tracking-tight drop-shadow-sm">จองนัดหมายผู้เชี่ยวชาญ</h1>
                <p class="text-pink-50 text-sm mt-1 font-medium drop-shadow-sm">พูดคุยกับจิตแพทย์หรือนักจิตวิทยาเพื่อดูแลสุขภาพใจ</p>
            </div>
        </div>

        <form action="{{ route('mental.appointments.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">ประเภทการปรึกษา <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <select name="type" required class="w-full pl-4 pr-10 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-[15px] focus:bg-white focus:ring-4 focus:ring-pink-100 focus:border-pink-400 transition-all shadow-inner appearance-none font-medium text-gray-700">
                        <option value="">เลือกประเภทการให้คำปรึกษา</option>
                        <option value="video" {{ old('type')=='video'?'selected':'' }}><x-heroicon-o-computer-desktop class="w-5 h-5 inline-block mr-1 -mt-1" /> ออนไลน์ (Video Call)</option>
                        <option value="in_person" {{ old('type')=='in_person'?'selected':'' }}><x-heroicon-o-building-office-2 class="w-5 h-5 inline-block mr-1 -mt-1" /> พบหน้า (Onsite ที่ศูนย์)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                @error('type') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">วันและเวลาที่ต้องการ <span class="text-rose-500">*</span></label>
                <input type="datetime-local" name="scheduled_at" required value="{{ old('scheduled_at') }}"
                       min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                       class="w-full px-4 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-[15px] focus:bg-white focus:ring-4 focus:ring-pink-100 focus:border-pink-400 transition-all shadow-inner font-medium text-gray-700">
                @error('scheduled_at') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">ปัญหาที่ต้องการปรึกษา <span class="text-gray-400 font-normal text-xs ml-1">(ไม่บังคับ)</span></label>
                <textarea name="notes" rows="4" placeholder="อธิบายสั้นๆ ว่าต้องการปรึกษาเรื่องอะไร เพื่อให้ผู้เชี่ยวชาญเตรียมตัว..."
                          class="w-full px-4 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-[15px] focus:bg-white focus:ring-4 focus:ring-pink-100 focus:border-pink-400 transition-all shadow-inner resize-none font-medium text-gray-700">{{ old('notes') }}</textarea>
            </div>

            <div class="p-4 bg-gradient-to-r from-rose-50 to-pink-50 border border-rose-100 rounded-2xl text-sm text-rose-700 flex items-start gap-3 shadow-sm">
                <x-heroicon-s-information-circle class="w-5 h-5 shrink-0 text-rose-500 mt-0.5" />
                <span class="leading-relaxed font-medium"><strong>หมายเหตุ:</strong> เจ้าหน้าที่จะติดต่อกลับเพื่อยืนยันเวลานัดหมาย และแจ้งรายละเอียดเพิ่มเติมผ่านทางโทรศัพท์หรืออีเมลอีกครั้ง</span>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-2xl transition-all transform active:scale-[0.98] shadow-md hover:shadow-lg flex justify-center items-center gap-2">
                <span class="text-lg"><x-heroicon-o-envelope class="w-5 h-5 inline-block mr-1 -mt-1" /></span> ส่งคำขอนัดหมาย
            </button>
        </form>
    </div>

    {{-- My Appointments --}}
    @if(auth()->user()->appointments()->count() > 0)
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-pink-50 mt-8 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-pink-500" /> นัดหมายของฉัน
            </h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach(auth()->user()->appointments()->latest()->take(5)->get() as $appt)
            <div class="p-6 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-gray-800 flex items-center gap-2">
                        <x-heroicon-o-calendar class="w-4 h-4 text-gray-400" />
                        {{ $appt->scheduled_at?->format('d/m/Y H:i') ?? 'รอยืนยัน' }}
                    </span>
                    <span class="text-[11px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1
                        {{ $appt->status == 'confirmed' ? 'bg-green-50 text-green-600 border border-green-100' : ($appt->status == 'cancelled' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-orange-50 text-orange-600 border border-orange-100') }}">
                        @if($appt->status == 'confirmed')
                            <x-heroicon-s-check-circle class="w-3.5 h-3.5" /> ยืนยันแล้ว
                        @elseif($appt->status == 'cancelled')
                            <x-heroicon-s-x-circle class="w-3.5 h-3.5" /> ยกเลิก
                        @else
                            <x-heroicon-s-clock class="w-3.5 h-3.5" /> รอยืนยัน
                        @endif
                    </span>
                </div>
                <div class="text-sm font-medium text-gray-500 flex items-center gap-1.5">
                    @if($appt->type == 'video')
                        <span class="text-base"><x-heroicon-o-computer-desktop class="w-5 h-5 inline-block mr-1 -mt-1" /></span> ออนไลน์ (Video Call)
                    @else
                        <span class="text-base"><x-heroicon-o-building-office-2 class="w-5 h-5 inline-block mr-1 -mt-1" /></span> พบหน้า (Onsite)
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
