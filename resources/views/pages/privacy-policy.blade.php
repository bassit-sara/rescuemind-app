@extends('layouts.app')

@section('title', 'นโยบายความเป็นส่วนตัว (Privacy Policy)')
@section('page-title', 'นโยบายความเป็นส่วนตัว')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-blue-900 to-indigo-900 rounded-3xl p-8 sm:p-12 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20 mb-6">
                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold mb-4">นโยบายความเป็นส่วนตัว (Privacy Policy)</h1>
            <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
                เอกสารนี้สรุปข้อกำหนดเกี่ยวกับการคุ้มครองข้อมูลส่วนบุคคลของโครงการ ReMind โดยเราให้ความสำคัญกับความเป็นส่วนตัวและข้อมูลสุขภาพจิตของคุณเป็นอันดับแรก
            </p>
        </div>
    </div>

    {{-- Content --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-10 space-y-12">
        
        {{-- Section 1: Controller --}}
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">1</span>
                ผู้ควบคุมข้อมูลส่วนบุคคล
            </h2>
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3"><span class="text-blue-500">🏢</span> <strong>ผู้ควบคุมข้อมูล:</strong> โครงการ ReMind Project</li>
                    <li class="flex items-start gap-3"><span class="text-blue-500">📧</span> <strong>อีเมลติดต่อ:</strong> dpo@remind-project.th</li>
                    <li class="flex items-start gap-3"><span class="text-blue-500">📞</span> <strong>โทรศัพท์:</strong> 02-XXX-XXXX</li>
                </ul>
            </div>
        </section>

        {{-- Section 2 & 3: Data & Purpose --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">2</span>
                    ข้อมูลที่เราจัดเก็บ
                </h2>
                <ul class="space-y-3 text-gray-700 list-disc list-inside ml-2">
                    <li>ข้อมูลระบุตัวตน (ชื่อ, อีเมล ฯลฯ)</li>
                    <li><strong class="text-red-600">ข้อมูลสุขภาพจิต (Sensitive Data)</strong></li>
                    <li>ข้อมูลปัจจัยเสี่ยงต่างๆ</li>
                    <li>ข้อมูลประวัติการใช้งานระบบ</li>
                </ul>
            </section>
            
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">3</span>
                    วัตถุประสงค์ในการจัดเก็บ
                </h2>
                <ul class="space-y-3 text-gray-700 list-disc list-inside ml-2">
                    <li>เพื่อประเมินสุขภาพจิตเบื้องต้น</li>
                    <li>เพื่อคัดกรองความเสี่ยงในภาวะวิกฤต</li>
                    <li>เพื่อติดตามและให้ความช่วยเหลือ</li>
                    <li>เพื่องานวิจัยด้านสาธารณสุข</li>
                </ul>
            </section>
        </div>

        {{-- Section 4 & 5 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">4</span>
                    ฐานกฎหมายที่รองรับ
                </h2>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-semibold border border-indigo-100">Consent (ความยินยอม)</span>
                    <span class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-sm font-semibold border border-red-100">Vital Interest (เพื่อชีวิต)</span>
                    <span class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-sm font-semibold border border-green-100">Public Interest (สาธารณะ)</span>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">5</span>
                    การเปิดเผยข้อมูล
                </h2>
                <ul class="space-y-3 text-gray-700 list-disc list-inside ml-2">
                    <li>ผู้เชี่ยวชาญด้านสุขภาพจิตที่เกี่ยวข้อง</li>
                    <li>หน่วยงานรัฐ (กรณีฉุกเฉิน)</li>
                    <li>นักวิจัย (รูปแบบข้อมูลไม่ระบุตัวตน / Anonymized เท่านั้น)</li>
                </ul>
            </section>
        </div>

        {{-- Section 6 & 7 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">6</span>
                    ระยะเวลาการจัดเก็บ
                </h2>
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="font-bold text-gray-900 mb-1">ผลประเมินสุขภาพจิต</p>
                        <p class="text-sm text-gray-600">จัดเก็บเป็นระยะเวลาสูงสุด <strong>5 ปี</strong></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="font-bold text-gray-900 mb-1">ประวัติการใช้งานทั่วไป</p>
                        <p class="text-sm text-gray-600">จัดเก็บเป็นระยะเวลาสูงสุด <strong>1 ปี</strong></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="font-bold text-gray-900 mb-1">ข้อมูลสำหรับงานวิจัย</p>
                        <p class="text-sm text-gray-600">จัดเก็บตลอดอายุโครงการ (โดยการทำ Anonymize ข้อมูล)</p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">7</span>
                    มาตรการรักษาความปลอดภัย
                </h2>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">🔒</div>
                        <div>
                            <p class="font-bold text-gray-900">การเข้ารหัสลับ (Encryption)</p>
                            <p class="text-sm text-gray-600">ส่งข้อมูลผ่าน TLS/SSL และเข้ารหัสฐานข้อมูล</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">🔑</div>
                        <div>
                            <p class="font-bold text-gray-900">การควบคุมการเข้าถึง (Access Control)</p>
                            <p class="text-sm text-gray-600">จำกัดสิทธิ์การเข้าถึงข้อมูลอย่างเคร่งครัด</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">📝</div>
                        <div>
                            <p class="font-bold text-gray-900">การบันทึกประวัติ (Audit Log)</p>
                            <p class="text-sm text-gray-600">บันทึกประวัติการเข้าใช้งานและเข้าถึงข้อมูลทั้งหมด</p>
                        </div>
                    </li>
                </ul>
            </section>
        </div>

        {{-- Section 8: PDPA Rights --}}
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">8</span>
                สิทธิของคุณตามกฎหมาย PDPA
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="block text-2xl mb-2">👁️</span>
                    <span class="text-sm font-bold text-gray-800">ขอเข้าถึงข้อมูล</span>
                    <span class="block text-xs text-gray-500 mt-1">(Access)</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="block text-2xl mb-2">✍️</span>
                    <span class="text-sm font-bold text-gray-800">ขอแก้ไขข้อมูล</span>
                    <span class="block text-xs text-gray-500 mt-1">(Rectification)</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="block text-2xl mb-2">🗑️</span>
                    <span class="text-sm font-bold text-gray-800">ขอลบข้อมูล</span>
                    <span class="block text-xs text-gray-500 mt-1">(Erasure)</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="block text-2xl mb-2">🛑</span>
                    <span class="text-sm font-bold text-gray-800">ขอระงับการใช้</span>
                    <span class="block text-xs text-gray-500 mt-1">(Restriction)</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="block text-2xl mb-2">📦</span>
                    <span class="text-sm font-bold text-gray-800">ขอโอนย้ายข้อมูล</span>
                    <span class="block text-xs text-gray-500 mt-1">(Portability)</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="block text-2xl mb-2">✋</span>
                    <span class="text-sm font-bold text-gray-800">คัดค้านการเก็บ</span>
                    <span class="block text-xs text-gray-500 mt-1">(Object)</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="block text-2xl mb-2">🔙</span>
                    <span class="text-sm font-bold text-gray-800">ถอนความยินยอม</span>
                    <span class="block text-xs text-gray-500 mt-1">(Withdraw)</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="block text-2xl mb-2">⚖️</span>
                    <span class="text-sm font-bold text-gray-800">ร้องเรียน</span>
                    <span class="block text-xs text-gray-500 mt-1">(Complaint)</span>
                </div>
            </div>
        </section>

        {{-- Section 9, 10, 11 --}}
        <div class="space-y-6">
            <section class="flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0 mt-1">9</div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">การส่งข้อมูลไปต่างประเทศ (International Transfer)</h2>
                    <p class="text-gray-600 text-sm">ดำเนินการโดยปฏิบัติตามมาตรฐาน พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA) มาตรา 28-29 อย่างเคร่งครัด</p>
                </div>
            </section>
            
            <section class="flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0 mt-1">10</div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">นโยบายการใช้คุกกี้ (Cookies)</h2>
                    <p class="text-gray-600 text-sm">เราใช้งานเฉพาะ Necessary Cookies ที่จำเป็นต่อระบบเท่านั้น <span class="text-red-500 font-medium">ไม่มีการใช้ Tracking หรือ Advertising Cookies</span> เพื่อติดตามพฤติกรรมผู้ใช้</p>
                </div>
            </section>
            
            <section class="flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0 mt-1">11</div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">การปรับปรุงนโยบาย (Policy Update)</h2>
                    <p class="text-gray-600 text-sm">หากมีการเปลี่ยนแปลงนโยบาย เราจะแจ้งให้ทราบล่วงหน้าอย่างน้อย 30 วันก่อนการเปลี่ยนแปลงจะมีผล</p>
                </div>
            </section>
        </div>

        {{-- Summary block --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mt-8 relative">
            <div class="absolute -top-3 -right-3 w-8 h-8 bg-blue-600 rounded-full text-white flex items-center justify-center shadow-lg">🛡️</div>
            <h3 class="text-lg font-bold text-blue-900 mb-2">บทสรุป</h3>
            <p class="text-blue-800 text-sm leading-relaxed">
                ระบบ Privacy Policy ของโครงการ ReMind ออกแบบตามมาตรฐาน PDPA ครอบคลุมการเก็บ ใช้ เปิดเผย จัดเก็บ ส่งต่อ และลบข้อมูลส่วนบุคคล โดยให้ความสำคัญเป็นพิเศษกับข้อมูลสุขภาพจิตซึ่งจัดเป็น Sensitive Data พร้อมรองรับสิทธิของเจ้าของข้อมูลและมีมาตรการความปลอดภัยอย่างครบถ้วน
            </p>
        </div>

    </div>
</div>
@endsection
