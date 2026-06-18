@extends('layouts.app')
@section('title', 'นัดหมายผู้เชี่ยวชาญ')
@section('page-title', '📅 นัดหมายผู้เชี่ยวชาญสุขภาพจิต')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 p-6 text-white">
            <div class="text-3xl mb-2">📅</div>
            <h1 class="text-xl font-bold">จองนัดหมายผู้เชี่ยวชาญ</h1>
            <p class="text-purple-100 text-sm mt-1">พูดคุยกับจิตแพทย์หรือนักจิตวิทยา</p>
        </div>

        <form action="{{ route('mental.appointments.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทการปรึกษา <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    <option value="">เลือกประเภท</option>
                    <option value="video" {{ old('type')=='video'?'selected':'' }}>💻 ออนไลน์ (Video Call)</option>
                    <option value="in_person" {{ old('type')=='in_person'?'selected':'' }}>🏥 พบหน้า (Onsite)</option>
                </select>
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันและเวลาที่ต้องการ <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="scheduled_at" required value="{{ old('scheduled_at') }}"
                       min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                @error('scheduled_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ปัญหาที่ต้องการปรึกษา</label>
                <textarea name="notes" rows="4" placeholder="อธิบายสั้นๆ ว่าต้องการปรึกษาเรื่องอะไร..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="p-4 bg-purple-50 rounded-xl text-sm text-purple-700">
                <strong>หมายเหตุ:</strong> เจ้าหน้าที่จะยืนยันนัดหมายและแจ้งรายละเอียดผ่านโทรศัพท์หรืออีเมล
            </div>

            <button type="submit" class="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-2xl transition-colors">
                📨 ส่งคำขอนัดหมาย
            </button>
        </form>
    </div>

    {{-- My Appointments --}}
    @if(auth()->user()->appointments()->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800">นัดหมายของฉัน</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach(auth()->user()->appointments()->latest()->take(5)->get() as $appt)
            <div class="p-4">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-medium text-gray-800">{{ $appt->scheduled_at?->format('d/m/Y H:i') ?? 'รอยืนยัน' }}</span>
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $appt->status == 'confirmed' ? 'bg-green-100 text-green-700' : ($appt->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ['pending'=>'⏳ รอยืนยัน','confirmed'=>'✅ ยืนยันแล้ว','cancelled'=>'❌ ยกเลิก'][$appt->status] ?? $appt->status }}
                    </span>
                </div>
                <div class="text-sm text-gray-500">{{ ['video'=>'💻 ออนไลน์','in_person'=>'🏥 พบหน้า'][$appt->type] ?? $appt->type }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
