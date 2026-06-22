@extends('layouts.app')
@section('title', 'ระบบอาสาสมัครฟื้นฟู')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mt3') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-emerald-600 hover:bg-emerald-50 shadow-sm transition-colors shrink-0">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-o-users class="w-7 h-7 text-emerald-500" />
                        ระบบลงทะเบียนอาสาสมัครฟื้นฟู
                    </h2>
                    <p class="text-gray-600 text-[15px] font-medium mt-1">ร่วมเป็นส่วนหนึ่งในการฟื้นฟูชุมชนและช่วยเหลือผู้ประสบภัย</p>
                </div>
            </div>
            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
            <div class="flex items-center gap-2 shrink-0">
                <button class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-200 flex items-center gap-2 transition-colors">
                    <x-heroicon-o-pencil-square class="w-4 h-4" /> จัดการรายชื่ออาสาสมัคร
                </button>
            </div>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                <x-heroicon-s-check-circle class="w-6 h-6 text-emerald-500 shrink-0" />
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
            <div class="p-0 sm:p-8">
                <!-- Hero Banner Image (Mock) -->
                <div class="h-48 bg-emerald-600 rounded-2xl mb-8 relative overflow-hidden hidden sm:block">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-white p-6 text-center">
                        <x-heroicon-s-heart class="w-12 h-12 mb-2 text-emerald-200" />
                        <h3 class="text-2xl font-black tracking-wide text-white">พลังอาสา ฟื้นฟูชุมชน</h3>
                        <p class="text-emerald-100 mt-1">ทุกทักษะของคุณ มีค่าสำหรับผู้ประสบภัย</p>
                    </div>
                </div>

                <form action="{{ route('mt3.submit-form') }}" method="POST" class="p-6 sm:p-0">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Personal Info -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2">1. ข้อมูลส่วนตัว</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล</label>
                                    <input type="text" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required value="{{ auth()->user()->name ?? '' }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ติดต่อ</label>
                                    <input type="text" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปแบบอาสาสมัคร</label>
                                    <select class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow">
                                        <option>อาสาสมัครรายบุคคล</option>
                                        <option>มาเป็นทีม/กลุ่ม/องค์กร</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Skills -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">2. ทักษะและความเชี่ยวชาญ (เลือกได้มากกว่า 1)</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">แรงงาน/ทำความสะอาด</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">ช่างไฟฟ้า</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">ช่างประปา</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">ช่างก่อสร้าง/ไม้</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">แพทย์/พยาบาล/กู้ชีพ</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">นักจิตวิทยา/ที่ปรึกษา</span>
                                </label>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">3. วันและพื้นที่ที่สะดวก</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">พื้นที่ที่สะดวกลงปฏิบัติงาน (จังหวัด/อำเภอ)</label>
                                    <input type="text" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" placeholder="เช่น ยะลา">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ระยะเวลาที่สามารถช่วยได้ (วัน)</label>
                                    <input type="number" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" placeholder="เช่น 3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('mt3') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-bold transition-colors">ยกเลิก</a>
                        <button type="submit" class="w-full sm:w-auto text-center px-8 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-bold shadow-md shadow-emerald-200 transition-all transform hover:-translate-y-0.5">ลงทะเบียนอาสาสมัคร</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



