@extends('layouts.admin')
@section('title', 'จัดการข้อมูลฟื้นฟูอาชีพ (MT3)')
@section('page-title')
    <x-heroicon-o-briefcase class="w-5 h-5 inline-block shrink-0" /> จัดการข้อมูลฟื้นฟูอาชีพ
@endsection
@section('content')

<div x-data="{ 
    showModal: false, 
    modalMode: 'add', 
    currentItem: { id: null, name: '', needs: '', status: 'รอตรวจสอบ' },
    items: [
        { id: '#LL-001', name: 'ลุงบุญมี รักนา (เกษตรกร (ทำนา))', needs: 'ขอรับเมล็ดพันธุ์ข้าว, เงินเยียวยา', status: 'รอตรวจสอบ' },
        { id: '#LL-002', name: 'ป้าสมพร ขายข้าวแกง (แม่ค้า (ธุรกิจรายย่อย))', needs: 'ขอทุนซื้ออุปกรณ์ทำกินใหม่, อบรมอาชีพเสริม', status: 'อนุมัติแล้ว' }
    ],
    init() {
        const stored = localStorage.getItem('mt3_livelihood');
        if (stored) {
            this.items = JSON.parse(stored);
        }
        this.$watch('items', value => {
            localStorage.setItem('mt3_livelihood', JSON.stringify(value));
        });
    },
    saveItem() {
        if(this.modalMode === 'add') {
            this.currentItem.id = '#LL-' + String(this.items.length + 1).padStart(3, '0');
            this.items.push({...this.currentItem});
        } else {
            const index = this.items.findIndex(i => i.id === this.currentItem.id);
            if(index > -1) this.items[index] = {...this.currentItem};
        }
        this.showModal = false;
        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: this.modalMode === 'add' ? 'เพิ่มข้อมูลสำเร็จ!' : 'บันทึกการแก้ไขสำเร็จ!', confirmButtonColor: '#4f46e5' });
    },
    deleteItem(id) {
        Swal.fire({ title: 'ยืนยันการลบข้อมูล?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#9ca3af', confirmButtonText: 'ใช่, ลบเลย!', cancelButtonText: 'ยกเลิก' }).then((result) => {
            if (result.isConfirmed) {
                this.items = this.items.filter(i => i.id !== id);
                Swal.fire({ icon: 'success', title: 'ลบข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
            }
        });
    },
    viewItem(item) {
        Swal.fire({
            title: 'รายละเอียดการขอฟื้นฟูอาชีพ',
            html: `
                <div class='text-left space-y-2 mt-4 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100'>
                    <p><span class='font-bold text-gray-900'>ID:</span> ${item.id}</p>
                    <p><span class='font-bold text-gray-900'>ผู้แจ้ง / อาชีพเดิม:</span> ${item.name}</p>
                    <p><span class='font-bold text-gray-900'>ความประสงค์:</span> ${item.needs}</p>
                    <p><span class='font-bold text-gray-900'>สถานะ:</span> <span class='px-2 py-1 rounded bg-gray-200 text-xs font-bold'>${item.status}</span></p>
                </div>
            `,
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: '#6b7280',
            cancelButtonColor: '#3b82f6',
            denyButtonColor: '#ef4444',
            confirmButtonText: 'ปิดหน้าต่าง',
            cancelButtonText: 'แก้ไข',
            denyButtonText: 'ลบ'
        }).then((result) => {
            if (result.isDenied) {
                this.deleteItem(item.id);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                this.modalMode = 'edit';
                this.currentItem = JSON.parse(JSON.stringify(item));
                this.showModal = true;
            }
        });
    }
}" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h2 class="font-bold text-gray-800">รายการแจ้งความเสียหาย/ขอฟื้นฟูอาชีพ</h2>
        <div class="flex gap-2">
            <button @click="showModal = true; modalMode = 'add'; currentItem = { id: null, name: '', needs: '', status: 'รอตรวจสอบ' }" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700 flex items-center gap-2 transition-colors">
                <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มข้อมูลใหม่
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ผู้แจ้ง / อาชีพเดิม</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ความประสงค์</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">สถานะ</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.id"></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800" x-text="item.name.split(' (')[0]"></div>
                            <div class="text-xs text-gray-500" x-text="item.name.includes('(') ? item.name.substring(item.name.indexOf('(')+1, item.name.length-1) : ''"></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.needs"></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-bold"
                                  :class="{
                                      'bg-yellow-100 text-yellow-700': item.status === 'รอตรวจสอบ',
                                      'bg-emerald-100 text-emerald-700': item.status === 'อนุมัติแล้ว',
                                      'bg-blue-100 text-blue-700': item.status === 'ช่วยเหลือแล้ว'
                                  }" x-text="item.status"></span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                            <button @click="viewItem(item)" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors inline-flex items-center gap-1">
                                <x-heroicon-o-eye class="w-3 h-3" /> ดูข้อมูล
                            </button>
                            <button @click="showModal = true; modalMode = 'edit'; currentItem = JSON.parse(JSON.stringify(item))" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
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

    <!-- Modal Form -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="saveItem()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <x-heroicon-o-briefcase class="h-6 w-6 text-indigo-600" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title" x-text="modalMode === 'add' ? 'เพิ่มคำขอฟื้นฟูอาชีพ' : 'แก้ไขข้อมูลฟื้นฟูอาชีพ'"></h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">ชื่อผู้แจ้ง / อาชีพเดิม</label>
                                        <input type="text" x-model="currentItem.name" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">ความประสงค์</label>
                                        <textarea x-model="currentItem.needs" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" rows="3" required></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">สถานะ</label>
                                        <select x-model="currentItem.status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option>รอตรวจสอบ</option>
                                            <option>อนุมัติแล้ว</option>
                                            <option>ช่วยเหลือแล้ว</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            บันทึกข้อมูล
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            ยกเลิก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
