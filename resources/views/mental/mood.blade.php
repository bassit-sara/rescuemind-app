@extends('layouts.app')
@section('title', 'Mood Tracker')
@section('page-title', '😊 Mood Tracker')
@section('content')
<div class="max-w-xl mx-auto">
    {{-- Today's Mood --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">วันนี้คุณรู้สึกอย่างไร?</h2>
        @if($todayMood)
        <div class="text-center py-4 bg-green-50 rounded-xl mb-4">
            <div class="text-5xl mb-2">{{ ['','😢','😞','😐','😊','😄'][$todayMood->mood] }}</div>
            <div class="font-medium text-gray-700">{{ $todayMood->mood_label }}</div>
            @if($todayMood->note) <div class="text-sm text-gray-500 mt-1">{{ $todayMood->note }}</div> @endif
        </div>
        <p class="text-sm text-gray-500 text-center">บันทึกวันนี้แล้ว (แก้ไขได้)</p>
        @endif

        <form action="{{ route('mental.mood.store') }}" method="POST" x-data="{ mood: {{ $todayMood?->mood ?? 0 }}, note: '{{ $todayMood?->note ?? '' }}' }">
            @csrf
            <div class="flex justify-center gap-4 mb-4">
                @foreach([1 => '😢', 2 => '😞', 3 => '😐', 4 => '😊', 5 => '😄'] as $val => $emoji)
                <label class="cursor-pointer text-center" :class="mood == {{ $val }} ? 'scale-125' : 'opacity-50 hover:opacity-75'" style="transition: all 0.2s">
                    <input type="radio" name="mood" value="{{ $val }}" @click="mood = {{ $val }}" class="sr-only" {{ $todayMood?->mood == $val ? 'checked' : '' }}>
                    <div class="text-4xl">{{ $emoji }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $val }}</div>
                </label>
                @endforeach
            </div>
            <input type="text" name="note" x-model="note" placeholder="เพิ่มความรู้สึก (ไม่บังคับ)..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm mb-4 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors">
                บันทึกอารมณ์วันนี้
            </button>
        </form>
    </div>

    {{-- History --}}
    @if($moods->count() > 0)
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">ย้อนหลัง 30 วัน</h2>
        <div class="grid grid-cols-7 gap-2">
            @foreach($moods as $m)
            <div class="text-center">
                <div class="text-2xl">{{ ['','😢','😞','😐','😊','😄'][$m->mood] }}</div>
                <div class="text-xs text-gray-400">{{ $m->logged_date->format('d') }}</div>
                <div class="text-xs text-gray-300">{{ $m->logged_date->format('M') }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
