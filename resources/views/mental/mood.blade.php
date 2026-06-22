@extends('layouts.app')
@section('title', 'Mood Tracker')
@section('page-title')
    <x-heroicon-o-face-smile class="w-5 h-5 inline-block shrink-0" /> Mood Tracker
@endsection
@section('content')
<div class="max-w-xl mx-auto space-y-6" x-data="{ activeMood: null, isEditing: false, editMoodIndex: 1, editNote: '' }">
    
    {{-- Back Button --}}
    <div>
        <a href="{{ route('mt3') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-fuchsia-600 transition-colors group">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 bg-white group-hover:border-fuchsia-300 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
            ย้อนกลับ
        </a>
    </div>

    {{-- Today's Mood --}}
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-fuchsia-50 overflow-hidden">
        
        {{-- Header Gradient --}}
        <div class="bg-gradient-to-br from-purple-500 via-fuchsia-500 to-pink-500 p-8 text-white relative text-center">
            <div class="absolute inset-0 opacity-20 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner border border-white/30 mb-3 animate-pulse">
                    <x-heroicon-s-sparkles class="w-8 h-8 text-white" />
                </div>
                <h2 class="text-2xl font-extrabold drop-shadow-sm tracking-tight">วันนี้คุณรู้สึกอย่างไร?</h2>
                <p class="text-fuchsia-100 text-sm mt-1 font-medium">บันทึกอารมณ์เพื่อติดตามสุขภาพใจของคุณ</p>
            </div>
        </div>

        <div class="p-8">
            @if($todayMood)
            @php
                $moodIconsLarge = [
                    '',
                    svg('heroicon-s-face-frown', 'w-16 h-16 text-red-500')->toHtml(),
                    svg('heroicon-o-face-frown', 'w-16 h-16 text-orange-500')->toHtml(),
                    svg('heroicon-s-minus-circle', 'w-16 h-16 text-yellow-500')->toHtml(),
                    svg('heroicon-o-face-smile', 'w-16 h-16 text-lime-500')->toHtml(),
                    svg('heroicon-s-face-smile', 'w-16 h-16 text-green-500')->toHtml()
                ];
            @endphp
            <div class="text-center py-5 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100 rounded-2xl mb-6 shadow-inner">
                <div class="mb-3 drop-shadow-sm flex justify-center">{!! $moodIconsLarge[$todayMood->mood] !!}</div>
                <div class="font-bold text-green-700 text-lg">{{ $todayMood->mood_label }}</div>
                @if($todayMood->note) 
                    <div class="text-sm text-green-600/80 mt-2 font-medium px-4">"{{ $todayMood->note }}"</div> 
                @endif
                <div class="text-[11px] text-green-500/70 font-bold mt-3 bg-green-100/50 inline-block px-3 py-1 rounded-full">บันทึกวันนี้แล้ว (แก้ไขได้)</div>
            </div>
            @endif

            <form action="{{ route('mental.mood.store') }}" method="POST" x-data="{ mood: {{ $todayMood?->mood ?? 0 }}, note: '{{ $todayMood?->note ?? '' }}' }">
                @csrf
                <div class="flex justify-center gap-2 sm:gap-4 mb-6 bg-gray-50/50 p-4 rounded-3xl border border-gray-100">
                    @php
                        $moodOptions = [
                            1 => svg('heroicon-s-face-frown', 'w-12 h-12 mx-auto text-red-500')->toHtml(),
                            2 => svg('heroicon-o-face-frown', 'w-12 h-12 mx-auto text-orange-500')->toHtml(),
                            3 => svg('heroicon-s-minus-circle', 'w-12 h-12 mx-auto text-yellow-500')->toHtml(),
                            4 => svg('heroicon-o-face-smile', 'w-12 h-12 mx-auto text-lime-500')->toHtml(),
                            5 => svg('heroicon-s-face-smile', 'w-12 h-12 mx-auto text-green-500')->toHtml()
                        ];
                    @endphp
                    @foreach($moodOptions as $val => $icon)
                    <label class="cursor-pointer text-center relative group" :class="mood == {{ $val }} ? 'scale-125 z-10' : 'opacity-60 hover:opacity-100 hover:scale-110'" style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)">
                        <input type="radio" name="mood" value="{{ $val }}" @click="mood = {{ $val }}" class="sr-only" {{ $todayMood?->mood == $val ? 'checked' : '' }}>
                        <div class="filter drop-shadow-sm">{!! $icon !!}</div>
                        <div class="text-[10px] font-bold mt-2 transition-colors duration-300" :class="mood == {{ $val }} ? 'text-fuchsia-600' : 'text-gray-400'">{{ ['','แย่มาก','แย่','ปกติ','ดี','ดีมาก'][$val] }}</div>
                        
                        {{-- Active Indicator dot --}}
                        <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-fuchsia-500 transition-opacity duration-300" :class="mood == {{ $val }} ? 'opacity-100' : 'opacity-0'"></div>
                    </label>
                    @endforeach
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">เพิ่มโน้ตสั้นๆ <span class="text-gray-400 font-normal text-xs">(ไม่บังคับ)</span></label>
                    <input type="text" name="note" x-model="note" placeholder="เหตุการณ์สำคัญวันนี้ หรือความรู้สึกเพิ่มเติม..."
                           class="w-full px-5 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-[15px] focus:bg-white focus:ring-4 focus:ring-fuchsia-100 focus:border-fuchsia-400 transition-all shadow-inner font-medium text-gray-700 placeholder-gray-400">
                </div>
                
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-400 to-teal-500 hover:from-emerald-500 hover:to-teal-600 text-white font-bold rounded-2xl transition-all shadow-md hover:shadow-lg transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <x-heroicon-s-sparkles class="w-6 h-6" /> บันทึกอารมณ์วันนี้
                </button>
            </form>
        </div>
    </div>

    {{-- History --}}
    @if($moods->count() > 0)
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-pink-50 overflow-hidden mt-8">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-fuchsia-500" /> ย้อนหลัง 30 วัน
            </h2>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-7 gap-3 sm:gap-4">
                @php
                    $moodIconsSmall = [
                        '',
                        svg('heroicon-s-face-frown', 'w-6 h-6 sm:w-8 sm:h-8 text-red-500')->toHtml(),
                        svg('heroicon-o-face-frown', 'w-6 h-6 sm:w-8 sm:h-8 text-orange-500')->toHtml(),
                        svg('heroicon-s-minus-circle', 'w-6 h-6 sm:w-8 sm:h-8 text-yellow-500')->toHtml(),
                        svg('heroicon-o-face-smile', 'w-6 h-6 sm:w-8 sm:h-8 text-lime-500')->toHtml(),
                        svg('heroicon-s-face-smile', 'w-6 h-6 sm:w-8 sm:h-8 text-green-500')->toHtml()
                    ];
                @endphp
                @foreach($moods as $m)
                <div @click="activeMood = { id: {{ $m->id }}, is_today: {{ $m->logged_date->isToday() ? 'true' : 'false' }}, date: '{{ $m->logged_date->format('d M Y') }}', mood_index: {{ $m->mood }}, label: '{{ $m->mood_label }}', note: '{{ addslashes(str_replace(["\r", "\n"], ' ', $m->note)) }}' }; isEditing = false; editMoodIndex = activeMood.mood_index; editNote = activeMood.note;" 
                     class="text-center group flex flex-col items-center cursor-pointer">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-50 rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 group-hover:scale-110 group-hover:bg-teal-50 group-hover:border-teal-200 transition-all duration-300">
                        {!! $moodIconsSmall[$m->mood] !!}
                    </div>
                    <div class="mt-2 text-[11px] font-bold text-gray-800 group-hover:text-fuchsia-600 transition-colors">{{ $m->logged_date->format('d') }}</div>
                    <div class="text-[10px] font-medium text-gray-400 group-hover:text-fuchsia-400 transition-colors">{{ $m->logged_date->format('M') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- View Mood Modal --}}
    <div x-show="activeMood !== null" 
         class="fixed inset-0 bg-gray-900/40 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.away="activeMood = null"
         @keydown.escape.window="activeMood = null">
        
        <div class="bg-white rounded-3xl max-w-sm w-full overflow-hidden shadow-2xl border border-gray-100 transform transition-all"
             x-show="activeMood !== null"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            <div class="p-6 text-center" x-show="!isEditing">
                <div class="mb-4 drop-shadow-sm flex justify-center">
                    <template x-if="activeMood?.mood_index === 1">
                        <x-heroicon-s-face-frown class="w-16 h-16 text-red-500" />
                    </template>
                    <template x-if="activeMood?.mood_index === 2">
                        <x-heroicon-o-face-frown class="w-16 h-16 text-orange-500" />
                    </template>
                    <template x-if="activeMood?.mood_index === 3">
                        <x-heroicon-s-minus-circle class="w-16 h-16 text-yellow-500" />
                    </template>
                    <template x-if="activeMood?.mood_index === 4">
                        <x-heroicon-o-face-smile class="w-16 h-16 text-lime-500" />
                    </template>
                    <template x-if="activeMood?.mood_index === 5">
                        <x-heroicon-s-face-smile class="w-16 h-16 text-green-500" />
                    </template>
                </div>
                <h3 class="text-xl font-bold text-gray-800" x-text="activeMood?.label"></h3>
                <p class="text-xs font-bold text-teal-500 mt-1 mb-4 flex items-center justify-center gap-1">
                    <x-heroicon-o-calendar-days class="w-4 h-4" /> <span x-text="activeMood?.date"></span>
                </p>
                
                <div x-show="activeMood?.note" class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-left relative mt-4">
                    <div class="absolute -top-3 left-4 bg-white px-2 text-[10px] font-bold text-gray-400">โน้ตของคุณ</div>
                    <p class="text-sm text-gray-600 font-medium leading-relaxed italic" x-text="'&quot;' + activeMood?.note + '&quot;'"></p>
                </div>
                
                <div x-show="!activeMood?.note" class="py-4 text-sm text-gray-400 font-medium">
                    (ไม่มีโน้ตเพิ่มเติม)
                </div>
            </div>

            <div class="p-6" x-show="isEditing" style="display: none;">
                <form :action="'{{ url('/mental/mood') }}/' + activeMood?.id" method="POST" class="space-y-5 text-left">
                    @csrf
                    @method('PATCH')
                    
                    <div class="flex justify-center gap-2 mb-4">
                        @php
                            $editMoodOptions = [
                                1 => svg('heroicon-s-face-frown', 'w-10 h-10 mx-auto text-red-500')->toHtml(),
                                2 => svg('heroicon-o-face-frown', 'w-10 h-10 mx-auto text-orange-500')->toHtml(),
                                3 => svg('heroicon-s-minus-circle', 'w-10 h-10 mx-auto text-yellow-500')->toHtml(),
                                4 => svg('heroicon-o-face-smile', 'w-10 h-10 mx-auto text-lime-500')->toHtml(),
                                5 => svg('heroicon-s-face-smile', 'w-10 h-10 mx-auto text-green-500')->toHtml()
                            ];
                        @endphp
                        @foreach($editMoodOptions as $val => $icon)
                        <label class="cursor-pointer text-center relative group" :class="editMoodIndex == {{ $val }} ? 'scale-110 z-10' : 'opacity-50 hover:opacity-100'">
                            <input type="radio" name="mood" value="{{ $val }}" x-model="editMoodIndex" class="sr-only">
                            <div class="filter drop-shadow-sm">{!! $icon !!}</div>
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-teal-500 transition-opacity duration-300" :class="editMoodIndex == {{ $val }} ? 'opacity-100' : 'opacity-0'"></div>
                        </label>
                        @endforeach
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">แก้ไขโน้ต</label>
                        <textarea name="note" x-model="editNote" rows="3" placeholder="ไม่มีโน้ตเพิ่มเติม..."
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-teal-100 focus:border-teal-400 transition-all font-medium text-gray-700 placeholder-gray-400"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="isEditing = false" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition-colors focus:outline-none">
                            ยกเลิก
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-teal-500 hover:bg-teal-600 text-white font-bold rounded-xl text-sm transition-all shadow-md focus:outline-none">
                            บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
            
            <div x-show="!isEditing" class="p-4 pt-0 border-t border-gray-50 mt-2 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-b-3xl" x-data="{ showDeleteConfirm: false }">
                
                {{-- Delete logic --}}
                <div class="relative w-full sm:w-auto" x-show="activeMood?.is_today">
                    <button type="button" @click="showDeleteConfirm = true" x-show="!showDeleteConfirm" class="w-full sm:w-auto px-4 py-2.5 text-red-500 hover:bg-red-50 font-bold rounded-xl text-xs transition-colors focus:outline-none flex items-center justify-center gap-1.5">
                        <x-heroicon-o-trash class="w-4 h-4" /> ลบ
                    </button>
                    <div x-show="showDeleteConfirm" style="display: none;" class="flex gap-2 w-full sm:w-auto items-center">
                        <button type="button" @click="showDeleteConfirm = false" class="px-3 py-2 text-gray-500 hover:bg-gray-200 bg-gray-100 font-bold rounded-lg text-xs transition-colors focus:outline-none">
                            ยกเลิก
                        </button>
                        <form :action="'{{ url('/mental/mood') }}/' + activeMood?.id" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg text-xs transition-colors focus:outline-none shadow-sm">
                                ยืนยันลบ
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex gap-2 w-full sm:w-auto justify-end ml-auto">
                    <button x-show="activeMood?.is_today" @click="isEditing = true" class="flex-1 sm:flex-none px-5 py-2.5 bg-white border border-gray-200 hover:border-teal-300 hover:bg-teal-50 text-gray-700 font-bold rounded-xl text-sm transition-colors focus:outline-none flex items-center justify-center gap-1.5">
                        <x-heroicon-o-pencil class="w-4 h-4" /> แก้ไข
                    </button>
                    <button @click="activeMood = null" class="flex-1 sm:flex-none px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl text-sm transition-colors focus:outline-none">
                        ปิด
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
