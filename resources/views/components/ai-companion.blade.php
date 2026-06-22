<div x-data="aiCompanion()" class="fixed bottom-[90px] lg:bottom-6 right-4 lg:right-6 z-50 font-sans">
    
    {{-- Chat Bubble Button --}}
    <button @click="toggleChat" 
            class="relative flex items-center justify-center w-14 h-14 md:w-16 md:h-16 bg-gradient-to-tr from-blue-600 to-indigo-500 hover:from-blue-700 hover:to-indigo-600 text-white rounded-full shadow-[0_10px_25px_-5px_rgba(59,130,246,0.5)] transition-all duration-300 transform hover:scale-110 focus:outline-none ring-4 ring-blue-500/30 hover:ring-blue-500/50">
        <span x-show="!isOpen" x-transition.opacity class="flex items-center justify-center"><x-heroicon-o-cpu-chip class="w-7 h-7 md:w-8 md:h-8" /></span>
        <span x-show="isOpen" x-transition.opacity class="flex items-center justify-center"><x-heroicon-o-chevron-down class="w-7 h-7 md:w-8 md:h-8" /></span>
        <span class="absolute top-0 right-0 flex w-3.5 h-3.5 md:w-4 md:h-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-300 opacity-75"></span>
            <span class="relative inline-flex rounded-full w-3.5 h-3.5 md:w-4 md:h-4 bg-blue-500 border-2 border-white"></span>
        </span>
    </button>

    {{-- Chat Window --}}
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="absolute bottom-20 right-0 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden" style="height: 500px; display: none;">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-xl"><x-heroicon-o-cpu-chip class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                <div>
                    <h3 class="font-bold text-sm">RescueMind AI</h3>
                    <p class="text-xs text-blue-100">ผู้เชี่ยวชาญด้านภัยพิบัติ 24 ชม.</p>
                </div>
            </div>
            <button @click="toggleChat" class="text-white/70 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Messages Area --}}
        <div id="ai-chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4">
            
            {{-- Welcome Message --}}
            <div class="flex items-end gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-sm flex-shrink-0"><x-heroicon-o-cpu-chip class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                <div class="bg-white p-3 rounded-2xl rounded-bl-none shadow-sm border border-gray-100 text-sm text-gray-700 max-w-[80%]">
                    สวัสดีครับ ผมคือ RescueMind AI <x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /><br>
                    มีเหตุฉุกเฉิน หรือต้องการคำแนะนำเรื่องการรับมือภัยพิบัติอะไรไหมครับ?
                </div>
            </div>

            {{-- Chat Loop --}}
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex items-end gap-2" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
                    <div x-show="msg.role !== 'user'" class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-sm flex-shrink-0"><x-heroicon-o-cpu-chip class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                    <div class="p-3 rounded-2xl shadow-sm border text-sm max-w-[80%] whitespace-pre-wrap break-words"
                         :class="msg.role === 'user' ? 'bg-blue-600 text-white rounded-br-none border-blue-700' : 'bg-white text-gray-700 rounded-bl-none border-gray-100'"
                         x-html="formatMessage(msg.content)">
                    </div>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isTyping" class="flex items-end gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-sm flex-shrink-0"><x-heroicon-o-cpu-chip class="w-5 h-5 inline-block mr-1 -mt-1" /></div>
                <div class="bg-white p-4 rounded-2xl rounded-bl-none shadow-sm border border-gray-100 flex gap-1 items-center">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>

        </div>

        {{-- Input Area --}}
        <div class="p-3 bg-white border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="flex gap-2 relative">
                <input type="text" x-model="input" placeholder="พิมพ์ข้อความที่นี่..." 
                       class="flex-1 pl-4 pr-10 py-3 bg-gray-100 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl text-sm"
                       :disabled="isTyping">
                <button type="submit" :disabled="!input.trim() || isTyping" 
                        class="absolute right-2 top-1.5 w-9 h-9 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <svg class="w-4 h-4 ml-0.5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                </button>
            </form>
            <div class="text-center mt-2">
                <span class="text-[10px] text-gray-400">AI อาจให้ข้อมูลที่ไม่แม่นยำ 100% โปรดใช้วิจารณญาณ</span>
            </div>
        </div>
    </div>
</div>

<script>
function aiCompanion() {
    return {
        isOpen: sessionStorage.getItem('ai_chat_open') === 'true',
        input: '',
        messages: JSON.parse(sessionStorage.getItem('ai_chat_history') || '[]'),
        isTyping: false,
        
        init() {
            this.$watch('messages', val => {
                sessionStorage.setItem('ai_chat_history', JSON.stringify(val));
            });
            this.$watch('isOpen', val => {
                sessionStorage.setItem('ai_chat_open', val);
            });
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if(this.isOpen) {
                setTimeout(() => this.scrollToBottom(), 100);
            }
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
                        history: this.messages.slice(0, -1) // Send all previous messages except the one just added
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
                const chatBox = document.getElementById('ai-chat-messages');
                if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
            }, 50);
        },
        
        formatMessage(text) {
            // Simple markdown parsing for bold and line breaks
            let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
            return formatted;
        }
    }
}
</script>
