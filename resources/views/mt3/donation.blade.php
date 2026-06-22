@extends('layouts.app')
@section('title', 'ศูนย์รับบริจาค')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mt3') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-emerald-600 hover:bg-emerald-50 shadow-sm transition-colors shrink-0">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-o-gift class="w-7 h-7 text-emerald-500" />
                        ศูนย์รับบริจาค
                    </h2>
                    <p class="text-gray-600 text-[15px] font-medium mt-1">ร่วมบริจาคเงินและสิ่งของช่วยเหลืออย่างโปร่งใส พร้อมระบบตรวจสอบเส้นทางการจัดส่ง</p>
                </div>
            </div>
            
            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
            <div class="flex items-center gap-2 shrink-0">
                <button class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-200 flex items-center gap-2 transition-colors">
                    <x-heroicon-o-pencil-square class="w-4 h-4" /> จัดการข้อมูลบริจาค
                </button>
            </div>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm">
                <x-heroicon-s-check-circle class="w-6 h-6 text-green-500 shrink-0" />
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Donate Money -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-banknotes class="w-8 h-8" />
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2">บริจาคเงินสมทบทุน</h3>
                <p class="text-gray-600 text-[15px] font-medium mb-6">โอนเงินเข้าบัญชีส่วนกลางเพื่อนำไปจัดซื้อสิ่งของที่จำเป็น</p>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200 text-left">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">ธนาคารกรุงไทย</div>
                    <div class="text-lg font-mono font-black text-gray-800 tracking-widest mb-1">987-6-54321-0</div>
                    <div class="text-sm font-bold text-gray-600">ชื่อบัญชี: กองทุนฟื้นฟูผู้ประสบภัย RescueMind</div>
                </div>

                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QR Code" class="w-32 h-32 mx-auto mb-6 opacity-70">
                
                <button onclick="
                    navigator.clipboard.writeText('9876543210'); 
                    let btn = this;
                    let oldText = btn.innerText;
                    let oldBg = btn.className;
                    btn.innerText = 'คัดลอกสำเร็จ!';
                    btn.className = 'w-full py-3 bg-green-100 text-green-700 rounded-xl font-bold transition-colors';
                    setTimeout(() => { 
                        btn.innerText = oldText; 
                        btn.className = oldBg; 
                    }, 2000);
                " class="w-full py-3 bg-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-200 font-bold transition-colors">
                    คัดลอกเลขบัญชี
                </button>
            </div>

            <!-- Donate Items -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-archive-box class="w-8 h-8" />
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2">บริจาคสิ่งของ</h3>
                <p class="text-gray-600 text-[15px] font-medium mb-6">จัดส่งสิ่งของจำเป็นทางไปรษณีย์ หรือ นำมามอบด้วยตนเอง</p>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200 text-left">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">ที่อยู่จัดส่งสิ่งของ</div>
                    <div class="text-sm font-black text-gray-800 mb-1">ศูนย์รับบริจาคส่วนกลาง RescueMind</div>
                    <div class="text-sm text-gray-600 leading-relaxed">เลขที่ 99 อาคารจิตอาสา ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพมหานคร 10110</div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="text-sm font-bold text-emerald-600 text-left"><x-heroicon-o-map-pin class="w-5 h-5 inline-block mr-1 -mt-1" /> สิ่งของที่ต้องการด่วนวันนี้:</div>
                    <ul class="text-sm text-gray-600 text-left list-disc list-inside space-y-1">
                        <li>น้ำดื่มขวดขนาด 1.5 ลิตร (ขาด 2,000 ขวด)</li>
                        <li>ยารักษาโรคน้ำกัดเท้า (ขาด 500 หลอด)</li>
                        <li>ไม้กวาดทางมะพร้าว (ขาด 300 ด้าม)</li>
                    </ul>
                </div>

                <a href="#item-form" class="block w-full py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-bold shadow-md shadow-emerald-200 transition-all transform hover:-translate-y-0.5">แจ้งความประสงค์บริจาคสิ่งของ</a>
            </div>
        </div>

        <!-- Item Donation Form -->
        <div id="item-form" class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 mt-8">
            <div class="p-8">
                <form action="{{ route('mt3.submit-form') }}" method="POST">
                    @csrf
                    <h3 class="text-lg font-black text-gray-800 mb-6 flex items-center gap-2 border-b pb-4">
                        <x-heroicon-o-document-text class="w-6 h-6 text-emerald-500" />
                        แบบฟอร์มแจ้งการบริจาคสิ่งของ (เพื่อติดตามสถานะ)
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้บริจาค / นามแฝง</label>
                                <input type="text" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required value="{{ auth()->user()->name ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ (ใส่เฉพาะกรณีต้องการให้ติดต่อกลับ)</label>
                                <input type="text" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">รายการสิ่งของที่จัดส่ง</label>
                            <textarea rows="3" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required placeholder="เช่น น้ำดื่ม 20 แพ็ค, ข้าวสาร 10 กระสอบ"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">เลขพัสดุ Tracking Number (ถ้ามี)</label>
                            <input type="text" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" placeholder="เช่น TH123456789">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <button type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-bold shadow-md shadow-emerald-200 transition-all transform hover:-translate-y-0.5">บันทึกข้อมูลการบริจาค</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



