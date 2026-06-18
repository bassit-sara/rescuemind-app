@extends('layouts.app')
@section('title', 'บันทึกความรู้สึก')
@section('page-title', '📔 บันทึกความรู้สึก (Journal)')
@section('content')

<div class="max-w-2xl mx-auto space-y-8" x-data="{ activeJournal: null, isEditing: false, editContent: '' }">

    {{-- Intro / Encouragement Header --}}
    <div class="bg-gradient-to-br from-amber-500/10 via-orange-500/5 to-transparent border border-amber-100 rounded-3xl p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-500 text-white rounded-2xl flex items-center justify-center text-2xl shadow-md flex-shrink-0 animate-bounce">
            ✍️
        </div>
        <div>
            <h2 class="font-bold text-gray-800 text-base">ระบายความรู้สึกของคุณวันนี้</h2>
            <p class="text-xs text-gray-500 leading-relaxed">การเขียนบันทึกประจำวันเป็นวิธีที่มีประสิทธิภาพในการลดความเครียด เรียบเรียงความรู้สึก และช่วยฟื้นฟูสภาพจิตใจหลังเกิดเหตุการณ์วิกฤต</p>
        </div>
    </div>

    {{-- Journal Entry Form --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <form action="{{ route('mental.journal.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="content" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">เขียนบันทึกของคุณ</label>
                <textarea id="content" name="content" rows="5" required 
                          placeholder="วันนี้รู้สึกอย่างไรบ้าง? สิ่งที่ทำให้กังวล หรือความหวังในวันนี้คืออะไร... (ระบบจะวิเคราะห์สภาวะอารมณ์ของคุณโดยอัตโนมัติ)" 
                          class="w-full px-4 py-3 bg-amber-50/30 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900 focus:border-amber-400 focus:ring-2 focus:ring-amber-250 focus:ring-opacity-50 rounded-2xl text-sm resize-none focus:outline-none transition-all text-gray-700 placeholder-gray-400 dark:text-gray-300"></textarea>
            </div>
            @error('content') 
            <p class="text-red-500 text-xs font-semibold">{{ $message }}</p> 
            @enderror
            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl transition-all shadow-md shadow-amber-500/20 hover:shadow-lg focus:outline-none flex items-center justify-center gap-2">
                <span>📝</span> บันทึกความรู้สึก
            </button>
        </form>
    </div>

    {{-- Journal List Section --}}
    <div class="space-y-4">
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">ประวัติบันทึกที่ผ่านมา</h3>
        
        @forelse($journals as $j)
        <div @click="activeJournal = { id: {{ $j->id }}, date: '{{ $j->created_at->format('d M Y H:i') }}', content: {{ json_encode($j->content) }}, label: '{{ $j->sentiment_label ?? 'neutral' }}', score: '{{ $j->sentiment_score ?? 0.0 }}' }; editContent = activeJournal.content; isEditing = false;"
             class="group bg-white hover:bg-amber-50/10 rounded-3xl p-5 shadow-sm border border-gray-100 hover:border-amber-200 transition-all duration-300 cursor-pointer flex flex-col justify-between transform hover:-translate-y-0.5">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400 font-semibold flex items-center gap-1">
                        📅 {{ $j->created_at->format('d M Y H:i') }}
                    </span>
                    
                    {{-- Sentiment badge --}}
                    @if($j->sentiment_label === 'positive')
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-green-50 text-green-700 rounded-full flex items-center gap-1">
                            😊 สุข/เชิงบวก
                        </span>
                    @elseif($j->sentiment_label === 'negative')
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-red-50 text-red-700 rounded-full flex items-center gap-1">
                            😢 เครียด/เชิงลบ
                        </span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-50 text-slate-700 rounded-full flex items-center gap-1">
                            😐 ปกติ/ทั่วไป
                        </span>
                    @endif
                </div>
                
                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed line-clamp-3 whitespace-pre-line">
                    {{ $j->content }}
                </p>
                
                <div class="text-right">
                    <span class="text-[10px] font-bold text-amber-600 group-hover:underline">คลิกเปิดอ่าน / แก้ไข / ลบ →</span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-gray-50/50 rounded-3xl p-12 text-center border border-dashed border-gray-200">
            <div class="text-5xl mb-4 text-gray-300">📔</div>
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
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-lg w-full max-h-[80vh] overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-750 flex flex-col transform transition-all"
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
                    <span class="text-xl">📔</span>
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
                            <span class="text-xs font-bold text-green-600">😊 มีความสุข (เชิงบวก)</span>
                        </template>
                        <template x-if="activeJournal?.label === 'negative'">
                            <span class="text-xs font-bold text-red-600">😢 เครียด/กังวล (เชิงลบ)</span>
                        </template>
                        <template x-if="activeJournal?.label === 'neutral'">
                            <span class="text-xs font-bold text-slate-500">😐 ปกติทั่วไป</span>
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
            <div x-show="!isEditing" class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/80 bg-amber-50/10 dark:bg-gray-900/50 flex items-center justify-between">
                {{-- Delete form --}}
                <form :action="'{{ url('/mental/journal') }}/' + activeJournal?.id" method="POST" onsubmit="return confirm('คุณแน่ใจที่จะลบบันทึกนี้ใช่ไหม?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 border border-red-100 font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 focus:outline-none">
                        🗑️ ลบบันทึก
                    </button>
                </form>

                <div class="flex gap-2">
                    <button @click="isEditing = true" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-600 font-bold rounded-xl text-xs transition-all focus:outline-none">
                        ✏️ แก้ไข
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
