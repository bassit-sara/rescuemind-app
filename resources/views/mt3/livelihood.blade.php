@extends('layouts.app')
@section('title', 'แจ้งความเสียหายและฟื้นฟูอาชีพ')

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
                        <x-heroicon-o-briefcase class="w-7 h-7 text-emerald-500" />
                        แจ้งความเสียหายและฟื้นฟูอาชีพ
                    </h2>
                    <p class="text-gray-600 text-[15px] font-medium mt-1">ระบบแจ้งความเสียหายทางการเกษตร ปศุสัตว์ หรือธุรกิจรายย่อย เพื่อขอรับเงินชดเชย หรือทุนฝึกอาชีพใหม่</p>
                </div>
            </div>
            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
            <div class="flex items-center gap-2 shrink-0">
                <button class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-200 flex items-center gap-2 transition-colors">
                    <x-heroicon-o-pencil-square class="w-4 h-4" /> จัดการข้อมูลผู้เสียหาย
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

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
            <div class="p-8">
                <form action="{{ route('mt3.submit-form') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Type of business -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2">1. ประเภทอาชีพที่ได้รับความเสียหาย</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="radio" name="business_type" value="agriculture" class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500" checked onchange="document.getElementById('other_business').classList.add('hidden')">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">เกษตรกรรม (พืชผล)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="radio" name="business_type" value="livestock" class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500" onchange="document.getElementById('other_business').classList.add('hidden')">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">ปศุสัตว์ / ประมง</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="radio" name="business_type" value="business" class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500" onchange="document.getElementById('other_business').classList.add('hidden')">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">ร้านค้า / ธุรกิจรายย่อย</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="radio" name="business_type" value="other" class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500" onchange="document.getElementById('other_business').classList.remove('hidden')">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">อื่นๆ (โปรดระบุ)</span>
                                </label>
                            </div>
                            <div id="other_business" class="hidden mt-3 animate-fade-in-down">
                                <input type="text" name="business_type_other" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" placeholder="ระบุประเภทอาชีพของคุณ...">
                            </div>
                        </div>

                        <!-- Details -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">2. รายละเอียดความเสียหาย</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่ประกอบการ (ที่อยู่แปลงเกษตร / ร้านค้า)</label>
                                    <input type="text" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดความเสียหาย (เช่น ข้าวนาปรังเสียหาย 10 ไร่, อุปกรณ์ในร้านพังทั้งหมด)</label>
                                    <textarea rows="3" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">มูลค่าความเสียหายประเมินเบื้องต้น (บาท)</label>
                                    <input type="number" class="w-full md:w-1/2 border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow">
                                </div>
                            </div>
                        </div>

                        <!-- Assistance needed -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">3. ความประสงค์ในการฟื้นฟู</h3>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 text-gray-700">ขอรับเงินชดเชยเยียวยาตามมาตรการรัฐ</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 text-gray-700">ขอรับเมล็ดพันธุ์ / พันธุ์สัตว์ / อุปกรณ์ทำกินใหม่</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 text-gray-700">ต้องการเข้าฝึกอบรมเปลี่ยนสายอาชีพ (Reskill)</span>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="mt-10 flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('mt3') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-bold transition-colors">ยกเลิก</a>
                        <button type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-bold shadow-md shadow-emerald-200 transition-all transform hover:-translate-y-0.5">แจ้งความเสียหาย</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



