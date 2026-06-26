@extends('layouts.admin')
@section('title', 'จัดการข้อมูลฟื้นฟูสุขภาพจิต (Mental Recovery)')

@section('content')
    <div class="py-8 bg-gray-50 min-h-screen" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'assessments' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                        <div class="p-2.5 bg-emerald-100 text-emerald-600 rounded-2xl shadow-sm">
                            <x-heroicon-s-heart class="w-8 h-8" />
                        </div>
                        จัดการข้อมูลฟื้นฟูสุขภาพจิต
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">จัดการข้อมูลแบบประเมิน, การนัดหมาย, และบทความสุขภาพจิต</p>
                </div>
            </div>


            <!-- Tab Content: Assessments -->
            <div x-show="activeTab === 'assessments'" x-transition.opacity.duration.300ms style="display: none;">
                <div x-data="{ 
                    showModal: false, 
                    modalMode: 'add', 
                    currentItem: { id: null, topic: 'mental', user_name: '', score: '', severity: 'ปกติ', date: '' },
                    items: [
                        @foreach($assessments as $item)
                            { id: '#AS-{{ str_pad($item->id, 3, "0", STR_PAD_LEFT) }}', db_id: {{ $item->id }}, topic: '{{ $item->answers['topic'] ?? (in_array($item->type, ['physical', 'disease_risk', 'injury_severity', 'nutrition_status']) ? 'physical' : 'mental') }}', user_name: '{{ $item->answers['manual_entry_name'] ?? $item->user->name ?? "ผู้ใช้งานทั่วไป" }}', score: '{{ $item->answers['manual_score'] ?? (strtoupper($item->type) . ": " . $item->score . " คะแนน") }}', severity: '{{ $item->severity_label ?? $item->severity ?? "ปกติ" }}', date: '{{ $item->answers['manual_date'] ?? $item->created_at->format("Y-m-d") }}' },
                        @endforeach
                    ],
                    init() {},
                    saveItem() {
                        const payload = {
                            topic: this.currentItem.topic,
                            user_name: this.currentItem.user_name,
                            score: this.currentItem.score,
                            severity: this.currentItem.severity,
                            date: this.currentItem.date
                        };

                        if(this.modalMode === 'add') {
                            fetch(`/super-admin/mt3/mental-recovery/assessments`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify(payload)
                            }).then(res => res.json()).then(data => {
                                if (data.success) {
                                    this.currentItem.db_id = data.id;
                                    this.currentItem.id = '#AS-' + String(data.id).padStart(3, '0');
                                    this.items.unshift({...this.currentItem});
                                    this.showModal = false;
                                    Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'เพิ่มข้อมูลสำเร็จ!', confirmButtonColor: '#10b981' });
                                }
                            });
                        } else {
                            fetch(`/super-admin/mt3/mental-recovery/assessments/${this.currentItem.db_id}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify(payload)
                            }).then(res => res.json()).then(data => {
                                if (data.success) {
                                    const index = this.items.findIndex(i => i.id === this.currentItem.id);
                                    if(index > -1) this.items[index] = {...this.currentItem};
                                    this.showModal = false;
                                    Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกการแก้ไขสำเร็จ!', confirmButtonColor: '#10b981' });
                                }
                            });
                        }
                    },
                    deleteItem(id) {
                        Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#9ca3af', confirmButtonText: 'ลบเลย', cancelButtonText: 'ยกเลิก' }).then((result) => {
                            if (result.isConfirmed) {
                                const item = this.items.find(i => i.id === id);
                                if (!item) return;
                                fetch(`/super-admin/mt3/mental-recovery/assessments/${item.db_id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                    }
                                }).then(res => res.json()).then(data => {
                                    if (data.success) {
                                        this.items = this.items.filter(i => i.id !== id);
                                        Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1500 });
                                    }
                                });
                            }
                        });
                    },
                    viewItem(item) {
                        Swal.fire({
                            title: 'รายละเอียดแบบประเมิน',
                            html: `
                                <div class='text-left space-y-2 mt-4 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100'>
                                    <p><span class='font-bold'>ID:</span> ${item.id}</p>
                                    <p><span class='font-bold'>ชื่อผู้ประเมิน:</span> ${item.user_name}</p>
                                    <p><span class='font-bold'>คะแนนที่ได้:</span> ${item.score}</p>
                                    <p><span class='font-bold'>ระดับความรุนแรง:</span> ${item.severity}</p>
                                    <p><span class='font-bold'>วันที่ประเมิน:</span> ${item.date}</p>
                                </div>
                            `,
                            showCancelButton: true, showDenyButton: true, confirmButtonColor: '#6b7280', cancelButtonColor: '#3b82f6', denyButtonColor: '#ef4444', confirmButtonText: 'ปิด', cancelButtonText: 'แก้ไข', denyButtonText: 'ลบ'
                        }).then((result) => {
                            if (result.isDenied) this.deleteItem(item.id);
                            else if (result.dismiss === Swal.DismissReason.cancel) {
                                this.modalMode = 'edit'; this.currentItem = JSON.parse(JSON.stringify(item)); this.showModal = true;
                            }
                        });
                    }
                }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="font-bold text-gray-800">ผลการประเมินทั้งหมด</h2>
                        <button
                            @click="showModal = true; modalMode = 'add'; currentItem = { id: null, user_name: '', score: '', severity: 'ปกติ', date: new Date().toISOString().split('T')[0] }"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-700 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มผลประเมินใหม่
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">หัวข้อ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        ชื่อผู้ประเมิน</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">คะแนน
                                    </th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        ระดับความรุนแรง</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">วันที่
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="item in items" :key="item.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.id"></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold" 
                                                :class="item.topic === 'mental' ? 'bg-purple-50 text-purple-700' : 'bg-emerald-50 text-emerald-700'">
                                                <template x-if="item.topic === 'mental'">
                                                    <span class="flex items-center gap-1"><x-heroicon-s-sparkles class="w-3.5 h-3.5 inline-block shrink-0" /> สุขภาพจิต</span>
                                                </template>
                                                <template x-if="item.topic === 'physical'">
                                                    <span class="flex items-center gap-1"><x-heroicon-o-heart class="w-3.5 h-3.5 inline-block shrink-0" /> สุขภาพกาย</span>
                                                </template>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-gray-800" x-text="item.user_name"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.score"></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-lg text-xs font-bold" :class="{
                                                      'bg-red-100 text-red-700': item.severity.includes('รุนแรง'),
                                                      'bg-orange-100 text-orange-700': item.severity.includes('ปานกลาง'),
                                                      'bg-emerald-100 text-emerald-700': item.severity.includes('ปกติ')
                                                  }" x-text="item.severity"></span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.date"></td>
                                        <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                                            <button @click="viewItem(item)"
                                                class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-eye class="w-3 h-3" /> ดูข้อมูล
                                            </button>
                                            <button
                                                @click="showModal = true; modalMode = 'edit'; currentItem = JSON.parse(JSON.stringify(item))"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-pencil-square class="w-3 h-3" /> แก้ไข
                                            </button>
                                            <button @click="deleteItem(item.id)"
                                                class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-trash class="w-3 h-3" /> ลบ
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal for Assessments -->
                    <div x-show="showModal" style="display: none;"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                        x-transition>
                        <div @click.away="showModal = false"
                            class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden relative"
                            x-transition.scale.origin.bottom>
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900"
                                    x-text="modalMode === 'add' ? 'เพิ่มผลประเมิน' : 'แก้ไขผลประเมิน'"></h3>
                                <button @click="showModal = false"
                                    class="text-gray-400 hover:text-gray-600 transition-colors"><x-heroicon-o-x-mark
                                        class="w-5 h-5" /></button>
                            </div>
                            <form @submit.prevent="saveItem()" class="p-6 space-y-4">
                                <!-- Assessment Topic Toggle -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">หัวข้อการประเมิน</label>
                                    <div class="bg-gray-100 p-1.5 rounded-2xl flex gap-2 w-full shadow-inner">
                                        <button type="button" @click="currentItem.topic = 'mental'" 
                                                :class="currentItem.topic === 'mental' ? 'bg-white text-purple-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" 
                                                class="flex-1 flex items-center justify-center gap-2 py-2 rounded-xl font-bold transition-all duration-300 text-sm">
                                            <x-heroicon-s-sparkles class="w-4 h-4 inline-block shrink-0" /> สุขภาพจิต
                                        </button>
                                        <button type="button" @click="currentItem.topic = 'physical'" 
                                                :class="currentItem.topic === 'physical' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" 
                                                class="flex-1 flex items-center justify-center gap-2 py-2 rounded-xl font-bold transition-all duration-300 text-sm">
                                            <x-heroicon-o-heart class="w-4 h-4 inline-block mr-1 -mt-1" /> สุขภาพกาย
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">ชื่อผู้ประเมิน</label>
                                    <input type="text" x-model="currentItem.user_name"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">คะแนน/ผลลัพธ์</label>
                                    <input type="text" x-model="currentItem.score"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required placeholder="เช่น PHQ-9: 15 คะแนน">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">ระดับความรุนแรง</label>
                                    <select x-model="currentItem.severity"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                        <option value="ปกติ">ปกติ (ไม่มีอาการ)</option>
                                        <option value="ซึมเศร้าปานกลาง">ปานกลาง</option>
                                        <option value="วิตกกังวลรุนแรง">รุนแรง</option>
                                        <option value="เครียดรุนแรงมาก">รุนแรงมาก</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">วันที่ประเมิน</label>
                                    <input type="date" x-model="currentItem.date"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div class="mt-6 flex gap-3 justify-end">
                                    <button type="button" @click="showModal = false"
                                        class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">ยกเลิก</button>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-colors">บันทึกข้อมูล</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Appointments -->
            <div x-show="activeTab === 'appointments'" x-transition.opacity.duration.300ms style="display: none;">
                <div x-data="{ 
                    showModal: false, 
                    modalMode: 'add', 
                    currentItem: { id: null, patient: '', date: '', time: '', counselor: '', status: 'รอการยืนยัน' },
                    items: [
                        @foreach($appointments as $item)
                            { id: '#AP-{{ str_pad($item->id, 3, "0", STR_PAD_LEFT) }}', db_id: {{ $item->id }}, patient: '{{ $item->user->name ?? "ผู้ขอรับคำปรึกษา" }}', date: '{{ $item->scheduled_at ? \Carbon\Carbon::parse($item->scheduled_at)->format("Y-m-d") : "" }}', time: '{{ $item->scheduled_at ? \Carbon\Carbon::parse($item->scheduled_at)->format("H:i") : "" }}', counselor: '{{ $item->mentalOfficer->name ?? "ยังไม่ระบุ" }}', status: '{{ $item->status ?? "รอการยืนยัน" }}' },
                        @endforeach
                    ],
                    init() {},
                    saveItem() {
                        if(this.modalMode === 'add') {
                            this.currentItem.id = '#AP-' + String(this.items.length + 1).padStart(3, '0');
                            this.items.push({...this.currentItem});
                        } else {
                            const index = this.items.findIndex(i => i.id === this.currentItem.id);
                            if(index > -1) this.items[index] = {...this.currentItem};
                        }
                        this.showModal = false;
                        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกข้อมูลสำเร็จ!', confirmButtonColor: '#10b981' });
                    },
                    deleteItem(id) {
                        Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#9ca3af', confirmButtonText: 'ลบเลย', cancelButtonText: 'ยกเลิก' }).then((result) => {
                            if (result.isConfirmed) {
                                this.items = this.items.filter(i => i.id !== id);
                                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1500 });
                            }
                        });
                    },
                    viewItem(item) {
                        Swal.fire({
                            title: 'รายละเอียดการนัดหมาย',
                            html: `
                                <div class='text-left space-y-2 mt-4 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100'>
                                    <p><span class='font-bold'>ID:</span> ${item.id}</p>
                                    <p><span class='font-bold'>ผู้ขอรับคำปรึกษา:</span> ${item.patient}</p>
                                    <p><span class='font-bold'>วันที่:</span> ${item.date}</p>
                                    <p><span class='font-bold'>เวลา:</span> ${item.time}</p>
                                    <p><span class='font-bold'>ผู้ให้คำปรึกษา:</span> ${item.counselor}</p>
                                    <p><span class='font-bold'>สถานะ:</span> ${item.status}</p>
                                </div>
                            `,
                            showCancelButton: true, showDenyButton: true, confirmButtonColor: '#6b7280', cancelButtonColor: '#3b82f6', denyButtonColor: '#ef4444', confirmButtonText: 'ปิด', cancelButtonText: 'แก้ไข', denyButtonText: 'ลบ'
                        }).then((result) => {
                            if (result.isDenied) this.deleteItem(item.id);
                            else if (result.dismiss === Swal.DismissReason.cancel) {
                                this.modalMode = 'edit'; this.currentItem = JSON.parse(JSON.stringify(item)); this.showModal = true;
                            }
                        });
                    }
                }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="font-bold text-gray-800">รายการนัดหมายทั้งหมด</h2>
                        <button
                            @click="showModal = true; modalMode = 'add'; currentItem = { id: null, patient: '', date: '', time: '', counselor: '', status: 'รอการยืนยัน' }"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-700 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มนัดหมายใหม่
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        ผู้ขอรับคำปรึกษา</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">วัน-เวลา
                                    </th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        ผู้ให้คำปรึกษา</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">สถานะ
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="item in items" :key="item.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.id"></td>
                                        <td class="px-6 py-4 font-bold text-gray-800" x-text="item.patient"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><span x-text="item.date"></span> <span
                                                class="font-bold text-gray-800" x-text="item.time"></span></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.counselor"></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-lg text-xs font-bold" :class="{
                                                      'bg-yellow-100 text-yellow-700': item.status === 'รอการยืนยัน',
                                                      'bg-emerald-100 text-emerald-700': item.status === 'ยืนยันแล้ว',
                                                      'bg-gray-100 text-gray-700': item.status === 'เสร็จสิ้น'
                                                  }" x-text="item.status"></span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                                            <button @click="viewItem(item)"
                                                class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-eye class="w-3 h-3" /> ดูข้อมูล
                                            </button>
                                            <button
                                                @click="showModal = true; modalMode = 'edit'; currentItem = JSON.parse(JSON.stringify(item))"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-pencil-square class="w-3 h-3" /> แก้ไข
                                            </button>
                                            <button @click="deleteItem(item.id)"
                                                class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-trash class="w-3 h-3" /> ลบ
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal for Appointments -->
                    <div x-show="showModal" style="display: none;"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                        x-transition>
                        <div @click.away="showModal = false"
                            class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden relative"
                            x-transition.scale.origin.bottom>
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900"
                                    x-text="modalMode === 'add' ? 'เพิ่มนัดหมาย' : 'แก้ไขนัดหมาย'"></h3>
                                <button @click="showModal = false"
                                    class="text-gray-400 hover:text-gray-600 transition-colors"><x-heroicon-o-x-mark
                                        class="w-5 h-5" /></button>
                            </div>
                            <form @submit.prevent="saveItem()" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">ผู้ขอรับคำปรึกษา</label>
                                    <input type="text" x-model="currentItem.patient"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">วันที่</label>
                                        <input type="date" x-model="currentItem.date"
                                            class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">เวลา</label>
                                        <input type="time" x-model="currentItem.time"
                                            class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                            required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">ผู้ให้คำปรึกษา</label>
                                    <input type="text" x-model="currentItem.counselor"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">สถานะ</label>
                                    <select x-model="currentItem.status"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                        <option value="รอการยืนยัน">รอการยืนยัน</option>
                                        <option value="ยืนยันแล้ว">ยืนยันแล้ว</option>
                                        <option value="เสร็จสิ้น">เสร็จสิ้น</option>
                                    </select>
                                </div>
                                <div class="mt-6 flex gap-3 justify-end">
                                    <button type="button" @click="showModal = false"
                                        class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">ยกเลิก</button>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-colors">บันทึกข้อมูล</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Articles -->
            <div x-show="activeTab === 'articles'" x-transition.opacity.duration.300ms style="display: none;">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="font-bold text-gray-800">จัดการคลังความรู้ บทความและคำแนะนำ</h2>
                        <a href="{{ route('mental-officer.articles.create') }}"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-700 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มบทความใหม่
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-80">หัวข้อบทความ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ผู้เขียน</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">เวลาอ่าน</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($mentalArticles as $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500">#AR-{{ str_pad($item->id, 3, "0", STR_PAD_LEFT) }}</td>
                                        <td class="px-6 py-4 font-bold text-gray-800 max-w-xs"><span class="block truncate" title="{{ $item->title }}">{{ $item->title }}</span></td>
                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $item->author }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $item->read_time }}</td>
                                        <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                                            <a href="{{ route('mental.articles.show', $item->id) }}" target="_blank"
                                                class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-eye class="w-3 h-3" /> ดูเนื้อหา
                                            </a>
                                            <a href="{{ route('mental-officer.articles.edit', $item->id) }}"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-pencil-square class="w-3 h-3" /> แก้ไข
                                            </a>
                                            <form action="{{ route('mental-officer.articles.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบบทความนี้?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                                    <x-heroicon-o-trash class="w-3 h-3" /> ลบ
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            <p class="text-sm">ยังไม่มีบทความในระบบ</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Mood Tracker -->
            <div x-show="activeTab === 'mood'" x-transition.opacity.duration.300ms style="display: none;">
                <div x-data="{ 
                    showModal: false, 
                    modalMode: 'add', 
                    currentItem: { id: null, user_name: '', mood: 'ดี', note: '', date: '' },
                    items: [
                        @foreach($moods as $item)
                            { id: '#MD-{{ str_pad($item->id, 3, "0", STR_PAD_LEFT) }}', db_id: {{ $item->id }}, user_name: '{{ $item->user->name ?? "ผู้ใช้งานทั่วไป" }}', mood: '{{ $item->mood_label ?? "ดี" }}', note: '{{ Str::limit($item->note, 50) }}', date: '{{ $item->logged_date ? \Carbon\Carbon::parse($item->logged_date)->format("Y-m-d") : $item->created_at->format("Y-m-d") }}' },
                        @endforeach
                    ],
                    init() {},
                    saveItem() {
                        if(this.modalMode === 'add') {
                            this.currentItem.id = '#MD-' + String(this.items.length + 1).padStart(3, '0');
                            this.items.push({...this.currentItem});
                        } else {
                            const index = this.items.findIndex(i => i.id === this.currentItem.id);
                            if(index > -1) this.items[index] = {...this.currentItem};
                        }
                        this.showModal = false;
                        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกข้อมูลสำเร็จ!', confirmButtonColor: '#10b981' });
                    },
                    deleteItem(id) {
                        Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#9ca3af', confirmButtonText: 'ลบเลย', cancelButtonText: 'ยกเลิก' }).then((result) => {
                            if (result.isConfirmed) {
                                this.items = this.items.filter(i => i.id !== id);
                                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1500 });
                            }
                        });
                    },
                    viewItem(item) {
                        Swal.fire({
                            title: 'รายละเอียดบันทึกอารมณ์',
                            html: `
                                <div class='text-left space-y-2 mt-4 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100'>
                                    <p><span class='font-bold'>ID:</span> ${item.id}</p>
                                    <p><span class='font-bold'>ผู้ใช้:</span> ${item.user_name}</p>
                                    <p><span class='font-bold'>อารมณ์:</span> ${item.mood}</p>
                                    <p><span class='font-bold'>บันทึกเพิ่มเติม:</span> ${item.note}</p>
                                    <p><span class='font-bold'>วันที่:</span> ${item.date}</p>
                                </div>
                            `,
                            showCancelButton: true, showDenyButton: true, confirmButtonColor: '#6b7280', cancelButtonColor: '#3b82f6', denyButtonColor: '#ef4444', confirmButtonText: 'ปิด', cancelButtonText: 'แก้ไข', denyButtonText: 'ลบ'
                        }).then((result) => {
                            if (result.isDenied) this.deleteItem(item.id);
                            else if (result.dismiss === Swal.DismissReason.cancel) {
                                this.modalMode = 'edit'; this.currentItem = JSON.parse(JSON.stringify(item)); this.showModal = true;
                            }
                        });
                    }
                }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="font-bold text-gray-800">จัดการข้อมูล Mood Tracker</h2>
                        <button
                            @click="showModal = true; modalMode = 'add'; currentItem = { id: null, user_name: '', mood: 'ดี', note: '', date: new Date().toISOString().split('T')[0] }"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-700 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มบันทึกอารมณ์
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ผู้ใช้
                                    </th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">อารมณ์
                                    </th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">วันที่
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="item in items" :key="item.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.id"></td>
                                        <td class="px-6 py-4 font-bold text-gray-800" x-text="item.user_name"></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-lg text-xs font-bold" :class="{
                                                      'bg-emerald-100 text-emerald-700': item.mood === 'ดี' || item.mood === 'ดีมาก',
                                                      'bg-gray-100 text-gray-700': item.mood === 'เฉยๆ',
                                                      'bg-red-100 text-red-700': item.mood === 'แย่' || item.mood === 'เศร้า'
                                                  }" x-text="item.mood"></span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.date"></td>
                                        <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                                            <button @click="viewItem(item)"
                                                class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-eye class="w-3 h-3" /> ดูข้อมูล
                                            </button>
                                            <button
                                                @click="showModal = true; modalMode = 'edit'; currentItem = JSON.parse(JSON.stringify(item))"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-pencil-square class="w-3 h-3" /> แก้ไข
                                            </button>
                                            <button @click="deleteItem(item.id)"
                                                class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-trash class="w-3 h-3" /> ลบ
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" style="display: none;"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                        x-transition>
                        <div @click.away="showModal = false"
                            class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden relative"
                            x-transition.scale.origin.bottom>
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900"
                                    x-text="modalMode === 'add' ? 'เพิ่มบันทึกอารมณ์' : 'แก้ไขบันทึกอารมณ์'"></h3>
                                <button @click="showModal = false"
                                    class="text-gray-400 hover:text-gray-600 transition-colors"><x-heroicon-o-x-mark
                                        class="w-5 h-5" /></button>
                            </div>
                            <form @submit.prevent="saveItem()" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">ผู้ใช้</label>
                                    <input type="text" x-model="currentItem.user_name"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">อารมณ์</label>
                                    <select x-model="currentItem.mood"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                        <option value="ดีมาก">ดีมาก</option>
                                        <option value="ดี">ดี</option>
                                        <option value="เฉยๆ">เฉยๆ</option>
                                        <option value="แย่">แย่</option>
                                        <option value="เศร้า">เศร้า</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">บันทึกเพิ่มเติม</label>
                                    <textarea x-model="currentItem.note"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        rows="3"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">วันที่</label>
                                    <input type="date" x-model="currentItem.date"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div class="mt-6 flex gap-3 justify-end">
                                    <button type="button" @click="showModal = false"
                                        class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">ยกเลิก</button>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-colors">บันทึกข้อมูล</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Journal Diary -->
            <div x-show="activeTab === 'journal'" x-transition.opacity.duration.300ms style="display: none;">
                <div x-data="{ 
                    showModal: false, 
                    modalMode: 'add', 
                    currentItem: { id: null, user_name: '', title: '', content: '', date: '' },
                    items: [
                        @foreach($journals as $item)
                            { id: '#JR-{{ str_pad($item->id, 3, "0", STR_PAD_LEFT) }}', db_id: {{ $item->id }}, user_name: '{{ $item->user->name ?? "ผู้ใช้งานทั่วไป" }}', title: 'บันทึกประจำวัน', content: '{{ Str::limit($item->content, 50) }}', date: '{{ $item->created_at->format("Y-m-d") }}' },
                        @endforeach
                    ],
                    init() {},
                    saveItem() {
                        if(this.modalMode === 'add') {
                            this.currentItem.id = '#JR-' + String(this.items.length + 1).padStart(3, '0');
                            this.items.push({...this.currentItem});
                        } else {
                            const index = this.items.findIndex(i => i.id === this.currentItem.id);
                            if(index > -1) this.items[index] = {...this.currentItem};
                        }
                        this.showModal = false;
                        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกข้อมูลสำเร็จ!', confirmButtonColor: '#10b981' });
                    },
                    deleteItem(id) {
                        Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#9ca3af', confirmButtonText: 'ลบเลย', cancelButtonText: 'ยกเลิก' }).then((result) => {
                            if (result.isConfirmed) {
                                this.items = this.items.filter(i => i.id !== id);
                                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1500 });
                            }
                        });
                    },
                    viewItem(item) {
                        Swal.fire({
                            title: 'รายละเอียดไดอารี่',
                            html: `
                                <div class='text-left space-y-2 mt-4 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100'>
                                    <p><span class='font-bold'>ID:</span> ${item.id}</p>
                                    <p><span class='font-bold'>ผู้ใช้:</span> ${item.user_name}</p>
                                    <p><span class='font-bold'>หัวข้อ:</span> ${item.title}</p>
                                    <p><span class='font-bold'>เนื้อหา:</span> ${item.content}</p>
                                    <p><span class='font-bold'>วันที่:</span> ${item.date}</p>
                                </div>
                            `,
                            showCancelButton: true, showDenyButton: true, confirmButtonColor: '#6b7280', cancelButtonColor: '#3b82f6', denyButtonColor: '#ef4444', confirmButtonText: 'ปิด', cancelButtonText: 'แก้ไข', denyButtonText: 'ลบ'
                        }).then((result) => {
                            if (result.isDenied) this.deleteItem(item.id);
                            else if (result.dismiss === Swal.DismissReason.cancel) {
                                this.modalMode = 'edit'; this.currentItem = JSON.parse(JSON.stringify(item)); this.showModal = true;
                            }
                        });
                    }
                }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="font-bold text-gray-800">จัดการข้อมูล Journal Diary</h2>
                        <button
                            @click="showModal = true; modalMode = 'add'; currentItem = { id: null, user_name: '', title: '', content: '', date: new Date().toISOString().split('T')[0] }"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-700 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มไดอารี่
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ผู้ใช้
                                    </th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">หัวข้อ
                                    </th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">วันที่
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="item in items" :key="item.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.id"></td>
                                        <td class="px-6 py-4 font-bold text-gray-800" x-text="item.user_name"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.title"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.date"></td>
                                        <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                                            <button @click="viewItem(item)"
                                                class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-eye class="w-3 h-3" /> ดูข้อมูล
                                            </button>
                                            <button
                                                @click="showModal = true; modalMode = 'edit'; currentItem = JSON.parse(JSON.stringify(item))"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-pencil-square class="w-3 h-3" /> แก้ไข
                                            </button>
                                            <button @click="deleteItem(item.id)"
                                                class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-trash class="w-3 h-3" /> ลบ
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" style="display: none;"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                        x-transition>
                        <div @click.away="showModal = false"
                            class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden relative"
                            x-transition.scale.origin.bottom>
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900"
                                    x-text="modalMode === 'add' ? 'เพิ่มไดอารี่' : 'แก้ไขไดอารี่'"></h3>
                                <button @click="showModal = false"
                                    class="text-gray-400 hover:text-gray-600 transition-colors"><x-heroicon-o-x-mark
                                        class="w-5 h-5" /></button>
                            </div>
                            <form @submit.prevent="saveItem()" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">ผู้ใช้</label>
                                    <input type="text" x-model="currentItem.user_name"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">หัวข้อ</label>
                                    <input type="text" x-model="currentItem.title"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">เนื้อหา</label>
                                    <textarea x-model="currentItem.content"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        rows="5" required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">วันที่</label>
                                    <input type="date" x-model="currentItem.date"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div class="mt-6 flex gap-3 justify-end">
                                    <button type="button" @click="showModal = false"
                                        class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">ยกเลิก</button>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-colors">บันทึกข้อมูล</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: AI Companion -->
            <div x-show="activeTab === 'ai_companion'" x-transition.opacity.duration.300ms style="display: none;">
                <div x-data="{ 
                    showModal: false, 
                    modalMode: 'add', 
                    currentItem: { id: null, user_name: '', topic: '', messages: 0, date: '' },
                    items: [
                        { id: '#AI-001', user_name: 'สมหญิง บุญมา', topic: 'ปัญหาครอบครัว', messages: 24, date: '2026-06-21' },
                        { id: '#AI-002', user_name: 'มานะ อดทน', topic: 'ความเครียดจากการทำงาน', messages: 12, date: '2026-06-20' }
                    ],
                    init() {
                        const stored = localStorage.getItem('mt3_mental_ai');
                        if (stored) this.items = JSON.parse(stored);
                        this.$watch('items', val => localStorage.setItem('mt3_mental_ai', JSON.stringify(val)));
                    },
                    saveItem() {
                        if(this.modalMode === 'add') {
                            this.currentItem.id = '#AI-' + String(this.items.length + 1).padStart(3, '0');
                            this.items.push({...this.currentItem});
                        } else {
                            const index = this.items.findIndex(i => i.id === this.currentItem.id);
                            if(index > -1) this.items[index] = {...this.currentItem};
                        }
                        this.showModal = false;
                        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกข้อมูลสำเร็จ!', confirmButtonColor: '#10b981' });
                    },
                    deleteItem(id) {
                        Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#9ca3af', confirmButtonText: 'ลบเลย', cancelButtonText: 'ยกเลิก' }).then((result) => {
                            if (result.isConfirmed) {
                                this.items = this.items.filter(i => i.id !== id);
                                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1500 });
                            }
                        });
                    },
                    viewItem(item) {
                        Swal.fire({
                            title: 'รายละเอียด AI Session',
                            html: `
                                <div class='text-left space-y-2 mt-4 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100'>
                                    <p><span class='font-bold'>ID:</span> ${item.id}</p>
                                    <p><span class='font-bold'>ผู้ใช้:</span> ${item.user_name}</p>
                                    <p><span class='font-bold'>หัวข้อที่คุย:</span> ${item.topic}</p>
                                    <p><span class='font-bold'>จำนวนข้อความ:</span> ${item.messages} ข้อความ</p>
                                    <p><span class='font-bold'>วันที่:</span> ${item.date}</p>
                                </div>
                            `,
                            showCancelButton: true, showDenyButton: true, confirmButtonColor: '#6b7280', cancelButtonColor: '#3b82f6', denyButtonColor: '#ef4444', confirmButtonText: 'ปิด', cancelButtonText: 'แก้ไข', denyButtonText: 'ลบ'
                        }).then((result) => {
                            if (result.isDenied) this.deleteItem(item.id);
                            else if (result.dismiss === Swal.DismissReason.cancel) {
                                this.modalMode = 'edit'; this.currentItem = JSON.parse(JSON.stringify(item)); this.showModal = true;
                            }
                        });
                    }
                }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="font-bold text-gray-800">จัดการข้อมูล AI Companion Sessions</h2>
                        <button
                            @click="showModal = true; modalMode = 'add'; currentItem = { id: null, user_name: '', topic: '', messages: 0, date: new Date().toISOString().split('T')[0] }"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-700 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มเซสชัน
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ผู้ใช้
                                    </th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        หัวข้อที่คุย</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">วันที่
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="item in items" :key="item.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.id"></td>
                                        <td class="px-6 py-4 font-bold text-gray-800" x-text="item.user_name"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.topic"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.date"></td>
                                        <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                                            <button @click="viewItem(item)"
                                                class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-eye class="w-3 h-3" /> ดูข้อมูล
                                            </button>
                                            <button
                                                @click="showModal = true; modalMode = 'edit'; currentItem = JSON.parse(JSON.stringify(item))"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-pencil-square class="w-3 h-3" /> แก้ไข
                                            </button>
                                            <button @click="deleteItem(item.id)"
                                                class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                                <x-heroicon-o-trash class="w-3 h-3" /> ลบ
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" style="display: none;"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                        x-transition>
                        <div @click.away="showModal = false"
                            class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden relative"
                            x-transition.scale.origin.bottom>
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900"
                                    x-text="modalMode === 'add' ? 'เพิ่มเซสชัน AI' : 'แก้ไขเซสชัน AI'"></h3>
                                <button @click="showModal = false"
                                    class="text-gray-400 hover:text-gray-600 transition-colors"><x-heroicon-o-x-mark
                                        class="w-5 h-5" /></button>
                            </div>
                            <form @submit.prevent="saveItem()" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">ผู้ใช้</label>
                                    <input type="text" x-model="currentItem.user_name"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">หัวข้อที่คุย</label>
                                    <input type="text" x-model="currentItem.topic"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">จำนวนข้อความ</label>
                                    <input type="number" x-model.number="currentItem.messages"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">วันที่</label>
                                    <input type="date" x-model="currentItem.date"
                                        class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        required>
                                </div>
                                <div class="mt-6 flex gap-3 justify-end">
                                    <button type="button" @click="showModal = false"
                                        class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">ยกเลิก</button>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-colors">บันทึกข้อมูล</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection