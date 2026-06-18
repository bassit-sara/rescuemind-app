@extends('layouts.app')
@section('title', 'ผลการประเมิน')
@section('page-title', '📊 ผลการประเมินสุขภาพจิต')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Result Header --}}
        <div class="p-8 text-center
            {{ $mentalAssessment->severity == 'severe' ? 'bg-red-50' : ($mentalAssessment->severity == 'moderate' ? 'bg-orange-50' : ($mentalAssessment->severity == 'mild' ? 'bg-yellow-50' : 'bg-green-50')) }}">
            <div class="text-6xl mb-4">
                {{ $mentalAssessment->severity == 'severe' ? '🔴' : ($mentalAssessment->severity == 'moderate' ? '🟠' : ($mentalAssessment->severity == 'mild' ? '🟡' : '🟢')) }}
            </div>
            <div class="text-4xl font-black text-gray-800 mb-2">{{ $mentalAssessment->score }} <span class="text-2xl font-normal text-gray-500">คะแนน</span></div>
            <div class="text-xl font-bold
                {{ $mentalAssessment->severity == 'severe' ? 'text-red-600' : ($mentalAssessment->severity == 'moderate' ? 'text-orange-600' : ($mentalAssessment->severity == 'mild' ? 'text-yellow-600' : 'text-green-600')) }}">
                {{ $mentalAssessment->severity_label }}
            </div>
            <div class="text-gray-500 text-sm mt-1">{{ strtoupper($mentalAssessment->type) }} — {{ $mentalAssessment->created_at->format('d/m/Y H:i') }}</div>
        </div>

        <div class="p-6">
            {{-- Recommendation --}}
            <div class="p-4 rounded-xl mb-6
                {{ $mentalAssessment->severity == 'severe' ? 'bg-red-50 border border-red-200' : ($mentalAssessment->severity == 'moderate' ? 'bg-orange-50 border border-orange-200' : 'bg-green-50 border border-green-200') }}">
                <h3 class="font-bold text-gray-800 mb-2">คำแนะนำ</h3>
                @if($mentalAssessment->severity == 'severe')
                <p class="text-sm text-red-700">⚠️ คะแนนอยู่ในระดับรุนแรง ควรปรึกษาจิตแพทย์หรือนักจิตวิทยาโดยเร็ว กรุณานัดหมายผู้เชี่ยวชาญ</p>
                @elseif($mentalAssessment->severity == 'moderate')
                <p class="text-sm text-orange-700">⚠️ คะแนนอยู่ในระดับปานกลาง ควรพูดคุยกับผู้เชี่ยวชาญและติดตามอาการ</p>
                @elseif($mentalAssessment->severity == 'mild')
                <p class="text-sm text-yellow-700">ℹ️ คะแนนอยู่ในระดับน้อย ควรดูแลตนเองและติดตามอาการต่อเนื่อง</p>
                @else
                <p class="text-sm text-green-700">✅ คะแนนอยู่ในระดับปกติ ดีมาก! ดูแลสุขภาพจิตต่อไป</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="grid grid-cols-1 gap-3">
                @if(in_array($mentalAssessment->severity, ['moderate', 'severe']))
                <a href="{{ route('mental.appointments.create') }}" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl text-center transition-colors">
                    📅 นัดหมายผู้เชี่ยวชาญ
                </a>
                @endif
                <a href="{{ route('mental.mood.index') }}" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-center transition-colors">
                    😊 ไปยัง Mood Tracker
                </a>
                <a href="{{ route('mental.index') }}" class="w-full py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl text-center transition-colors text-sm">
                    ← กลับสู่ศูนย์สุขภาพจิต
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
