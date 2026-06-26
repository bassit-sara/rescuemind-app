@extends('layouts.admin')
@section('title', 'จัดการแบบฟอร์มการประเมิน')

@section('content')
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                        <div class="p-2.5 bg-emerald-100 text-emerald-600 rounded-2xl shadow-sm">
                            <x-heroicon-o-document-plus class="w-8 h-8" />
                        </div>
                        ระบบสร้างแบบประเมิน
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">สร้าง ลบ และแก้ไขชุดคำถามสำหรับแบบประเมินสุขภาพจิตและสุขภาพกาย</p>
                </div>
            </div>

            <div x-data="formBuilder()" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h2 class="font-bold text-gray-800">แบบฟอร์มทั้งหมด</h2>
                    <button @click="openAddModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-700 flex items-center gap-2 transition-colors">
                        <x-heroicon-o-plus class="w-4 h-4" /> สร้างแบบประเมินใหม่
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ชื่อแบบประเมิน</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">หมวดหมู่</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">จำนวนข้อ</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="item in items" :key="item.id">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-800" x-text="item.title"></td>
                                    <td class="px-6 py-4 text-sm text-gray-500" x-text="item.slug"></td>
                                    <td class="px-6 py-4">
                                        <span x-show="item.category === 'mental'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">สุขภาพจิต</span>
                                        <span x-show="item.category === 'physical'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">สุขภาพกาย</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600" x-text="item.questions.length + ' ข้อ'"></td>
                                    <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                                        <button @click="openEditModal(item)" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                            <x-heroicon-o-pencil-square class="w-3 h-3" /> แก้ไข
                                        </button>
                                        <button @click="deleteItem(item.id)" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                            <x-heroicon-o-trash class="w-3 h-3" /> ลบ
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition>
                    <div @click.away="showModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 overflow-hidden relative max-h-[90vh] flex flex-col" x-transition.scale.origin.bottom>
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900" x-text="modalMode === 'add' ? 'สร้างแบบประเมินใหม่' : 'แก้ไขแบบประเมิน'"></h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors"><x-heroicon-o-x-mark class="w-5 h-5" /></button>
                        </div>
                        <div class="p-6 overflow-y-auto flex-1">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">ชื่อแบบประเมิน</label>
                                        <input type="text" x-model="currentItem.title" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="เช่น แบบประเมินความเครียดเด็ก">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Slug (รหัสภาษาอังกฤษ)</label>
                                        <input type="text" x-model="currentItem.slug" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="เช่น child_stress">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">หมวดหมู่</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                            :class="currentItem.category === 'mental' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300'">
                                            <input type="radio" x-model="currentItem.category" value="mental" class="sr-only">
                                            <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" /></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-800">สุขภาพจิต</div>
                                                <div class="text-xs text-gray-500">แบบประเมินสุขภาพใจ</div>
                                            </div>
                                        </label>
                                        <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                            :class="currentItem.category === 'physical' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-gray-300'">
                                            <input type="radio" x-model="currentItem.category" value="physical" class="sr-only">
                                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-800">สุขภาพกาย</div>
                                                <div class="text-xs text-gray-500">แบบประเมินสุขภาพร่างกาย</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">คำอธิบาย</label>
                                    <textarea x-model="currentItem.description" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm" rows="2"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">เวลาที่ใช้ (เช่น ~3 นาที)</label>
                                        <input type="text" x-model="currentItem.time_estimate" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">สี Theme (indigo, pink, orange)</label>
                                        <input type="text" x-model="currentItem.color_theme" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    </div>
                                </div>
                                
                                <div class="mt-6 border-t border-gray-100 pt-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <label class="block text-sm font-bold text-gray-700">คำถาม (ข้อละบรรทัด)</label>
                                    </div>
                                    <textarea x-model="questionsText" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm" rows="10" placeholder="พิมพ์คำถามข้อที่ 1\nพิมพ์คำถามข้อที่ 2\nพิมพ์คำถามข้อที่ 3"></textarea>
                                    <p class="text-xs text-gray-500 mt-1">* ให้ผู้ใช้งานประเมินคะแนน 0-3 (ไม่มีเลย - เป็นประจำ) ต่อข้อ</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                            <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">ยกเลิก</button>
                            <button type="button" @click="saveItem()" class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-colors">บันทึกข้อมูล</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formBuilder', () => ({
                showModal: false,
                modalMode: 'add',
                questionsText: '',
                currentItem: { id: null, slug: '', category: 'mental', title: '', description: '', time_estimate: '~3 นาที', icon: 'o-clipboard-document-check', color_theme: 'indigo', questions: [] },
                items: [
                    @foreach($forms as $form)
                        { id: {{ $form->id }}, slug: '{{ $form->slug }}', category: '{{ $form->category ?? 'mental' }}', title: '{{ $form->title }}', description: '{{ $form->description }}', time_estimate: '{{ $form->time_estimate }}', icon: '{{ $form->icon }}', color_theme: '{{ $form->color_theme }}', questions: {!! json_encode($form->questions) !!} },
                    @endforeach
                ],
                openAddModal() {
                    this.modalMode = 'add';
                    this.currentItem = { id: null, slug: '', category: 'mental', title: '', description: '', time_estimate: '~3 นาที', icon: 'o-clipboard-document-check', color_theme: 'indigo', questions: [] };
                    this.questionsText = '';
                    this.showModal = true;
                },
                openEditModal(item) {
                    this.modalMode = 'edit';
                    this.currentItem = JSON.parse(JSON.stringify(item));
                    this.questionsText = this.currentItem.questions.join('\n');
                    this.showModal = true;
                },
                saveItem() {
                    if(!this.currentItem.title || !this.currentItem.slug) {
                        Swal.fire({ icon: 'error', title: 'ข้อมูลไม่ครบ', text: 'กรุณากรอกชื่อและ Slug' });
                        return;
                    }
                    
                    this.currentItem.questions = this.questionsText.split('\n').map(q => q.trim()).filter(q => q !== '');
                    
                    if(this.currentItem.questions.length === 0) {
                        Swal.fire({ icon: 'error', title: 'ข้อมูลไม่ครบ', text: 'กรุณาเพิ่มคำถามอย่างน้อย 1 ข้อ' });
                        return;
                    }

                    const url = this.modalMode === 'add' ? '/super-admin/mt3/mental-forms' : `/super-admin/mt3/mental-forms/${this.currentItem.id}`;
                    const method = this.modalMode === 'add' ? 'POST' : 'PUT';

                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify(this.currentItem)
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            if (this.modalMode === 'add') {
                                this.currentItem.id = data.id;
                                this.items.unshift({...this.currentItem});
                            } else {
                                const index = this.items.findIndex(i => i.id === this.currentItem.id);
                                this.items[index] = {...this.currentItem};
                            }
                            this.showModal = false;
                            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกข้อมูลเรียบร้อย!', confirmButtonColor: '#10b981' });
                        }
                    }).catch(() => {
                        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'slug อาจซ้ำ หรือข้อมูลไม่ถูกต้อง' });
                    });
                },
                deleteItem(id) {
                    Swal.fire({
                        title: 'ยืนยันการลบ?',
                        text: 'คุณต้องการลบแบบประเมินนี้ใช่หรือไม่?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#9ca3af',
                        confirmButtonText: 'ใช่, ลบเลย!',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/super-admin/mt3/mental-forms/${id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') }
                            }).then(res => res.json()).then(data => {
                                if (data.success) {
                                    this.items = this.items.filter(i => i.id !== id);
                                    Swal.fire('ลบสำเร็จ!', '', 'success');
                                }
                            });
                        }
                    });
                }
            }));
        });
    </script>
    @endpush
@endsection
