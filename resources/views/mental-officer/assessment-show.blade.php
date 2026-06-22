@extends('layouts.admin')
@section('title', 'รายละเอียดการประเมินสุขภาพจิต')
@section('page-title')
    <x-heroicon-o-chart-bar class="w-5 h-5 inline-block shrink-0" /> รายละเอียดการประเมินสุขภาพจิต
@endsection
@section('content')

@php
    $questions = match($mentalAssessment->type) {
        'phq9' => [
            'ไม่สนใจหรือไม่มีความสุขกับสิ่งต่างๆ',
            'รู้สึกหดหู่ เศร้า หรือสิ้นหวัง',
            'นอนหลับยากหรือหลับมากเกินไป',
            'รู้สึกเหนื่อยหรือมีพลังงานน้อย',
            'รับประทานอาหารน้อยลงหรือมากเกินไป',
            'รู้สึกไม่ดีกับตัวเอง หรือรู้สึกว่าตัวเองล้มเหลว',
            'มีปัญหาในการมีสมาธิ',
            'เคลื่อนไหวช้าหรือกระสับกระส่าย',
            'มีความคิดที่ว่าตายไปเสียดีกว่า หรือทำร้ายตัวเอง'
        ],
        'gad7' => [
            'รู้สึกประหม่า วิตกกังวล หรือเครียด',
            'ไม่สามารถหยุดหรือควบคุมความกังวลได้',
            'กังวลมากเกินไปเรื่องต่างๆ',
            'มีปัญหาในการผ่อนคลาย',
            'กระสับกระส่ายจนนั่งอยู่เฉยๆ ไม่ได้',
            'หงุดหงิดง่ายหรือรู้สึกหัวร้อน',
            'รู้สึกกลัวว่าจะมีสิ่งเลวร้ายเกิดขึ้น'
        ],
        'ptsd' => [
            'มีความทรงจำที่รบกวนจิตใจ ฝันร้ายเกี่ยวกับเหตุการณ์',
            'พยายามหลีกเลี่ยงความคิดหรือความรู้สึกที่เกี่ยวข้องกับเหตุการณ์',
            'หลีกเลี่ยงสิ่งที่เตือนให้นึกถึงเหตุการณ์',
            'มีความเชื่อแง่ลบเกี่ยวกับตัวเองหรือโลก',
            'รู้สึกผิดหรือโทษตัวเองเกี่ยวกับเหตุการณ์',
            'มีความรู้สึกด้านชาหรือห่างเหิน',
            'ไม่สนใจในกิจกรรมที่เคยชอบ',
            'มีอาการตื่นตัวมากผิดปกติ ตกใจง่าย'
        ],
        default => []
    };

    $titles = [
        'phq9' => 'แบบประเมินภาวะซึมเศร้า (PHQ-9)',
        'gad7' => 'แบบประเมินความวิตกกังวล (GAD-7)',
        'ptsd' => 'แบบประเมินความเครียดหลังเหตุการณ์รุนแรง (PTSD)'
    ];

    $title = $titles[$mentalAssessment->type] ?? 'แบบประเมินสุขภาพจิต';

    $scoreLabels = [
        0 => 'ไม่มีเลย',
        1 => 'มีบางวัน',
        2 => 'มีบ่อย',
        3 => 'มีทุกวัน'
    ];
@endphp

<div class="max-w-4xl mx-auto space-y-6">
    {{-- Back Link --}}
    <a href="{{ route('mental-officer.assessments') }}" class="inline-flex items-center gap-1 text-sm text-purple-600 hover:underline">
        ← กลับไปที่รายการประเมินทั้งหมด
    </a>

    {{-- Overview Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-100">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-700 bg-purple-100 px-2.5 py-1 rounded-full mb-2 inline-block">
                    {{ strtoupper($mentalAssessment->type) }}
                </span>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $title }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    ผู้ประเมิน: <strong>{{ $mentalAssessment->user->name }}</strong> • ทำเมื่อ {{ $mentalAssessment->created_at->format('d/m/Y H:i') }} ({{ $mentalAssessment->created_at->diffForHumans() }})
                </p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="text-center bg-white border border-gray-100 rounded-xl px-4 py-2.5 shadow-sm">
                    <div class="text-2xl font-black text-gray-800">{{ $mentalAssessment->score }}</div>
                    <div class="text-xs text-gray-400 font-medium">คะแนนรวม</div>
                </div>
                @php
                    $color = $mentalAssessment->severity_color;
                    $bgClass = match($color) {
                        'red' => 'bg-red-500 text-white',
                        'orange' => 'bg-orange-500 text-white',
                        'yellow' => 'bg-yellow-500 text-gray-900',
                        'green' => 'bg-green-500 text-white',
                        default => 'bg-gray-500 text-white'
                    };
                @endphp
                <div class="px-5 py-3.5 rounded-xl font-bold text-center {{ $bgClass }} shadow-sm">
                    {{ $mentalAssessment->severity_label }}
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 flex items-center gap-2">
                <x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" /> รายละเอียดคำตอบแต่ละข้อ
            </h2>
            <div class="divide-y divide-gray-100">
                @foreach($questions as $idx => $question)
                    @php
                        $ans = $mentalAssessment->answers[$idx] ?? 0;
                        $ansLabel = $scoreLabels[$ans] ?? 'ไม่มีข้อมูล';
                        $ansColor = match($ans) {
                            3 => 'text-red-600 font-bold bg-red-50 border-red-200',
                            2 => 'text-orange-600 font-semibold bg-orange-50 border-orange-200',
                            1 => 'text-yellow-600 bg-yellow-50 border-yellow-200',
                            default => 'text-green-600 bg-green-50 border-green-200'
                        };
                    @endphp
                    <div class="py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-semibold text-gray-600 flex-shrink-0 mt-0.5">
                                {{ $idx + 1 }}
                            </span>
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $question }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-block px-3 py-1.5 rounded-lg border text-xs {{ $ansColor }}">
                                {{ $ans }} - {{ $ansLabel }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Assessment Context & Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 col-span-2 space-y-4">
            <h3 class="font-bold text-gray-800"><x-heroicon-o-user class="w-5 h-5 inline-block mr-1 -mt-1" /> ข้อมูลผู้ป่วย & ประวัติติดต่อ</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-400 block">ชื่อ-นามสกุล</span>
                    <span class="font-medium text-gray-800">{{ $mentalAssessment->user->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">อีเมล</span>
                    <span class="font-medium text-gray-800">{{ $mentalAssessment->user->email }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">เบอร์โทรศัพท์</span>
                    <span class="font-medium text-gray-800">{{ $mentalAssessment->user->phone ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">ที่อยู่ / จังหวัด</span>
                    <span class="font-medium text-gray-800">{{ $mentalAssessment->user->province ?? '-' }} {{ $mentalAssessment->user->district }}</span>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <a href="tel:{{ $mentalAssessment->user->phone }}" class="flex-1 py-3 bg-purple-600 text-white font-bold text-center rounded-xl hover:bg-purple-700 transition-colors text-sm">
                    <x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /> โทรหาผู้ประเมิน
                </a>
                <a href="{{ route('mental-officer.appointments') }}" class="flex-1 py-3 bg-purple-50 text-purple-700 font-bold text-center rounded-xl hover:bg-purple-100 transition-colors text-sm">
                    <x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> ทำนัดหมายผู้เชี่ยวชาญ
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-4">
            <h3 class="font-bold text-gray-800"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> แนวทางปฏิบัติ</h3>
            <div class="space-y-3 text-xs text-gray-600">
                @if($mentalAssessment->severity == 'severe')
                    <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 font-medium">
                        <x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /> ระดับความรุนแรงสูงมาก ควรติดต่อแพทย์ด่วนและเฝ้าระวังความปลอดภัยของผู้ป่วยตลอด 24 ชั่วโมง
                    </div>
                @elseif($mentalAssessment->severity == 'moderate')
                    <div class="p-3 bg-orange-50 border border-orange-200 rounded-xl text-orange-700 font-medium">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /> ระดับความรุนแรงปานกลาง ควรนัดแนะพูดคุย หรือให้คำแนะนำเบื้องต้นเพื่อติดตามผลอย่างใกล้ชิด
                    </div>
                @else
                    <div class="p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 font-medium">
                        <x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> ระดับความรุนแรงน้อย-ปกติ แนะนำให้ทำกิจกรรมผ่อนคลายและประเมินผลอีกครั้งหลังผ่านไป 14 วัน
                    </div>
                @endif
                <p class="leading-relaxed">
                    ผลการวิเคราะห์นี้เป็นข้อมูลเบื้องต้นจากการทำแบบทดสอบทางสุขภาพจิตออนไลน์ ควรใช้ประกอบการประเมินเบื้องต้นโดยนักจิตวิทยาหรือจิตแพทย์เท่านั้น
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
