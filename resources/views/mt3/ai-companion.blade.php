@extends('layouts.app')

@section('title', 'AI Companion - RescueMind')
@section('page-title')
    AI Companion (ผู้ช่วยอัจฉริยะ)
@endsection

@section('content')
<div class="max-w-6xl mx-auto h-[calc(100vh-140px)] flex flex-col relative" x-data="aiCompanionFull()">
    
    {{-- Back Button --}}
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('mt3') }}" class="group inline-flex items-center gap-2 text-gray-600 font-medium hover:text-purple-600 font-bold transition-all text-sm">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ย้อนกลับ
        </a>
    </div>

    {{-- Custom Confirm Modal --}}
    <div x-show="showClearConfirm" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden transform"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             @click.away="showClearConfirm = false">
             
            <div class="p-6 text-center pt-8">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-trash class="w-8 h-8" />
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2">ล้างประวัติการสนทนา?</h3>
                <p class="text-gray-600 text-[15px] font-medium">คุณต้องการล้างประวัติการสนทนาทั้งหมดหรือไม่?<br>ข้อมูลการคุยก่อนหน้านี้จะหายไปทั้งหมด</p>
            </div>
            
            <div class="flex gap-3 p-6 pt-0">
                <button @click="showClearConfirm = false" class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-colors text-sm">
                    ยกเลิก
                </button>
                <button @click="confirmClearChat" class="flex-1 py-3 px-4 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl font-bold transition-colors shadow-sm text-sm">
                    ล้างแชทเลย
                </button>
            </div>
        </div>
    </div>

    <div class="flex-1 flex gap-6 overflow-hidden">
        
        {{-- Left Sidebar --}}
        <div class="hidden lg:flex flex-col w-72 shrink-0 gap-5 overflow-y-auto pr-2 pb-2 no-scrollbar">
            {{-- Recommended Topics --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-orange-50/50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-emerald-100/50 to-transparent rounded-full -mr-16 -mt-16 pointer-events-none"></div>
                <h4 class="font-black text-gray-800 mb-4 text-sm flex items-center gap-2">
                    <x-heroicon-s-sparkles class="w-5 h-5 text-orange-400" /> หัวข้อแนะนำ
                </h4>
                <div class="space-y-1">
                    <button @click="input = 'โรคจากน้ำท่วมและการป้องกัน'; sendMessage()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-orange-50 text-left transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-full bg-emerald-100/50 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-s-bug-ant class="w-5 h-5 text-orange-500" /></div>
                        <span class="text-sm text-gray-600 font-medium group-hover:text-orange-600">โรคจากน้ำท่วม</span>
                    </button>
                    <button @click="input = 'วิธีรักษาน้ำกัดเท้าเบื้องต้น'; sendMessage()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-blue-50 text-left transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-full bg-blue-100/50 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-s-exclamation-triangle class="w-5 h-5 text-blue-500" /></div>
                        <span class="text-sm text-gray-600 font-medium group-hover:text-blue-600">น้ำกัดเท้า</span>
                    </button>
                    <button @click="input = 'จัดการความเครียดหลังภัยพิบัติอย่างไร'; sendMessage()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-purple-50 text-left transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-full bg-purple-100/50 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-s-heart class="w-5 h-5 text-emerald-500" /></div>
                        <span class="text-sm text-gray-600 font-medium group-hover:text-purple-600">ความเครียดหลังภัยพิบัติ</span>
                    </button>
                    <button @click="input = 'ยาสามัญประจำบ้านที่ควรมีติดไว้'; sendMessage()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-emerald-50 text-left transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-full bg-emerald-100/50 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-s-beaker class="w-5 h-5 text-emerald-500" /></div>
                        <span class="text-sm text-gray-600 font-medium group-hover:text-emerald-600">ยาสามัญประจำบ้าน</span>
                    </button>
                    <button @click="input = 'อาหารและน้ำดื่มที่ปลอดภัยช่วงน้ำท่วม'; sendMessage()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-green-50 text-left transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-full bg-green-100/50 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-s-archive-box class="w-5 h-5 text-green-500" /></div>
                        <span class="text-sm text-gray-600 font-medium group-hover:text-green-600">อาหารและน้ำสะอาด</span>
                    </button>
                    <button @click="input = 'การดูแลเด็กและผู้สูงอายุในภาวะฉุกเฉิน'; sendMessage()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-yellow-50 text-left transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-full bg-yellow-100/50 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-s-users class="w-5 h-5 text-yellow-600" /></div>
                        <span class="text-sm text-gray-600 font-medium group-hover:text-yellow-700">ดูแลเด็กและผู้สูงอายุ</span>
                    </button>
                    <button @click="input = 'วิธีทำความสะอาดบ้านหลังน้ำลด'; sendMessage()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-teal-50 text-left transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-full bg-teal-100/50 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><x-heroicon-s-home class="w-5 h-5 text-teal-500" /></div>
                        <span class="text-sm text-gray-600 font-medium group-hover:text-teal-600">ทำความสะอาดบ้าน</span>
                    </button>
                </div>
            </div>

            {{-- Emergency Numbers --}}
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-50 rounded-3xl p-6 border border-emerald-100 shadow-sm relative overflow-hidden">
                <div class="absolute bottom-0 right-0 w-24 h-24 bg-emerald-100/50 rounded-full -mr-8 -mb-8 pointer-events-none blur-xl"></div>
                <h4 class="font-bold text-emerald-600 mb-4 text-sm flex items-center gap-2 relative z-10">
                    <x-heroicon-s-phone class="w-4 h-4" /> เบอร์ฉุกเฉิน
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-700 font-medium">สายด่วนสุขภาพ</span>
                        <span class="font-bold text-red-600">1669</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-700 font-medium">กรมสุขภาพจิต</span>
                        <span class="font-bold text-red-600">1323</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-700 font-medium">ภัยพิบัติ</span>
                        <span class="font-bold text-red-600">1784</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-700 font-medium">ตำรวจ/เจ็บป่วย</span>
                        <span class="font-bold text-red-600">191</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Chat Container --}}
        <div class="flex-1 bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-teal-50 flex flex-col overflow-hidden relative">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-600 text-white p-5 flex items-center justify-between shadow-sm relative z-10">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-inner border border-white/30"><x-heroicon-s-cpu-chip class="w-6 h-6 text-white" /></div>
                    <div>
                        <h3 class="font-bold text-lg flex items-center gap-2 drop-shadow-sm">AI ผู้ช่วยสุขภาพ RescueMind <span class="flex h-3 w-3 relative"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-300 opacity-75"></span><span class="relative inline-flex rounded-full w-3 h-3 bg-green-400 border border-white/50"></span></span></h3>
                        <p class="text-sm text-teal-50 font-medium flex items-center gap-1 opacity-90">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-300"></span> พร้อมให้บริการดูแลคุณ
                        </p>
                    </div>
                </div>
                <div class="relative z-10">
                    <button @click="showClearConfirm = true" class="text-xs px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl transition-all text-white flex items-center gap-1.5 border border-white/20 shadow-sm hover:shadow">
                        <x-heroicon-o-trash class="w-4 h-4" />
                        ล้างการสนทนา
                    </button>
                </div>
            </div>

            {{-- Messages Area --}}
            <div id="ai-chat-messages-full" class="flex-1 p-6 overflow-y-auto bg-[#fffaf8] space-y-6 pb-4">
                
                {{-- Welcome Message --}}
                <div class="flex items-end gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-emerald-50"><x-heroicon-s-cpu-chip class="w-6 h-6 text-emerald-500" /></div>
                    <div class="bg-white p-5 rounded-2xl rounded-bl-none shadow-sm border border-emerald-50 text-gray-700 max-w-[85%] text-[15px] leading-relaxed relative">
                        สวัสดีครับ! ผมเป็น AI ผู้ช่วยด้านสุขภาพของ RescueMind <x-heroicon-s-sparkles class="inline w-4 h-4 text-green-500" /><br><br>
                        ผมสามารถช่วยตอบคำถามเกี่ยวกับ:<br>
                        <ul class="space-y-2 mt-3 ml-2 text-gray-600">
                            <li class="flex items-start gap-2"><x-heroicon-s-bug-ant class="w-5 h-5 text-orange-500 mt-0.5" /> โรคที่มาจากน้ำท่วม และการป้องกัน</li>
                            <li class="flex items-start gap-2"><x-heroicon-s-heart class="w-5 h-5 text-emerald-500 mt-0.5" /> สุขภาพจิต ความเครียด และการฟื้นตัว</li>
                            <li class="flex items-start gap-2"><x-heroicon-s-plus-circle class="w-5 h-5 text-emerald-500 mt-0.5" /> การดูแลตัวเองเบื้องต้น</li>
                            <li class="flex items-start gap-2"><x-heroicon-s-clipboard-document-list class="w-5 h-5 text-blue-500 mt-0.5" /> แนะนำแบบประเมินที่เหมาะสม</li>
                        </ul>
                        <div class="mt-4 text-gray-800 font-medium">มีอะไรให้ช่วยไหมครับ? <x-heroicon-o-face-smile class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    </div>
                </div>

                {{-- Chat Loop --}}
                <template x-for="(msg, index) in messages" :key="index">
                    <div class="flex items-end gap-3" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
                        <div x-show="msg.role !== 'user'" class="w-10 h-10 rounded-2xl bg-gradient-to-br from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-emerald-50"><x-heroicon-s-cpu-chip class="w-6 h-6 text-emerald-500" /></div>
                        <div class="p-4 rounded-2xl shadow-sm max-w-[85%] text-[15px] font-medium whitespace-pre-wrap break-words leading-relaxed"
                             :class="msg.role === 'user' ? 'bg-gradient-to-br from-teal-500 to-teal-500 text-white rounded-br-none' : 'bg-white text-gray-700 rounded-bl-none border border-emerald-50'"
                             x-html="formatMessage(msg.content)">
                        </div>
                    </div>
                </template>

                {{-- Typing Indicator --}}
                <div x-show="isTyping" class="flex items-end gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-emerald-50"><x-heroicon-s-cpu-chip class="w-6 h-6 text-emerald-500" /></div>
                    <div class="bg-white px-5 py-4 rounded-2xl rounded-bl-none shadow-sm border border-emerald-50 flex gap-1.5 items-center">
                        <div class="w-2.5 h-2.5 bg-emerald-300 rounded-full animate-bounce"></div>
                        <div class="w-2.5 h-2.5 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2.5 h-2.5 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>

            </div>

            {{-- Input Area --}}
            <div class="bg-white border-t border-emerald-50 shadow-[0_-10px_40px_rgb(0,0,0,0.03)] z-10">
                
                {{-- Quick Suggestions --}}
                <div class="flex gap-2 px-6 py-3.5 overflow-x-auto no-scrollbar border-b border-gray-50 bg-emerald-50/20" x-show="!isTyping">
                    <button @click="input = 'มีไข้หลังน้ำท่วม ต้องทำอย่างไร?'; sendMessage()" class="whitespace-nowrap px-4 py-1.5 bg-white text-emerald-600 hover:bg-emerald-50 border border-emerald-100 rounded-full text-[13px] font-medium transition-colors flex items-center gap-2 shadow-sm">
                        <x-heroicon-s-face-frown class="w-5 h-5" /> มีไข้หลังน้ำท่วม
                    </button>
                    <button @click="input = 'ขอคำแนะนำเรื่องการดูแลสุขภาพจิต'; sendMessage()" class="whitespace-nowrap px-4 py-1.5 bg-white text-purple-600 hover:bg-purple-50 border border-purple-100 rounded-full text-[13px] font-medium transition-colors flex items-center gap-2 shadow-sm">
                        <x-heroicon-s-heart class="w-5 h-5" /> ดูแลสุขภาพจิต
                    </button>
                    <button @click="input = 'ช่วยแนะนำแบบประเมินสุขภาพให้หน่อย'; sendMessage()" class="whitespace-nowrap px-4 py-1.5 bg-white text-orange-600 hover:bg-orange-50 border border-emerald-100 rounded-full text-[13px] font-medium transition-colors flex items-center gap-2 shadow-sm">
                        <x-heroicon-s-clipboard-document-list class="w-5 h-5" /> แนะนำแบบประเมิน
                    </button>
                    <button @click="input = 'อาหารแบบไหนปลอดภัยช่วงน้ำท่วม?'; sendMessage()" class="whitespace-nowrap px-4 py-1.5 bg-white text-green-600 hover:bg-green-50 border border-green-100 rounded-full text-[13px] font-medium transition-colors flex items-center gap-2 shadow-sm">
                        <x-heroicon-s-archive-box class="w-5 h-5" /> อาหารปลอดภัย
                    </button>
                </div>

                <div class="p-5">
                    <form @submit.prevent="sendMessage" class="flex gap-3 relative max-w-full mx-auto">
                        <input type="text" x-model="input" placeholder="พิมพ์คำถามของคุณที่นี่..." 
                               class="flex-1 pl-6 pr-16 py-4 bg-gray-50/80 border border-gray-200 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 rounded-2xl text-[15px] transition-all shadow-inner"
                               :disabled="isTyping">
                        <button type="submit" :disabled="!input.trim() || isTyping" 
                                class="absolute right-2 top-2 bottom-2 w-14 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl flex items-center justify-center hover:from-emerald-600 hover:to-teal-600 disabled:opacity-50 disabled:from-gray-400 disabled:to-gray-400 disabled:cursor-not-allowed transition-all transform active:scale-95 shadow-md hover:shadow-lg">
                            <svg class="w-6 h-6 ml-1 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                        </button>
                    </form>
                    <div class="text-center mt-3 text-[11px] text-gray-400 font-medium">
                        AI อาจให้ข้อมูลที่ไม่แม่นยำ 100% โปรดใช้วิจารณญาณประกอบกับคำแนะนำของแพทย์หรือเจ้าหน้าที่
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function aiCompanionFull() {
    return {
        input: '',
        messages: JSON.parse(sessionStorage.getItem('ai_chat_history') || '[]'),
        isTyping: false,
        showClearConfirm: false,
        
        init() {
            this.$watch('messages', val => {
                sessionStorage.setItem('ai_chat_history', JSON.stringify(val));
            });
            setTimeout(() => this.scrollToBottom(), 100);
        },

        confirmClearChat() {
            this.messages = [];
            sessionStorage.removeItem('ai_chat_history');
            this.showClearConfirm = false;
        },
        
        async sendMessage() {
            if(!this.input.trim() || this.isTyping) return;
            
            const userMsg = this.input.trim();
            this.messages.push({ role: 'user', content: userMsg });
            this.input = '';
            this.isTyping = true;
            this.scrollToBottom();
            
            try {
                const response = await fetch('{{ route('ai-companion.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: userMsg,
                        history: this.messages.slice(0, -1)
                    })
                });
                
                const data = await response.json();
                
                if(data.success) {
                    this.messages.push({ role: 'assistant', content: data.reply });
                } else {
                    this.messages.push({ role: 'assistant', content: 'ขออภัย เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + (data.message || 'ระบบขัดข้อง') });
                }
            } catch (error) {
                this.messages.push({ role: 'assistant', content: 'ขออภัย ไม่สามารถติดต่อเซิร์ฟเวอร์ AI ได้ในขณะนี้ โปรดลองใหม่ภายหลัง' });
            } finally {
                this.isTyping = false;
                this.scrollToBottom();
            }
        },
        
        scrollToBottom() {
            setTimeout(() => {
                const chatBox = document.getElementById('ai-chat-messages-full');
                if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
            }, 50);
        },
        
        formatMessage(text) {
            let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
            formatted = formatted.replace(/\n/g, '<br>');
            return formatted;
        }
    }
}
</script>
@endsection




