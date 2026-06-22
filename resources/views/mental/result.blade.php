@extends('layouts.app')
@section('title', 'ผลการประเมิน')
@section('page-title')
    <x-heroicon-o-chart-bar class="w-5 h-5 inline-block shrink-0" /> ผลการประเมินสุขภาพจิต
@endsection
@section('content')
@php
    $labels = [0 => 'ไม่เลย', 1 => 'เล็กน้อย', 2 => 'ปานกลาง', 3 => 'มาก'];
@endphp
<div class="max-w-3xl mx-auto" x-data="{ showSevereModal: {{ $mentalAssessment->severity == 'severe' ? 'true' : 'false' }} }">
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
        {{-- Result Header --}}
        <div class="p-8 md:p-12 text-center relative overflow-hidden
            {{ $mentalAssessment->severity == 'severe' ? 'bg-gradient-to-br from-red-50 to-red-100' : ($mentalAssessment->severity == 'moderate' ? 'bg-gradient-to-br from-orange-50 to-orange-100' : ($mentalAssessment->severity == 'mild' ? 'bg-gradient-to-br from-yellow-50 to-yellow-100' : 'bg-gradient-to-br from-green-50 to-green-100')) }}">
            
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-40 rounded-full filter blur-2xl"></div>
            
            <div class="relative z-10">
                @php
                    $severityColor = 'text-green-500';
                    $severityIcon = 'heroicon-o-check-circle';
                    if ($mentalAssessment->severity == 'severe') {
                        $severityColor = 'text-red-500';
                        $severityIcon = 'heroicon-o-exclamation-triangle';
                    } elseif ($mentalAssessment->severity == 'moderate') {
                        $severityColor = 'text-orange-500';
                        $severityIcon = 'heroicon-o-exclamation-circle';
                    } elseif ($mentalAssessment->severity == 'mild') {
                        $severityColor = 'text-yellow-500';
                        $severityIcon = 'heroicon-o-information-circle';
                    }
                @endphp
                <div class="flex justify-center text-6xl md:text-7xl mb-4 drop-shadow-sm {{ $severityColor }}">
                    <x-dynamic-component :component="$severityIcon" class="w-20 h-20 md:w-28 md:h-28 mx-auto drop-shadow-md" />
                </div>
                <div class="text-5xl font-black text-gray-800 mb-2 tracking-tight">{{ $mentalAssessment->score }} <span class="text-2xl font-bold text-gray-500">คะแนน</span></div>
                <div class="text-2xl font-black mt-3
                    {{ $mentalAssessment->severity == 'severe' ? 'text-red-600' : ($mentalAssessment->severity == 'moderate' ? 'text-orange-600' : ($mentalAssessment->severity == 'mild' ? 'text-yellow-600' : 'text-green-600')) }}">
                    {{ $mentalAssessment->severity_label }}
                </div>
                <div class="text-gray-500 font-medium text-sm mt-3 bg-white/50 inline-block px-4 py-1.5 rounded-full backdrop-blur-sm">{{ strtoupper($mentalAssessment->type) }} — {{ $mentalAssessment->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="p-6 md:p-10">
            
            {{-- Recommendations Box --}}
            <div class="bg-orange-50/50 border border-orange-200 rounded-2xl p-6 md:p-8 mb-8 shadow-sm">
                <h3 class="font-black text-gray-800 mb-4 text-lg">คำแนะนำสำหรับคุณ</h3>
                <ul class="space-y-4 text-gray-700">
                    @if($mentalAssessment->severity == 'severe')
                        <li class="flex items-start gap-3"><span class="text-red-500 mt-1">●</span> <strong>ควรพบแพทย์หรือจิตแพทย์โดยด่วนเพื่อรับการประเมินและรักษาอย่างเหมาะสม</strong></li>
                        <li class="flex items-start gap-3"><span class="text-orange-500 mt-1">●</span> โทรปรึกษาสายด่วนสุขภาพจิต 1323 ได้ตลอด 24 ชั่วโมง</li>
                        <li class="flex items-start gap-3"><span class="text-orange-500 mt-1">●</span> หลีกเลี่ยงการอยู่คนเดียว ควรมีคนใกล้ชิดคอยดูแล</li>
                        <li class="flex items-start gap-3"><span class="text-orange-500 mt-1">●</span> ลองพูดคุยระบายความเครียดกับ AI ของเราเพื่อบรรเทาความรู้สึกเบื้องต้น</li>
                    @elseif($mentalAssessment->severity == 'moderate')
                        <li class="flex items-start gap-3"><span class="text-orange-500 mt-1">●</span> พบแพทย์หรือเจ้าหน้าที่สาธารณสุขภายใน 7 วัน</li>
                        <li class="flex items-start gap-3"><span class="text-orange-500 mt-1">●</span> หลีกเลี่ยงความเครียดสะสมและพักผ่อนให้เพียงพอ</li>
                        <li class="flex items-start gap-3"><span class="text-orange-500 mt-1">●</span> ติดต่อ อสม. ในพื้นที่เพื่อขอคำแนะนำเพิ่มเติม</li>
                        <li class="flex items-start gap-3"><span class="text-orange-500 mt-1">●</span> บันทึกอาการที่พบลงในสมุดบันทึกประจำวันเพื่อติดตามผล</li>
                    @elseif($mentalAssessment->severity == 'mild')
                        <li class="flex items-start gap-3"><span class="text-yellow-500 mt-1">●</span> ฝึกหายใจลึกๆ และทำกิจกรรมผ่อนคลายเพื่อลดความเครียด</li>
                        <li class="flex items-start gap-3"><span class="text-yellow-500 mt-1">●</span> พักผ่อนให้เพียงพอและทานอาหารที่มีประโยชน์</li>
                        <li class="flex items-start gap-3"><span class="text-yellow-500 mt-1">●</span> หากมีอาการรุนแรงขึ้น ควรปรึกษาคนใกล้ชิดหรือผู้เชี่ยวชาญ</li>
                    @else
                        <li class="flex items-start gap-3"><span class="text-green-500 mt-1">●</span> สุขภาพจิตโดยรวมอยู่ในเกณฑ์ดี ควรดูแลสุขภาพกายและใจต่อไป</li>
                        <li class="flex items-start gap-3"><span class="text-green-500 mt-1">●</span> สามารถทำกิจกรรมเพื่อสังคมหรือช่วยเหลือผู้อื่นได้</li>
                    @endif
                </ul>
            </div>

            {{-- Answer History --}}
            @if(isset($questions) && !empty($questions))
            <div class="mb-10">
                <h3 class="font-black text-gray-800 mb-6 text-lg">ประวัติคำตอบของคุณ</h3>
                <div class="space-y-0 divide-y divide-gray-100 border border-gray-100 rounded-2xl p-4 md:p-6 bg-white shadow-sm">
                    @foreach($questions as $index => $question)
                        @php
                            $val = $mentalAssessment->answers[$index] ?? 0;
                        @endphp
                        <div class="py-4 first:pt-0 last:pb-0">
                            <p class="font-semibold text-gray-800 mb-2">{{ $index + 1 }}. {{ $question }}</p>
                            <p class="text-sm text-gray-500">
                                คำตอบ: <span class="font-bold text-blue-600">{{ $labels[$val] ?? '-' }}</span> <span class="text-gray-400 ml-2 font-medium">(คะแนน: {{ $val }})</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(in_array($mentalAssessment->severity, ['moderate', 'severe']))
                <a href="{{ route('mental.appointments.create') }}" class="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-center transition-all shadow-md shadow-purple-200">
                    <x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> นัดหมายผู้เชี่ยวชาญ
                </a>
                @endif
                <a href="{{ route('mental.mood.index') }}" class="w-full py-4 bg-blue-50 border border-blue-100 hover:bg-blue-100 text-blue-700 font-bold rounded-xl text-center transition-all">
                    <x-heroicon-o-face-smile class="w-5 h-5 inline-block shrink-0" /> ไปยัง Mood Tracker
                </a>
                <a href="{{ route('mental.index') }}" class="w-full py-4 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-xl text-center transition-all md:col-span-full">
                    ← กลับสู่ศูนย์สุขภาพจิต
                </a>
            </div>
        </div>
    </div>

    {{-- Severe Modal --}}
    <div x-cloak x-show="showSevereModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-gray-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showSevereModal = false" x-show="showSevereModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl overflow-hidden relative">
            <div class="bg-red-50 p-6 md:p-8 text-center border-b border-red-100 relative overflow-hidden">
                <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl shadow-sm relative z-10"><x-heroicon-o-exclamation-triangle class="w-10 h-10 inline-block" /></div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 relative z-10">ผลประเมินอยู่ในระดับรุนแรง</h3>
                <p class="text-gray-600 relative z-10">เราเป็นห่วงคุณ กรุณาอย่าลังเลที่จะติดต่อขอความช่วยเหลือ เรามีช่องทางที่พร้อมรับฟังและช่วยเหลือคุณทันที</p>
                
                {{-- Decorative circles --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-red-500 opacity-5 rounded-full blur-xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-red-500 opacity-5 rounded-full blur-xl"></div>
            </div>
            
            <div class="p-6 md:p-8 space-y-4 bg-white">
                <a href="tel:1323" class="flex items-center justify-between p-4 rounded-2xl border-2 border-red-200 bg-white hover:bg-red-50 hover:border-red-300 transition-all group shadow-sm hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-o-phone class="w-6 h-6 inline-block" /></div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">สายด่วนสุขภาพจิต</h4>
                            <p class="text-red-600 font-bold">โทร 1323 (ตลอด 24 ชั่วโมง)</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-red-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <a href="/mt3/ai-companion" class="flex items-center justify-between p-4 rounded-2xl border-2 border-purple-200 bg-white hover:bg-purple-50 hover:border-purple-300 transition-all group shadow-sm hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-o-chat-bubble-left-ellipsis class="w-6 h-6 inline-block" /></div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">พูดคุยระบายกับ AI</h4>
                            <p class="text-purple-600 font-bold">แชทกับบอทเพื่อระบายความรู้สึก</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-purple-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            
            <div class="px-6 pb-6 pt-2 bg-white">
                <button type="button" @click="showSevereModal = false" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition-colors">
                    ปิดหน้าต่างนี้เพื่อดูผลประเมิน
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
