@extends('layouts.app')

@section('title', 'สอนการใช้งานระบบ')
@section('page-title')
    สอนการใช้งานระบบ
@endsection

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">สอนการใช้งานระบบ RescueMind</h2>
        <p class="text-gray-600">ระบบบริหารจัดการภัยพิบัติและฟื้นฟูสุขภาพจิต แบ่งออกเป็น 3 ระยะ</p>
    </div>
    
    <div class="space-y-6">
        <div class="p-6 border-l-4 border-blue-500 rounded-xl bg-blue-50/50 shadow-sm transition hover:shadow-md">
            <div class="flex items-start gap-4">
                <div class="text-4xl"><x-heroicon-o-globe-asia-australia class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                <div>
                    <h3 class="text-xl font-bold text-blue-700 mb-2">MT1 ก่อนเกิดภัย (Early Warning & Preparedness)</h3>
                    <p class="text-gray-700 mb-3 text-sm leading-relaxed">
                        การเตรียมพร้อมก่อนเกิดภัยพิบัติเพื่อลดความสูญเสียให้มากที่สุด:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li><strong>การแจ้งเตือน:</strong> ติดตามประกาศและข่าวสารภัยพิบัติในพื้นที่ของคุณ</li>
                        <li><strong>จุดช่วยเหลือ:</strong> ค้นหาที่ตั้งของศูนย์พักพิงและโรงพยาบาลที่ใกล้ที่สุด</li>
                        <li><strong>การเตรียมพร้อม:</strong> ตรวจสอบรายการสิ่งของจำเป็นและสิ่งที่ต้องทำก่อนเกิดเหตุ (Checklist)</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('preparedness.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">ไปยังส่วน MT1</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-l-4 border-red-500 rounded-xl bg-red-50/50 shadow-sm transition hover:shadow-md">
            <div class="flex items-start gap-4">
                <div class="text-4xl"><x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /></div>
                <div>
                    <h3 class="text-xl font-bold text-red-700 mb-2">MT2 ระหว่างเกิดภัย (Emergency Response)</h3>
                    <p class="text-gray-700 mb-3 text-sm leading-relaxed">
                        การรับมือเหตุฉุกเฉินและขอความช่วยเหลืออย่างทันท่วงที:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li><strong>ขอความช่วยเหลือ (SOS):</strong> ส่งตำแหน่งพิกัดและรายละเอียดของคุณให้เจ้าหน้าที่ทันที</li>
                        <li><strong>รายงานภัย:</strong> แจ้งเหตุการณ์หรืออันตรายที่พบเห็นเพื่อให้ส่วนรวมระวัง</li>
                        <li><strong>แจ้งคนหาย:</strong> แจ้งข้อมูลบุคคลสูญหายเพื่อให้เครือข่ายช่วยกันค้นหา</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('sos.create') }}" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">กด SOS ขอความช่วยเหลือ</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-l-4 border-purple-500 rounded-xl bg-purple-50/50 shadow-sm transition hover:shadow-md">
            <div class="flex items-start gap-4">
                <div class="text-4xl"><x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" /></div>
                <div>
                    <h3 class="text-xl font-bold text-purple-700 mb-2">MT3 หลังเกิดภัย (Recovery & Mental Health)</h3>
                    <p class="text-gray-700 mb-3 text-sm leading-relaxed">
                        การฟื้นฟูสภาพจิตใจและเยียวยาหลังผ่านเหตุการณ์เลวร้าย:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li><strong>ศูนย์สุขภาพจิต:</strong> ทำแบบประเมินความเครียดและซึมเศร้าด้วยตัวเอง</li>
                        <li><strong>บันทึกความรู้สึก:</strong> ติดตามอารมณ์ในแต่ละวัน (Mood Tracker) และเขียนระบายความรู้สึก</li>
                        <li><strong>ปรึกษาผู้เชี่ยวชาญ:</strong> นัดหมายพูดคุยกับนักจิตวิทยาเพื่อรับคำแนะนำ</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('mental.index') }}" class="inline-block px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition">ประเมินสุขภาพจิต</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
