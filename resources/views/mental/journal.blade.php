@extends('layouts.app')
@section('title', 'บันทึกความรู้สึก')
@section('page-title')
    <x-heroicon-o-book-open class="w-5 h-5 inline-block shrink-0" /> บันทึกความรู้สึก (Journal)
@endsection
@section('content')

<div class="max-w-3xl mx-auto space-y-8" x-data="{ activeJournal: null, isEditing: false, editContent: '' }">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('mt3') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-orange-500 transition-colors group">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 bg-white group-hover:border-orange-300 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
            ย้อนกลับ
        </a>
    </div>

    {{-- Intro / Encouragement Header --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-amber-400 via-orange-500 to-rose-500 rounded-[2rem] p-8 shadow-lg border border-orange-200/50">
        <div class="absolute inset-0 opacity-20 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute inset-0 opacity-30 pointer-events-none">
            <div class="absolute top-0 right-10 w-64 h-64 bg-yellow-300 rounded-full blur-[80px]"></div>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center shadow-inner border border-white/30 flex-shrink-0 animate-bounce">
                <x-heroicon-o-pencil class="w-8 h-8 text-white" />
            </div>
            <div class="text-center md:text-left text-white">
                <h2 class="font-extrabold text-2xl mb-2 drop-shadow-sm">ระบายความรู้สึกของคุณวันนี้</h2>
                <p class="text-sm md:text-base text-orange-50 font-medium leading-relaxed drop-shadow-sm">การเขียนบันทึกประจำวันเป็นวิธีที่มีประสิทธิภาพในการลดความเครียด เรียบเรียงความรู้สึก และช่วยฟื้นฟูสภาพจิตใจหลังเกิดเหตุการณ์วิกฤต</p>
            </div>
        </div>
    </div>

    {{-- Journal Entry Form --}}
    <div class="bg-white rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-orange-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-orange-50 to-transparent rounded-bl-full pointer-events-none"></div>
        <form action="{{ route('mental.journal.store') }}" method="POST" class="space-y-5 relative z-10">
            @csrf
            <div>
                <label for="content" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <x-heroicon-o-pencil class="w-4 h-4 text-orange-500" /> เขียนบันทึกของคุณ
                </label>
                <textarea id="content" name="content" rows="5" required 
                          placeholder="วันนี้รู้สึกอย่างไรบ้าง? สิ่งที่ทำให้กังวล หรือความหวังในวันนี้คืออะไร... (ระบบจะวิเคราะห์สภาวะอารมณ์ของคุณโดยอัตโนมัติ)" 
                          class="w-full px-5 py-4 bg-orange-50/50 border border-orange-100 focus:bg-white focus:border-orange-400 focus:ring-4 focus:ring-orange-100/50 rounded-2xl text-[15px] resize-none focus:outline-none transition-all text-gray-700 placeholder-gray-400 shadow-inner font-medium"></textarea>
            </div>
            @error('content') 
            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> 
            @enderror
            <button type="submit" class="w-full py-4 bg-gradient-to-r from-orange-400 to-rose-500 hover:from-orange-500 hover:to-rose-600 text-white font-bold rounded-2xl transition-all shadow-md hover:shadow-lg transform active:scale-[0.98] focus:outline-none flex items-center justify-center gap-2 text-[15px]">
                <x-heroicon-o-pencil-square class="w-5 h-5" /> บันทึกความรู้สึก
            </button>
        </form>
    </div>

    {{-- Journal List Section --}}
    <div class="space-y-5">
        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
            <x-heroicon-o-clock class="w-5 h-5 text-orange-500" /> ประวัติบันทึกที่ผ่านมา
        </h3>
        
        @forelse($journals as $j)
        <div @click="activeJournal = { id: {{ $j->id }}, date: '{{ $j->created_at->format('d M Y H:i') }}', content: {{ json_encode($j->content) }}, label: '{{ $j->sentiment_label ?? 'neutral' }}', score: '{{ $j->sentiment_score ?? 0.0 }}' }; editContent = activeJournal.content; isEditing = false;"
             class="group bg-white hover:bg-orange-50/30 rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-orange-200 hover:shadow-md transition-all duration-300 cursor-pointer flex flex-col justify-between transform hover:-translate-y-1">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500 font-bold flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-full">
                        <x-heroicon-o-calendar-days class="w-4 h-4" /> {{ $j->created_at->format('d M Y H:i') }}
                    </span>
                    
                    {{-- Sentiment badge --}}
                    @if($j->sentiment_label === 'positive')
                        <span class="text-[11px] font-bold px-3 py-1 bg-green-50 text-green-600 border border-green-100 rounded-full flex items-center gap-1">
                            <x-heroicon-s-face-smile class="w-4 h-4" /> สุข/เชิงบวก
                        </span>
                    @elseif($j->sentiment_label === 'negative')
                        <span class="text-[11px] font-bold px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full flex items-center gap-1">
                            <x-heroicon-s-face-frown class="w-4 h-4" /> เครียด/เชิงลบ
                        </span>
                    @else
                        <span class="text-[11px] font-bold px-3 py-1 bg-gray-50 text-gray-600 border border-gray-200 rounded-full flex items-center gap-1">
                            <x-heroicon-s-minus-circle class="w-4 h-4" /> ปกติ/ทั่วไป
                        </span>
                    @endif
                </div>
                
                <p class="text-gray-700 text-[15px] leading-relaxed line-clamp-3 whitespace-pre-line font-medium">
                    {{ $j->content }}
                </p>
                
                <div class="text-right mt-2">
                    <span class="text-xs font-bold text-white bg-gray-900 px-3 py-1.5 rounded-xl group-hover:bg-orange-500 transition-colors inline-flex items-center gap-1 shadow-sm">
                        เปิดอ่าน / แก้ไข
                        <svg class="w-3 h-3 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-gray-50/50 rounded-3xl p-12 text-center border border-dashed border-gray-200">
            <div class="text-5xl mb-4 text-gray-300"><x-heroicon-o-book-open class="w-5 h-5 inline-block shrink-0" /></div>
            <p class="text-sm text-gray-500 font-medium">ยังไม่มีบันทึกเลย</p>
            <p class="text-xs text-gray-400 mt-1">บันทึกสภาวะอารมณ์ฉบับแรกของคุณวันนี้เพื่อติดตามสุขภาพใจ</p>
        </div>
        @endforelse

        {{-- Pagination --}}
        <div class="pt-4">
            {{ $journals->links() }}
        </div>
    </div>

    {{-- Interactive Journal Reader Modal (Supports View, Edit, and Delete) --}}
    <div x-show="activeJournal !== null" 
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.away="if (!isEditing) activeJournal = null"
         @keydown.escape.window="if (!isEditing) activeJournal = null">
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-2xl w-full max-h-[80vh] overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-750 flex flex-col transform transition-all"
             x-show="activeJournal !== null"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/80 flex items-center justify-between bg-amber-50/20 dark:bg-gray-900/50">
                <div class="flex items-center gap-2 text-amber-600">
                    <span class="text-xl"><x-heroicon-o-book-open class="w-5 h-5 inline-block shrink-0" /></span>
                    <span class="text-xs font-bold uppercase tracking-wider" x-text="isEditing ? 'แก้ไขบันทึก' : activeJournal?.date"></span>
                </div>
                <button @click="activeJournal = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-white p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-4">
                
                {{-- Sentiment stats (gimmick) --}}
                <div x-show="!isEditing" class="p-3 bg-gray-50 dark:bg-gray-900/60 rounded-2xl flex items-center justify-between border border-gray-100 dark:border-gray-700/50">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-gray-750 dark:text-gray-300">สภาวะจิตใจจากการวิเคราะห์:</span>
                        <template x-if="activeJournal?.label === 'positive'">
                            <span class="text-xs font-bold text-green-600"><x-heroicon-o-face-smile class="w-5 h-5 inline-block shrink-0" /> มีความสุข (เชิงบวก)</span>
                        </template>
                        <template x-if="activeJournal?.label === 'negative'">
                            <span class="text-xs font-bold text-red-600 flex items-center gap-1"><x-heroicon-o-face-frown class="w-4 h-4" /> เครียด/กังวล (เชิงลบ)</span>
                        </template>
                        <template x-if="activeJournal?.label === 'neutral'">
                            <span class="text-xs font-bold text-slate-500 flex items-center gap-1"><x-heroicon-o-minus-circle class="w-4 h-4" /> ปกติทั่วไป</span>
                        </template>
                    </div>
                    
                    {{-- Score bar --}}
                    <div class="text-xs font-bold text-gray-500" x-text="'คะแนน: ' + activeJournal?.score"></div>
                </div>

                {{-- Edit State --}}
                <div x-show="isEditing" style="display: none;">
                    <form :action="'{{ url('/mental/journal') }}/' + activeJournal?.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <textarea name="content" x-model="editContent" rows="6" required
                                  class="w-full px-4 py-3 border border-gray-250 dark:border-gray-700 focus:border-amber-400 focus:ring-2 focus:ring-amber-250 rounded-2xl text-sm resize-none focus:outline-none bg-amber-50/10 text-gray-750 dark:text-gray-300"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="isEditing = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl text-xs transition-colors focus:outline-none">
                                ยกเลิก
                            </button>
                            <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs transition-all shadow-sm shadow-amber-500/10">
                                บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
                
                {{-- View State --}}
                <div x-show="!isEditing">
                    <div class="text-sm md:text-base text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap break-words" x-text="activeJournal?.content"></div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div x-data="{ showDeleteConfirm: false }" x-show="!isEditing" class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/80 bg-amber-50/10 dark:bg-gray-900/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative">
                {{-- Delete button --}}
                <div class="flex items-center">
                    <button @click="showDeleteConfirm = true" type="button" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 border border-red-100 font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 focus:outline-none">
                        <x-heroicon-o-trash class="w-4 h-4" /> ลบบันทึก
                    </button>
                </div>

                {{-- Delete Confirmation Overlay Modal --}}
                <template x-teleport="body">
                    <div x-show="showDeleteConfirm" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/40 backdrop-blur-md" x-transition.opacity>
                        <div @click.away="showDeleteConfirm = false" class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.5)] max-w-sm w-full mx-4 transform transition-all border border-white/20" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 scale-90 translate-y-8" 
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                        
                        {{-- Icon --}}
                        <div class="relative w-24 h-24 mx-auto mb-6">
                            <div class="absolute inset-0 bg-red-400 opacity-20 rounded-full blur-xl animate-pulse"></div>
                            <div class="relative bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/50 text-red-500 rounded-full w-full h-full flex items-center justify-center text-4xl shadow-inner border border-red-200/50 dark:border-red-700/50">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </div>
                        </div>

                        {{-- Text Content --}}
                        <h3 class="text-2xl font-black text-center text-gray-800 dark:text-white mb-3">ยืนยันการลบบันทึก?</h3>
                        <p class="text-center text-gray-500 dark:text-gray-400 text-sm mb-8 leading-relaxed px-4">
                            บันทึกความรู้สึกนี้จะถูก<span class="text-red-500 font-bold">ลบอย่างถาวร</span> และไม่สามารถกู้คืนกลับมาได้อีก คุณแน่ใจหรือไม่?
                        </p>
                        
                        {{-- Actions --}}
                        <div class="flex gap-4 justify-center">
                            <button type="button" @click="showDeleteConfirm = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl text-sm transition-all focus:outline-none flex-1">
                                กลับไปก่อน
                            </button>
                            <form :action="'{{ url('/mental/journal') }}/' + activeJournal?.id" method="POST" class="flex-1 m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-2xl text-sm transition-all transform hover:-translate-y-0.5 focus:outline-none shadow-lg shadow-red-500/30">
                                    ใช่, ลบเลย
                                </button>
                            </form>
                        </div>
                    </div>
                </template>

                <div class="flex gap-2 flex-wrap justify-end">

                    <button @click="isEditing = true" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-600 font-bold rounded-xl text-xs transition-all focus:outline-none flex items-center gap-1.5">
                        <x-heroicon-o-pencil class="w-4 h-4" /> แก้ไข
                    </button>
                    <button @click="activeJournal = null" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs transition-all focus:outline-none shadow-sm shadow-amber-500/10">
                        ตกลง
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
