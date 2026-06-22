@extends('layouts.app')

@section('title', 'นโยบายความเป็นส่วนตัว (Privacy Policy)')
@section('page-title')
    นโยบายความเป็นส่วนตัว
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    @php
        $backUrl = url()->previous() !== url()->current() ? url()->previous() : route('home');
        if (request('from_pdpa')) {
            $backUrl .= (parse_url($backUrl, PHP_URL_QUERY) ? '&' : '?') . 'show_consent=1';
            if (request('consent_url')) {
                $backUrl .= '&consent_url=' . urlencode(request('consent_url'));
            }
        }
    @endphp

    {{-- Back Button --}}
    <div>
        <a href="{{ $backUrl }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ url()->previous() !== url()->current() ? 'ย้อนกลับ' : 'ย้อนกลับหน้าหลัก' }}
        </a>
    </div>
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-blue-900 to-indigo-900 rounded-3xl p-8 sm:p-12 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center justify-center px-4 py-1.5 bg-white/10 rounded-full backdrop-blur-sm border border-white/20 mb-6 text-sm font-bold text-blue-100">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                PDPA Compliant
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold mb-2">นโยบายคุ้มครองข้อมูลส่วนบุคคล</h1>
            <h2 class="text-xl sm:text-2xl text-blue-200 mb-6 font-semibold">Privacy Policy — โครงการ ReMind</h2>
            
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 text-sm text-blue-100/80 mb-6 bg-white/5 rounded-2xl p-4 border border-white/10 w-fit">
                <div>
                    <strong class="block text-white mb-0.5">มีผลบังคับใช้ตั้งแต่:</strong> 1 มกราคม 2567
                </div>
                <div>
                    <strong class="block text-white mb-0.5">ปรับปรุงล่าสุด:</strong> 1 มิถุนายน 2568
                </div>
                <div>
                    <strong class="block text-white mb-0.5">อ้างอิง:</strong> พ.ร.บ. PDPA พ.ศ. 2562
                </div>
            </div>

            <p class="text-blue-50 text-base max-w-3xl leading-relaxed">
                โครงการ ReMind ให้ความสำคัญอย่างยิ่งต่อความเป็นส่วนตัวของท่าน เอกสารฉบับนี้อธิบายวิธีการที่เราเก็บรวบรวม ใช้ และคุ้มครองข้อมูลส่วนบุคคลของท่าน ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562
            </p>
        </div>
    </div>

    {{-- Content --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-10 space-y-12">
        
        {{-- Section 1: Controller --}}
        <section id="section-1">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">1</span>
                ผู้ควบคุมข้อมูลส่วนบุคคล
            </h2>
            <p class="text-gray-600 mb-4 ml-11">ผู้ควบคุมข้อมูลส่วนบุคคลตามนโยบายนี้คือ โครงการ ReMind ซึ่งดำเนินงานโดยหน่วยงานที่รับผิดชอบการประเมินและดูแลสุขภาพจิตในชุมชน</p>
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 ml-11">
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-center gap-3"><span class="text-blue-500 text-lg w-6 text-center"><x-heroicon-o-building-office class="w-5 h-5 inline-block mr-1 -mt-1" /></span> <strong>ชื่อโครงการ:</strong> ReMind</li>
                    <li class="flex items-center gap-3"><span class="text-blue-500 text-lg w-6 text-center"><x-heroicon-o-envelope class="w-5 h-5 inline-block mr-1 -mt-1" /></span> <strong>อีเมลติดต่อ:</strong> dpo@remind-project.th</li>
                    <li class="flex items-center gap-3"><span class="text-blue-500 text-lg w-6 text-center"><x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /></span> <strong>เบอร์โทรศัพท์:</strong> 02-XXX-XXXX</li>
                </ul>
            </div>
        </section>

        {{-- Section 2: Data Collected --}}
        <section id="section-2">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">2</span>
                ข้อมูลที่เก็บรวบรวม
            </h2>
            <p class="text-gray-600 mb-6 ml-11">เราเก็บรวบรวมข้อมูลประเภทต่าง ๆ ดังนี้</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-11">
                <div class="p-5 border border-gray-100 rounded-xl bg-white shadow-sm hover:border-blue-200 transition-colors">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-2"><span class="text-xl"><x-heroicon-o-identification class="w-5 h-5 inline-block mr-1 -mt-1" /></span> ข้อมูลระบุตัวตน</h3>
                    <p class="text-sm text-gray-600">ชื่อ นามสกุล เพศ อายุ ที่อยู่ เบอร์โทรศัพท์</p>
                </div>
                
                <div class="p-5 border border-red-100 rounded-xl bg-red-50/50 shadow-sm hover:border-red-200 transition-colors">
                    <h3 class="font-bold text-red-800 flex items-center gap-2 mb-2"><span class="text-xl"><x-heroicon-o-heart class="w-5 h-5 inline-block mr-1 -mt-1" /></span> ข้อมูลสุขภาพ (ข้อมูลอ่อนไหว)</h3>
                    <p class="text-sm text-red-700/80 leading-relaxed">ผลการประเมินภาวะความเครียด ซึมเศร้า และความเสี่ยงด้านสุขภาพจิต ซึ่งถือเป็นข้อมูลอ่อนไหวตาม PDPA มาตรา 26 โครงการจะเก็บข้อมูลนี้ด้วยความระมัดระวังเป็นพิเศษและจะขอความยินยอมโดยชัดแจ้ง</p>
                </div>

                <div class="p-5 border border-gray-100 rounded-xl bg-white shadow-sm hover:border-orange-200 transition-colors">
                    <h3 class="font-bold text-orange-800 flex items-center gap-2 mb-2"><span class="text-xl"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block mr-1 -mt-1" />️</span> ข้อมูลปัจจัยเสี่ยง</h3>
                    <p class="text-sm text-gray-600">สถานการณ์ที่อาจส่งผลต่อสุขภาพจิต เช่น การประสบภัยพิบัติ ความสูญเสีย ภาวะเครียดสะสม</p>
                </div>

                <div class="p-5 border border-gray-100 rounded-xl bg-white shadow-sm hover:border-blue-200 transition-colors">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-2"><span class="text-xl"><x-heroicon-o-chart-bar class="w-5 h-5 inline-block mr-1 -mt-1" /></span> ข้อมูลการใช้งานระบบ</h3>
                    <p class="text-sm text-gray-600">ประวัติการทำแบบประเมิน วันและเวลา IP address (เพื่อความปลอดภัยเท่านั้น)</p>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 ml-11 flex items-start gap-3">
                <span class="text-amber-500 text-xl shrink-0 mt-0.5"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block mr-1 -mt-1" />️</span>
                <p class="text-amber-800 text-sm leading-relaxed"><strong>ข้อมูลสุขภาพจัดเป็นข้อมูลอ่อนไหว (Sensitive Data)</strong> ตาม พ.ร.บ. PDPA มาตรา 26 โครงการจะดำเนินการด้วยความระมัดระวังเป็นพิเศษ</p>
            </div>
        </section>

        {{-- Section 3: Purpose & Legal Basis --}}
        <section id="section-3">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">3</span>
                วัตถุประสงค์และฐานทางกฎหมาย
            </h2>
            <p class="text-gray-600 mb-6 ml-11">การประมวลผลข้อมูลของท่านอาศัยฐานทางกฎหมายดังนี้ตามมาตรา 24 และ 26</p>
            
            <div class="overflow-x-auto ml-11">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-sm">
                            <th class="p-4 border-b border-gray-200 font-bold rounded-tl-xl">วัตถุประสงค์</th>
                            <th class="p-4 border-b border-gray-200 font-bold rounded-tr-xl">ฐานทางกฎหมาย</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600">
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                            <td class="p-4">ประเมินสุขภาพจิตและคัดกรองความเสี่ยง</td>
                            <td class="p-4 font-semibold text-blue-600">ความยินยอม (ม.19)</td>
                        </tr>
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                            <td class="p-4">ติดตามดูแลและช่วยเหลือผู้มีความเสี่ยง</td>
                            <td class="p-4 font-semibold text-red-600">ประโยชน์สำคัญต่อชีวิต (ม.24(4))</td>
                        </tr>
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                            <td class="p-4">พัฒนาฐานข้อมูลสาธารณสุข (ไม่ระบุตัวตน)</td>
                            <td class="p-4 font-semibold text-green-600">ประโยชน์สาธารณะ / งานวิจัย (ม.24(5))</td>
                        </tr>
                        <tr class="hover:bg-gray-50/50">
                            <td class="p-4 rounded-bl-xl">ส่งข้อมูลให้ผู้เชี่ยวชาญที่เกี่ยวข้อง</td>
                            <td class="p-4 font-semibold text-blue-600 rounded-br-xl">ความยินยอม (ม.19)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Section 4: Third Parties --}}
        <section id="section-4">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">4</span>
                การเปิดเผยข้อมูลแก่บุคคลที่สาม
            </h2>
            <p class="text-gray-600 mb-6 ml-11">โครงการ ReMind จะไม่ขายหรือให้เช่าข้อมูลส่วนบุคคลของท่านแก่บุคคลภายนอก อย่างไรก็ตาม อาจมีการเปิดเผยข้อมูลในกรณีดังต่อไปนี้</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 ml-11">
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 text-center">
                    <div class="text-4xl mb-3"><x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" />‍<x-heroicon-o-heart class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <h3 class="font-bold text-gray-800 mb-1">ผู้เชี่ยวชาญด้านสุขภาพจิต</h3>
                    <div class="text-xs font-bold text-blue-600 bg-blue-50 py-1 px-2 rounded-lg inline-block mb-3 border border-blue-100">ความยินยอม / ประโยชน์ชีวิต</div>
                    <p class="text-xs text-gray-500">เฉพาะกรณีที่ท่านมีความเสี่ยงและต้องการความช่วยเหลือเร่งด่วน</p>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 text-center">
                    <div class="text-4xl mb-3"><x-heroicon-o-building-library class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <h3 class="font-bold text-gray-800 mb-1">หน่วยงานราชการ</h3>
                    <div class="text-xs font-bold text-amber-600 bg-amber-50 py-1 px-2 rounded-lg inline-block mb-3 border border-amber-100">หน้าที่ตามกฎหมาย</div>
                    <p class="text-xs text-gray-500">เมื่อมีคำสั่งศาลหรือข้อบังคับทางกฎหมาย</p>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 text-center">
                    <div class="text-4xl mb-3"><x-heroicon-o-beaker class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <h3 class="font-bold text-gray-800 mb-1">นักวิจัย / สถาบันวิชาการ</h3>
                    <div class="text-xs font-bold text-green-600 bg-green-50 py-1 px-2 rounded-lg inline-block mb-3 border border-green-100">ประโยชน์สาธารณะ</div>
                    <p class="text-xs text-gray-500">ข้อมูลที่ผ่านกระบวนการ de-identification ไม่สามารถระบุตัวตนได้</p>
                </div>
            </div>
        </section>

        {{-- Section 5: Retention --}}
        <section id="section-5">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">5</span>
                ระยะเวลาการเก็บรักษาข้อมูล
            </h2>
            <p class="text-gray-600 mb-6 ml-11">เราจะเก็บรักษาข้อมูลส่วนบุคคลของท่านตามระยะเวลาที่จำเป็นเพื่อบรรลุวัตถุประสงค์ที่ระบุไว้ในนโยบายนี้</p>
            
            <div class="ml-11 space-y-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-white border border-gray-100 p-4 rounded-xl shadow-sm">
                    <div class="bg-indigo-50 text-indigo-700 font-black text-lg px-4 py-2 rounded-lg border border-indigo-100 shrink-0 w-24 text-center">5 ปี</div>
                    <div>
                        <h4 class="font-bold text-gray-800">ข้อมูลผลการประเมิน</h4>
                        <p class="text-sm text-gray-500">นับจากวันที่ทำแบบประเมินครั้งล่าสุด</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-white border border-gray-100 p-4 rounded-xl shadow-sm">
                    <div class="bg-blue-50 text-blue-700 font-black text-lg px-4 py-2 rounded-lg border border-blue-100 shrink-0 w-24 text-center">1 ปี</div>
                    <div>
                        <h4 class="font-bold text-gray-800">ข้อมูลประวัติการใช้งาน</h4>
                        <p class="text-sm text-gray-500">นับจากวันสุดท้ายที่ใช้งานระบบ</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-white border border-gray-100 p-4 rounded-xl shadow-sm">
                    <div class="bg-green-50 text-green-700 font-black text-sm px-2 py-2 rounded-lg border border-green-100 shrink-0 w-24 text-center leading-tight flex items-center justify-center min-h-[44px]">ตลอดโครงการ</div>
                    <div>
                        <h4 class="font-bold text-gray-800">ข้อมูลเพื่อการวิจัย</h4>
                        <p class="text-sm text-gray-500">หลังจาก anonymize แล้ว</p>
                    </div>
                </div>
            </div>
            <p class="text-gray-500 text-sm italic ml-11 mt-4">เมื่อครบกำหนดเวลา ข้อมูลจะถูกลบหรือทำให้ไม่สามารถระบุตัวตนได้ (anonymization) ตามมาตรฐานสากล</p>
        </section>

        {{-- Section 6: Security --}}
        <section id="section-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">6</span>
                มาตรการรักษาความปลอดภัย
            </h2>
            <p class="text-gray-600 mb-6 ml-11">โครงการ ReMind ใช้มาตรการทางเทคนิคและองค์กรที่เหมาะสมเพื่อปกป้องข้อมูลส่วนบุคคลของท่าน</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ml-11">
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="text-3xl"><x-heroicon-o-lock-closed class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <div>
                        <h4 class="font-bold text-gray-800">เข้ารหัส TLS/SSL</h4>
                        <p class="text-xs text-gray-500">การส่งข้อมูลทั้งหมด</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="text-3xl"><x-heroicon-o-archive-box class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <div>
                        <h4 class="font-bold text-gray-800">เข้ารหัส Database</h4>
                        <p class="text-xs text-gray-500">ข้อมูลที่จัดเก็บ</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="text-3xl"><x-heroicon-o-users class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <div>
                        <h4 class="font-bold text-gray-800">Access Control</h4>
                        <p class="text-xs text-gray-500">จำกัดการเข้าถึง</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="text-3xl"><x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <div>
                        <h4 class="font-bold text-gray-800">Audit Log</h4>
                        <p class="text-xs text-gray-500">บันทึกการเข้าใช้งาน</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section 7: PDPA Rights --}}
        <section id="section-7">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">7</span>
                    สิทธิของเจ้าของข้อมูลส่วนบุคคล
                </h2>
                <span class="text-xs font-bold bg-blue-100 text-blue-800 px-3 py-1 rounded-full w-fit ml-11 sm:ml-0">ตาม PDPA มาตรา 30–43</span>
            </div>
            <p class="text-gray-600 mb-6 ml-11">ท่านมีสิทธิต่าง ๆ เกี่ยวกับข้อมูลส่วนบุคคลของท่าน ตามที่บัญญัติใน พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 ดังนี้</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ml-11">
                <div class="bg-white border border-gray-200 p-4 rounded-xl flex gap-4 hover:border-blue-300 hover:shadow-sm transition-all">
                    <div class="text-2xl pt-1"><x-heroicon-o-eye class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <div>
                        <h4 class="font-bold text-gray-800 flex items-center justify-between">
                            สิทธิในการเข้าถึง
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">มาตรา 30</span>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">ขอดูข้อมูลส่วนบุคคลของตนเองที่โครงการเก็บรักษาไว้</p>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 p-4 rounded-xl flex gap-4 hover:border-blue-300 hover:shadow-sm transition-all">
                    <div class="text-2xl pt-1"><x-heroicon-o-pencil class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <div>
                        <h4 class="font-bold text-gray-800 flex items-center justify-between">
                            สิทธิในการแก้ไข
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">มาตรา 35</span>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">ขอแก้ไขข้อมูลที่ไม่ถูกต้องหรือไม่ครบถ้วนให้เป็นปัจจุบัน</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-4 rounded-xl flex gap-4 hover:border-blue-300 hover:shadow-sm transition-all">
                    <div class="text-2xl pt-1"><x-heroicon-o-trash class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <div>
                        <h4 class="font-bold text-gray-800 flex items-center justify-between">
                            สิทธิในการลบ
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">มาตรา 33</span>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">ขอให้ลบหรือทำลายข้อมูลเมื่อไม่มีความจำเป็นต้องเก็บไว้อีกต่อไป</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-4 rounded-xl flex gap-4 hover:border-blue-300 hover:shadow-sm transition-all">
                    <div class="text-2xl pt-1"><x-heroicon-o-pause class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <div>
                        <h4 class="font-bold text-gray-800 flex items-center justify-between">
                            สิทธิในการระงับ
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">มาตรา 34</span>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">ขอให้ระงับการใช้ข้อมูลชั่วคราวในระหว่างการตรวจสอบ</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-4 rounded-xl flex gap-4 hover:border-blue-300 hover:shadow-sm transition-all">
                    <div class="text-2xl pt-1"><x-heroicon-o-archive-box class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <div>
                        <h4 class="font-bold text-gray-800 flex items-center justify-between">
                            สิทธิในการโอนย้าย
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">มาตรา 36</span>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">ขอรับข้อมูลในรูปแบบที่อ่านได้ด้วยเครื่องและโอนไปยังผู้ควบคุมรายอื่น</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-4 rounded-xl flex gap-4 hover:border-blue-300 hover:shadow-sm transition-all">
                    <div class="text-2xl pt-1"><x-heroicon-o-no-symbol class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <div>
                        <h4 class="font-bold text-gray-800 flex items-center justify-between">
                            สิทธิในการคัดค้าน
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">มาตรา 38</span>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">คัดค้านการเก็บรวบรวม ใช้ หรือเปิดเผยข้อมูลได้ทุกเมื่อ</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-4 rounded-xl flex gap-4 hover:border-blue-300 hover:shadow-sm transition-all">
                    <div class="text-2xl pt-1"><x-heroicon-o-arrow-uturn-left class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <div>
                        <h4 class="font-bold text-gray-800 flex items-center justify-between">
                            สิทธิถอนความยินยอม
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">มาตรา 19</span>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">ถอนความยินยอมได้ทุกเมื่อ โดยไม่กระทบต่อการประมวลผลที่ชอบด้วยกฎหมายก่อนหน้า</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-4 rounded-xl flex gap-4 hover:border-blue-300 hover:shadow-sm transition-all">
                    <div class="text-2xl pt-1"><x-heroicon-o-scale class="w-5 h-5 inline-block mr-1 -mt-1" />️</div>
                    <div>
                        <h4 class="font-bold text-gray-800 flex items-center justify-between">
                            สิทธิในการร้องเรียน
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">มาตรา 73</span>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">ร้องเรียนต่อสำนักงานคณะกรรมการคุ้มครองข้อมูลส่วนบุคคลหากพบการละเมิด</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mt-6 ml-11">
                <h4 class="font-bold text-blue-900 mb-2">วิธีการใช้สิทธิ์</h4>
                <p class="text-sm text-blue-800 leading-relaxed">
                    ส่งคำร้องเป็นลายลักษณ์อักษรมายัง <strong>dpo@remind-project.th</strong> พร้อมสำเนาบัตรประจำตัวประชาชน โครงการจะดำเนินการภายใน 30 วัน นับจากวันที่ได้รับคำร้อง หากต้องการข้อมูลเพิ่มเติมสามารถโทร 02-XXX-XXXX
                </p>
            </div>
        </section>

        {{-- Section 8, 9, 10 --}}
        <div class="space-y-8">
            <section id="section-8">
                <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">8</span>
                    การส่งข้อมูลไปต่างประเทศ
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed ml-11">
                    โดยปกติโครงการ ReMind จัดเก็บและประมวลผลข้อมูลภายในประเทศไทยเป็นหลัก หากมีความจำเป็นต้องส่งข้อมูลไปยังต่างประเทศ (เช่น บริการ Cloud ที่ใช้เซิร์ฟเวอร์ต่างประเทศ) โครงการจะดำเนินการตาม PDPA มาตรา 28 และ 29 โดยตรวจสอบว่าประเทศปลายทางมีมาตรฐานการคุ้มครองข้อมูลที่เพียงพอ
                </p>
            </section>
            
            <section id="section-9">
                <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">9</span>
                    คุกกี้และเทคโนโลยีติดตาม
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed ml-11">
                    ระบบใช้คุกกี้เพื่อการทำงานที่จำเป็น (Strictly Necessary Cookies) เท่านั้น เช่น Session Token เพื่อรักษาสถานะการเข้าสู่ระบบ ไม่มีการใช้คุกกี้เพื่อติดตามพฤติกรรมหรือโฆษณา
                </p>
            </section>
            
            <section id="section-10">
                <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">10</span>
                    การเปลี่ยนแปลงนโยบาย
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed ml-11">
                    โครงการ ReMind ขอสงวนสิทธิ์ในการปรับปรุงนโยบายนี้เป็นครั้งคราว การเปลี่ยนแปลงที่สำคัญจะแจ้งให้ท่านทราบผ่านระบบหรืออีเมลที่ท่านลงทะเบียนไว้ล่วงหน้าอย่างน้อย 30 วันก่อนมีผลบังคับใช้
                </p>
            </section>
        </div>

        {{-- Section 11: Contact DPO --}}
        <section id="section-11">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">11</span>
                ติดต่อผู้ควบคุมข้อมูล (DPO)
            </h2>
            <p class="text-gray-600 mb-6 ml-11">หากท่านมีคำถาม ข้อสงสัย หรือต้องการใช้สิทธิ์ใด ๆ สามารถติดต่อเจ้าหน้าที่คุ้มครองข้อมูลส่วนบุคคล (Data Protection Officer) ได้ตามช่องทางด้านล่าง</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 ml-11 mb-6">
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center hover:border-blue-200 transition-colors">
                    <div class="text-2xl mb-2"><x-heroicon-o-envelope class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <h4 class="font-bold text-gray-800 text-sm mb-1">อีเมล</h4>
                    <p class="text-xs text-blue-600 font-medium">dpo@remind-project.th</p>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center hover:border-blue-200 transition-colors">
                    <div class="text-2xl mb-2"><x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <h4 class="font-bold text-gray-800 text-sm mb-1">โทรศัพท์</h4>
                    <p class="text-xs text-gray-600">02-XXX-XXXX<br><span class="text-[10px] text-gray-400">(วันทำการ 08:30–16:30)</span></p>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center hover:border-blue-200 transition-colors">
                    <div class="text-2xl mb-2"><x-heroicon-o-inbox class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <h4 class="font-bold text-gray-800 text-sm mb-1">จดหมาย</h4>
                    <p class="text-xs text-gray-600">ฝ่าย DPO โครงการ ReMind<br>[ที่อยู่หน่วยงาน]</p>
                </div>
            </div>

            <div class="ml-11 bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-start gap-3">
                <span class="text-xl shrink-0 mt-0.5"><x-heroicon-o-scale class="w-5 h-5 inline-block mr-1 -mt-1" />️</span>
                <p class="text-sm text-indigo-900 leading-relaxed">
                    นอกจากนี้ท่านมีสิทธิ์ร้องเรียนต่อ <strong>สำนักงานคณะกรรมการคุ้มครองข้อมูลส่วนบุคคล (PDPC)</strong> ได้ที่ <a href="https://www.pdpc.or.th" target="_blank" class="text-blue-600 hover:underline">www.pdpc.or.th</a>
                </p>
            </div>
        </section>

        {{-- Bottom Back Button --}}
        <div class="flex justify-center pt-8 border-t border-gray-100">
            <a href="{{ $backUrl }}" class="group inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all text-sm">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ url()->previous() !== url()->current() ? 'ย้อนกลับ' : 'ย้อนกลับหน้าหลัก' }}
            </a>
        </div>

    </div>
</div>
@endsection
