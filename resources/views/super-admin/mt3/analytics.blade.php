@extends('layouts.admin')
@section('title', 'Analytics MT3')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('mt3') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-gray-900 hover:bg-gray-100 shadow-sm transition-colors shrink-0">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                            <x-heroicon-o-chart-bar class="w-7 h-7 text-gray-700" />
                            Recovery Dashboard & Analytics
                        </h2>
                        <p class="text-gray-600 text-[15px] font-medium mt-1">ภาพรวมการฟื้นฟูพื้นที่ สถิติความช่วยเหลือ และการวิเคราะห์ข้อมูลสำหรับเจ้าหน้าที่</p>
                    </div>
                    
                    @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                    <div class="mt-2 sm:mt-0">
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-300 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-cog-8-tooth class="w-4 h-4" /> ตั้งค่า Analytics
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Filter (Mock) -->
            <div class="hidden sm:flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                <span class="text-sm font-medium text-gray-600 font-medium">พื้นที่:</span>
                <select class="border-0 bg-transparent text-sm font-black text-gray-800 focus:ring-0 cursor-pointer">
                    <option>ทั้งหมด (ทั่วประเทศ)</option>
                    <option>ภาคใต้ (3 จังหวัดชายแดนภาคใต้)</option>
                    <option>ภาคกลาง (อยุธยา)</option>
                </select>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-orange-50 rounded-full"></div>
                <div class="relative z-10">
                    <div class="text-sm font-bold text-gray-600 font-medium mb-1">คำขอฟื้นฟูบ้าน (ทั้งหมด)</div>
                    <div class="text-3xl font-black text-gray-900 mb-2">1,284 <span class="text-sm font-medium text-gray-400">หลัง</span></div>
                    <div class="flex items-center text-sm text-green-600 font-bold">
                        <x-heroicon-s-arrow-trending-up class="w-4 h-4 mr-1" />
                        สำเร็จแล้ว 65%
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full"></div>
                <div class="relative z-10">
                    <div class="text-sm font-bold text-gray-600 font-medium mb-1">อาสาสมัครลงพื้นที่</div>
                    <div class="text-3xl font-black text-gray-900 mb-2">856 <span class="text-sm font-medium text-gray-400">คน</span></div>
                    <div class="flex items-center text-sm text-green-600 font-bold">
                        <x-heroicon-s-arrow-trending-up class="w-4 h-4 mr-1" />
                        +120 คน (สัปดาห์นี้)
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-pink-50 rounded-full"></div>
                <div class="relative z-10">
                    <div class="text-sm font-bold text-gray-600 font-medium mb-1">สิ่งของบริจาคที่แมตช์แล้ว</div>
                    <div class="text-3xl font-black text-gray-900 mb-2">15.2k <span class="text-sm font-medium text-gray-400">รายการ</span></div>
                    <div class="flex items-center text-sm text-indigo-600 font-bold">
                        <x-heroicon-s-cpu-chip class="w-4 h-4 mr-1" />
                        AI Matching 92%
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-yellow-50 rounded-full"></div>
                <div class="relative z-10">
                    <div class="text-sm font-bold text-gray-600 font-medium mb-1">คำขอชดเชยอาชีพ</div>
                    <div class="text-3xl font-black text-gray-900 mb-2">342 <span class="text-sm font-medium text-gray-400">ราย</span></div>
                    <div class="flex items-center text-sm text-yellow-600 font-bold">
                        <x-heroicon-s-clock class="w-4 h-4 mr-1" />
                        กำลังตรวจสอบ 80%
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Progress Bars Section -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-800 mb-6 border-b pb-4">ความคืบหน้าการฟื้นฟูแยกตามจังหวัด (Top 3)</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-gray-700">จ.ยะลา (วิกฤตหนักสุด)</span>
                                <span class="text-sm font-bold text-orange-600">45%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-orange-500 h-3 rounded-full" style="width: 45%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 font-medium mt-1">
                                <span>บ้านที่ฟื้นฟูแล้ว: 320/711 หลัง</span>
                                <span>เป้าหมาย: ภายใน 30 วัน</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-gray-700">จ.ปัตตานี</span>
                                <span class="text-sm font-bold text-blue-600">72%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full" style="width: 72%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 font-medium mt-1">
                                <span>บ้านที่ฟื้นฟูแล้ว: 215/298 หลัง</span>
                                <span>เป้าหมาย: ภายใน 15 วัน</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-gray-700">จ.นราธิวาส</span>
                                <span class="text-sm font-bold text-green-600">88%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full" style="width: 88%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 font-medium mt-1">
                                <span>บ้านที่ฟื้นฟูแล้ว: 154/175 หลัง</span>
                                <span>ใกล้เสร็จสิ้น</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-800 mb-6 border-b pb-4">รายการล่าสุด (Real-time Feed)</h3>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                                <x-heroicon-s-check class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-800">ทีมอาสา "ใจถึงใจ" ล้างโคลนเสร็จสิ้น (บ้านเลขที่ 12/3 ยะลา)</p>
                                <p class="text-sm text-gray-600 font-medium mt-0.5">2 นาทีที่แล้ว</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 shrink-0">
                                <x-heroicon-s-gift class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-800">มีผู้บริจาคเงินสมทบทุน 5,000 บาท</p>
                                <p class="text-sm text-gray-600 font-medium mt-0.5">15 นาทีที่แล้ว</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-orange-600 shrink-0">
                                <x-heroicon-s-exclamation-triangle class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-800">คำขอฟื้นฟูบ้านใหม่ 3 รายการ จาก อ.เมือง ยะลา</p>
                                <p class="text-sm text-gray-600 font-medium mt-0.5">1 ชั่วโมงที่แล้ว</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Insights Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-indigo-50 rounded-3xl p-6 text-indigo-900 shadow-sm border border-indigo-100">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2 text-indigo-800 border-b border-indigo-200 pb-3">
                        <x-heroicon-s-sparkles class="w-5 h-5 text-indigo-500" />
                        AI Insights
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                            <h4 class="font-bold text-sm text-indigo-600 mb-1">การคาดการณ์ (Prediction)</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                อิงจากอัตราการทำงานปัจจุบัน พื้นที่ จ.ยะลา จะฟื้นฟูระบบสาธารณูปโภคหลักเสร็จสิ้นภายใน <strong class="text-indigo-700">12 วัน</strong> (เร็วกว่ากำหนดการเดิม 3 วัน)
                            </p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                            <h4 class="font-bold text-sm text-orange-600 mb-1">ทรัพยากรที่อาจขาดแคลน</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                แนวโน้มความต้องการ <strong class="text-orange-700">"ช่างไฟฟ้า"</strong> ในสัปดาห์หน้าจะเพิ่มขึ้น 40% แนะนำให้เปิดรับอาสาสมัครช่างไฟเพิ่มด่วน
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


