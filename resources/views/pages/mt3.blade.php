@extends('layouts.app')

@section('title', 'MT3 ฟื้นฟูหลังเกิดภัย (Recovery & Rehabilitation)')
@section('page-title')
    มิติที่ 3: หลังเกิดภัย
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    {{-- Back Button --}}
    <div>
        <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-teal-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ย้อนกลับหน้าหลัก
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-teal-500 to-emerald-700 rounded-3xl p-6 sm:p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-teal-900/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 drop-shadow-md"><x-heroicon-s-sparkles class="w-8 h-8 inline-block shrink-0 text-emerald-100" /></div>
            <h1 class="text-2xl sm:text-3xl md:text-5xl font-black mb-3 sm:mb-4 drop-shadow-md tracking-tight leading-snug">Recovery & Rehabilitation</h1>
            <p class="text-emerald-50 text-lg md:text-xl font-medium max-w-3xl leading-relaxed drop-shadow-sm">
                ศูนย์กลางการฟื้นฟูหลังภัยพิบัติแบบครบวงจร ครอบคลุมการฟื้นฟูบ้านเรือน ชุมชน อาชีพ อาสาสมัคร การบริจาค และเยียวยาสุขภาพจิต เพื่อให้คุณกลับมาใช้ชีวิตได้อย่างเข้มแข็งอีกครั้ง
            </p>
        </div>
    </div>

    @auth
    
    {{-- Section 1: Home & Community Recovery --}}
    <div class="pt-4">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <x-heroicon-s-home-modern class="w-8 h-8 text-emerald-500" /> ฟื้นฟูที่อยู่อาศัยและชุมชน (Home Recovery)
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Home Recovery Request --}}
            <a href="{{ route('mt3.home-recovery') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-200 transition-all duration-300 text-left flex flex-col transform hover:-translate-y-1 relative overflow-hidden">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-home-modern class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 text-lg mb-2 group-hover:text-emerald-600 transition-colors">ขอความช่วยเหลือฟื้นฟูบ้าน</div>
                <div class="text-[15px] text-gray-700 leading-relaxed font-medium">แจ้งขอรับความช่วยเหลือในการทำความสะอาด ล้างโคลน ซ่อมแซมระบบไฟฟ้าและประปาเบื้องต้น</div>
            </a>
            
            {{-- Community Needs Assessment --}}
            <a href="{{ route('mt3.community-needs') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-teal-200 transition-all duration-300 text-left flex flex-col transform hover:-translate-y-1 relative overflow-hidden">
                <div class="w-14 h-14 bg-teal-50 text-teal-500 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-megaphone class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 text-lg mb-2 group-hover:text-teal-600 transition-colors">ประเมินความต้องการชุมชน</div>
                <div class="text-[15px] text-gray-700 leading-relaxed font-medium">แจ้งและรวบรวมความต้องการของชุมชนส่วนรวม เช่น อาหาร น้ำดื่ม ยารักษาโรค เพื่อประสานงานจัดหา</div>
            </a>
            
            {{-- Household Recovery Tracking --}}
            <a href="{{ route('mt3.recovery-tracking') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-teal-200 transition-all duration-300 text-left flex flex-col transform hover:-translate-y-1 relative overflow-hidden">
                <div class="w-14 h-14 bg-teal-50 text-teal-500 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-clipboard-document-check class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 text-lg mb-2 group-hover:text-teal-600 transition-colors">ติดตามการฟื้นฟู</div>
                <div class="text-[15px] text-gray-700 leading-relaxed font-medium">เช็คสถานะคำขอความช่วยเหลือ ติดตามความคืบหน้าการทำงานของทีมอาสาสมัครฟื้นฟูบ้านคุณ</div>
            </a>
        </div>
    </div>

    {{-- Section 2: Volunteers & Donations --}}
    <div class="pt-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <x-heroicon-s-users class="w-8 h-8 text-emerald-500" /> อาสาสมัครและการบริจาค (Volunteers & Donations)
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Volunteer Recovery Team --}}
            <a href="{{ route('mt3.volunteer') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-200 transition-all duration-300 text-left flex flex-col transform hover:-translate-y-1 relative overflow-hidden">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-users class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 text-lg mb-2 group-hover:text-emerald-600 transition-colors">ระบบอาสาสมัครฟื้นฟู</div>
                <div class="text-[15px] text-gray-700 leading-relaxed font-medium">ลงทะเบียนเป็นอาสาสมัคร ค้นหางานตามทักษะ (ช่างไฟ, ประปา, จิตวิทยา) และจับคู่กับพื้นที่ที่ต้องการ</div>
            </a>
            
            {{-- Donation Center --}}
            <a href="{{ route('mt3.donation') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-200 transition-all duration-300 text-left flex flex-col transform hover:-translate-y-1 relative overflow-hidden">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-gift class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 text-lg mb-2 group-hover:text-emerald-600 transition-colors">ศูนย์รับบริจาค</div>
                <div class="text-[15px] text-gray-700 leading-relaxed font-medium">ระบบรับบริจาคเงินและสิ่งของช่วยเหลืออย่างโปร่งใส พร้อมตรวจสอบเส้นทางการจัดส่งทรัพยากร</div>
            </a>
            
            {{-- AI Donation Matching --}}
            <a href="{{ route('mt3.ai-matching') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-teal-200 transition-all duration-300 text-left flex flex-col transform hover:-translate-y-1 relative overflow-hidden">
                <div class="w-14 h-14 bg-teal-50 text-teal-500 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-cpu-chip class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 text-lg mb-2 group-hover:text-teal-600 transition-colors">AI Donation Matching</div>
                <div class="text-[15px] text-gray-700 leading-relaxed font-medium">ระบบอัจฉริยะวิเคราะห์เพื่อจับคู่ผู้บริจาคกับพื้นที่ที่กำลังขาดแคลน ช่วยลดปัญหาของล้นคลัง</div>
            </a>
        </div>
    </div>

    {{-- Section 3: Livelihood Recovery --}}
    <div class="pt-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <x-heroicon-s-briefcase class="w-8 h-8 text-emerald-500" /> ฟื้นฟูอาชีพและเศรษฐกิจ (Livelihood Recovery)
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            {{-- Livelihood Recovery --}}
            <a href="{{ route('mt3.livelihood') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-200 transition-all duration-300 text-left flex flex-col transform hover:-translate-y-1 relative overflow-hidden">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-briefcase class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 text-lg mb-2 group-hover:text-emerald-600 transition-colors">แจ้งความเสียหายและฟื้นฟูอาชีพ</div>
                <div class="text-[15px] text-gray-700 leading-relaxed font-medium">ระบบแจ้งความเสียหายทางการเกษตร ปศุสัตว์ หรือธุรกิจรายย่อย เพื่อขอรับเงินชดเชย หรือทุนฝึกอาชีพใหม่</div>
            </a>
            
            {{-- Recovery Analytics --}}
            <a href="{{ route('mt3.analytics') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-gray-300 transition-all duration-300 text-left flex flex-col transform hover:-translate-y-1 relative overflow-hidden">
                <div class="w-14 h-14 bg-gray-100 text-gray-600 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-chart-bar class="w-7 h-7" />
                </div>
                <div class="font-bold text-gray-800 text-lg mb-2 group-hover:text-gray-900 transition-colors">Recovery Dashboard & Analytics</div>
                <div class="text-[15px] text-gray-700 leading-relaxed font-medium">ติดตามภาพรวมการฟื้นฟูพื้นที่ (สำหรับเจ้าหน้าที่) ดูอัตราการฟื้นฟูสำเร็จ และวิเคราะห์ความต้องการในอนาคต</div>
            </a>
        </div>
    </div>

    {{-- Section 4: Mental Recovery --}}
    <div class="pt-6 border-t border-gray-100 mt-10">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <x-heroicon-s-sparkles class="w-8 h-8 text-teal-500" /> ฟื้นฟูสุขภาพจิต (Mental Recovery)
        </h3>

        {{-- Main Evaluation Banner --}}
        <div class="mb-6">
            <a href="{{ route('mental.index') }}" class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-3xl p-6 shadow-sm border border-emerald-100 hover:shadow-md hover:border-emerald-300 transition-all group flex flex-col md:flex-row items-center gap-6">
                <div class="w-20 h-20 bg-white text-emerald-500 rounded-full flex items-center justify-center text-4xl flex-shrink-0 group-hover:scale-110 transition-transform shadow-sm">
                    <x-heroicon-s-heart class="w-10 h-10" />
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl font-black text-teal-900 mb-2">เริ่มประเมินกายและจิต</h2>
                    <p class="text-teal-700 font-medium">ทำแบบประเมินเบื้องต้น (PHQ-9, GAD-7, PTSD) เพื่อวิเคราะห์สภาวะสุขภาพร่างกายและจิตใจของคุณ พร้อมรับคำแนะนำในการดูแลที่เหมาะสม</p>
                </div>
                <div class="hidden md:block ml-auto text-emerald-400 group-hover:text-emerald-600 transition-colors">
                    <x-heroicon-s-arrow-right-circle class="w-10 h-10" />
                </div>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            {{-- Mood Tracker --}}
            <a href="{{ route('mental.mood.index') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-200 transition-all duration-300 text-center flex flex-col items-center transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-face-smile class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 group-hover:text-emerald-600 transition-colors">Mood Tracker</div>
                <div class="text-sm text-gray-700 mt-2 leading-relaxed font-medium">บันทึกและติดตามสภาวะอารมณ์รายวันเพื่อดูแนวโน้มการฟื้นฟู</div>
            </a>
            
            {{-- Journal --}}
            <a href="{{ route('mental.journal.index') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-teal-200 transition-all duration-300 text-center flex flex-col items-center transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-teal-50 text-teal-500 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-book-open class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 group-hover:text-teal-600 transition-colors">Journal Diary</div>
                <div class="text-sm text-gray-700 mt-2 leading-relaxed font-medium">เขียนไดอารี่ถ่ายทอดความรู้สึกและลดความเครียดที่สะสมอยู่ภายใน</div>
            </a>
            
            {{-- Appointment --}}
            <a href="{{ route('mental.appointments.create') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-teal-200 transition-all duration-300 text-center flex flex-col items-center transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-teal-50 text-teal-500 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-calendar-days class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 group-hover:text-teal-600 transition-colors">นัดหมายปรึกษา</div>
                <div class="text-sm text-gray-700 mt-2 leading-relaxed font-medium">นัดหมายพูดคุยและปรึกษากับนักจิตวิทยาหรือจิตแพทย์อาสา</div>
            </a>

            {{-- Articles & Guidelines --}}
            <a href="{{ route('mental.articles') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-teal-200 transition-all duration-300 text-center flex flex-col items-center transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-book-open class="w-7 h-7" />
                </div>
                <div class="font-black text-gray-900 group-hover:text-teal-600 transition-colors">บทความน่ารู้</div>
                <div class="text-sm text-gray-700 mt-2 leading-relaxed font-medium">คลังบทความและแนวทางปฐมพยาบาลดูแลสุขภาพกายและใจ</div>
            </a>
            
            {{-- AI Companion (Full Page) --}}
            <a href="{{ route('mt3.ai-companion') }}" class="col-span-2 sm:col-span-3 lg:col-span-1 group bg-white rounded-3xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-teal-200 transition-all duration-300 text-center flex flex-col items-center w-full transform hover:-translate-y-1">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-50 text-teal-500 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-bold mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-cpu-chip class="w-6 h-6 sm:w-7 sm:h-7" />
                </div>
                <div class="font-black text-[15px] sm:text-base text-gray-900 group-hover:text-teal-600 transition-colors">AI Companion</div>
                <div class="text-[12px] sm:text-sm text-gray-700 mt-1 sm:mt-2 leading-relaxed font-medium hidden sm:block">พูดคุยปรึกษากับระบบ AI อัจฉริยะที่จะอยู่เคียงข้างคุณตลอด 24 ชม.</div>
            </a>
        </div>
    </div>

    @else
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-3xl p-10 border border-emerald-100 text-center shadow-sm">
        <div class="flex justify-center mb-6"><x-heroicon-o-lock-closed class="w-16 h-16 text-teal-400" /></div>
        <h2 class="text-2xl font-black text-gray-800 mb-3">ระบบฟื้นฟูหลังเกิดภัย (MT3) สงวนไว้สำหรับสมาชิก</h2>
        <p class="text-gray-700 font-medium mb-8 max-w-lg mx-auto leading-relaxed">เพื่อความเป็นส่วนตัวและความปลอดภัยของข้อมูลในการขอรับความช่วยเหลือและการประเมินสุขภาพ กรุณาเข้าสู่ระบบก่อนใช้งาน</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('login') }}" class="px-8 py-3.5 bg-white text-teal-600 font-bold rounded-2xl shadow-sm border border-teal-200 hover:bg-teal-50 hover:shadow transition-all">เข้าสู่ระบบ (Login)</a>
            <a href="{{ route('register') }}" class="px-8 py-3.5 bg-teal-600 text-white font-bold rounded-2xl shadow-sm hover:bg-teal-700 hover:shadow transition-all">สมัครสมาชิกฟรี (Register)</a>
        </div>
    </div>
    @endauth

</div>
@endsection


