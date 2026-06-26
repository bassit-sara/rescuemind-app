@extends('layouts.admin')
@section('title', 'จัดการความต้องการชุมชน (MT3)')
@section('page-title')
    <x-heroicon-o-megaphone class="w-5 h-5 inline-block shrink-0" /> จัดการความต้องการชุมชน
@endsection
@section('content')

@php
    $mappedNeeds = $needs->map(function($r) {
        $needsText = [];
        if($r->food_sets > 0) $needsText[] = 'ข้าวกล่อง/น้ำดื่ม (' . $r->food_sets . ' ชุด)';
        if($r->medicine_sets > 0) $needsText[] = 'ยารักษาโรค (' . $r->medicine_sets . ' ชุด)';
        if($r->cleaning_sets > 0) $needsText[] = 'อุปกรณ์ทำความสะอาด (' . $r->cleaning_sets . ' ชุด)';
        if($r->clothing_sets > 0) $needsText[] = 'เสื้อผ้า/เครื่องนุ่งห่ม (' . $r->clothing_sets . ' ชุด)';
        
        $progressText = [
            'pending' => 'รับข้อมูลประเมิน',
            'verifying' => 'ตรวจสอบและจัดสรร',
            'delivering' => 'กำลังจัดส่ง',
            'completed' => 'ได้รับความช่วยเหลือแล้ว',
        ];

        return [
            'raw_id' => $r->id,
            'id' => '#CM-' . str_pad($r->id, 3, '0', STR_PAD_LEFT),
            'community_name' => $r->community_name ?? '-',
            'contact' => $r->user ? $r->user->phone : '-',
            'population' => $r->population ?? '-',
            'zip_code' => $r->zip_code ?? '-',
            'lat' => $r->lat ?? '-',
            'lng' => $r->lng ?? '-',
            'needs' => empty($needsText) ? 'ไม่มีระบุ' : implode(', ', $needsText),
            'status' => $r->status === 'critical' ? 'วิกฤต' : ($r->status === 'urgent' ? 'เร่งด่วน' : 'ปานกลาง'),
            'progress' => $progressText[$r->progress ?? 'pending'] ?? 'รับข้อมูลประเมิน',
        ];
    });
@endphp

<script>
    window.mt3CommunityNeedsData = @json($mappedNeeds);
</script>

<div x-data="{ 
    showModal: false, 
    modalMode: 'add', 
    currentItem: { id: null, community_name: '', contact: '', needs: '', status: 'วิกฤต', progress: 'รับข้อมูลประเมิน', population: '', zip_code: '', lat: '', lng: '' },
    items: window.mt3CommunityNeedsData,
    init() {
        // init
    },
    async saveItem() {
        if(this.modalMode === 'add') {
            this.currentItem.id = '#CM-' + String(this.items.length + 1).padStart(3, '0');
            this.items.push({...this.currentItem});
            this.showModal = false;
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'เพิ่มข้อมูลสำเร็จ!', confirmButtonColor: '#4f46e5' });
        } else {
            try {
                let response = await fetch(`/super-admin/mt3/community-needs/${this.currentItem.raw_id}`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.currentItem)
                });
                if(response.ok) {
                    const index = this.items.findIndex(i => i.id === this.currentItem.id);
                    if(index > -1) this.items[index] = {...this.currentItem};
                    this.showModal = false;
                    Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกการแก้ไขสำเร็จ!', confirmButtonColor: '#4f46e5' });
                } else {
                    Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถบันทึกข้อมูลได้', confirmButtonColor: '#ef4444' });
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'เชื่อมต่อเซิร์ฟเวอร์ไม่ได้', confirmButtonColor: '#ef4444' });
            }
        }
    },
    deleteItem(id) {
        Swal.fire({ title: 'ยืนยันการลบข้อมูล?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#9ca3af', confirmButtonText: 'ใช่, ลบเลย!', cancelButtonText: 'ยกเลิก' }).then(async (result) => {
            if (result.isConfirmed) {
                let item = this.items.find(i => i.id === id);
                if (item && item.raw_id) {
                    try {
                        let response = await fetch(`/super-admin/mt3/community-needs/${item.raw_id}`, {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        if (response.ok) {
                            this.items = this.items.filter(i => i.id !== id);
                            Swal.fire({ icon: 'success', title: 'ลบข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
                        }
                    } catch (e) {}
                } else {
                    this.items = this.items.filter(i => i.id !== id);
                    Swal.fire({ icon: 'success', title: 'ลบข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
                }
            }
        });
    },
    viewItem(item) {
        Swal.fire({
            title: 'รายละเอียดความต้องการ',
            html: `
                <div class='text-left space-y-3 mt-4 text-sm text-gray-700 bg-gray-50 p-5 rounded-2xl border border-gray-100'>
                    <div class='grid grid-cols-2 gap-4'>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>ID</span> ${item.id}</p>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>ผู้ติดต่อ</span> ${item.contact}</p>
                    </div>
                    <div class='border-t border-gray-200 pt-3'>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>ชุมชน/หมู่บ้าน</span> ${item.community_name}</p>
                    </div>
                    <div class='grid grid-cols-2 gap-4 border-t border-gray-200 pt-3'>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>ประชากรที่ได้รับผลกระทบ</span> ${item.population} คน</p>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>รหัสไปรษณีย์</span> ${item.zip_code}</p>
                    </div>
                    <div class='grid grid-cols-2 gap-4 border-t border-gray-200 pt-3'>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>ละติจูด (LAT)</span> ${item.lat}</p>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>ลองจิจูด (LNG)</span> ${item.lng}</p>
                    </div>
                    <div class='border-t border-gray-200 pt-3'>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>ความต้องการหลัก</span> ${item.needs}</p>
                    </div>
                    <div class='grid grid-cols-2 gap-4 border-t border-gray-200 pt-3'>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>ระดับความเร่งด่วน</span> <span class='px-3 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-black'>${item.status}</span></p>
                        <p><span class='font-bold text-gray-900 block text-xs uppercase text-gray-500'>สถานะการดำเนินการ</span> <span class='px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-xs font-black'>${item.progress}</span></p>
                    </div>
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
        <h2 class="font-bold text-gray-800">รายการความต้องการชุมชนทั้งหมด</h2>
        <div class="flex gap-2">
            <button @click="showModal = true; modalMode = 'add'; currentItem = { id: null, community: '', contact: '', needs: '', status: 'วิกฤต' }" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700 flex items-center gap-2 transition-colors">
                <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มข้อมูลใหม่
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ชื่อชุมชน/หมู่บ้าน</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ความต้องการหลัก</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ระดับความเร่งด่วน</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.id"></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800" x-text="item.community_name"></div>
                            <div class="text-xs text-gray-500" x-text="'ผู้ติดต่อ: ' + item.contact"></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.needs"></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-bold"
                                  :class="{
                                      'bg-red-100 text-red-700': item.status === 'วิกฤต',
                                      'bg-orange-100 text-orange-700': item.status === 'เร่งด่วน',
                                      'bg-yellow-100 text-yellow-700': item.status === 'ปานกลาง'
                                  }" x-text="item.status"></span>
                            <div class="mt-1">
                                <span class="px-2 py-1 rounded-lg text-xs font-bold"
                                    :class="{
                                        'bg-gray-100 text-gray-700': item.progress === 'รับข้อมูลประเมิน',
                                        'bg-blue-100 text-blue-700': item.progress === 'ตรวจสอบและจัดสรร',
                                        'bg-indigo-100 text-indigo-700': item.progress === 'กำลังจัดส่ง',
                                        'bg-emerald-100 text-emerald-700': item.progress === 'ได้รับความช่วยเหลือแล้ว'
                                    }" x-text="item.progress"></span>
                            </div>
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
                                <x-heroicon-o-megaphone class="h-6 w-6 text-indigo-600" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title" x-text="modalMode === 'add' ? 'เพิ่มข้อมูลชุมชน' : 'แก้ไขข้อมูลชุมชน'"></h3>
                                <div class="mt-4 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">ชื่อชุมชน/หมู่บ้าน</label>
                                            <input type="text" x-model="currentItem.community_name" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-2.5" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">ประชากรโดยประมาณ</label>
                                            <input type="number" x-model="currentItem.population" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-2.5">
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">รหัสไปรษณีย์</label>
                                            <input type="text" x-model="currentItem.zip_code" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-2.5">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">ละติจูด (LAT)</label>
                                            <input type="text" x-model="currentItem.lat" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-2.5" disabled>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">ลองจิจูด (LNG)</label>
                                            <input type="text" x-model="currentItem.lng" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-2.5" disabled>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">ความต้องการหลัก (แก้ไขไม่ได้)</label>
                                        <textarea x-model="currentItem.needs" class="block w-full rounded-xl border-gray-200 bg-gray-100 shadow-sm text-gray-500 sm:text-sm transition-all px-4 py-3" rows="2" disabled></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">ระดับความเร่งด่วน</label>
                                            <select x-model="currentItem.status" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm font-bold transition-all px-4 py-2.5"
                                                :class="{
                                                    'text-red-700': currentItem.status === 'วิกฤต',
                                                    'text-orange-700': currentItem.status === 'เร่งด่วน',
                                                    'text-yellow-700': currentItem.status === 'ปานกลาง'
                                                }">
                                                <option class="text-gray-900 font-medium">วิกฤต</option>
                                                <option class="text-gray-900 font-medium">เร่งด่วน</option>
                                                <option class="text-gray-900 font-medium">ปานกลาง</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">สถานะการดำเนินการ</label>
                                            <select x-model="currentItem.progress" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm font-bold transition-all px-4 py-2.5"
                                                :class="{
                                                    'text-gray-700': currentItem.progress === 'รับข้อมูลประเมิน',
                                                    'text-blue-700': currentItem.progress === 'ตรวจสอบและจัดสรร',
                                                    'text-indigo-700': currentItem.progress === 'กำลังจัดส่ง',
                                                    'text-emerald-700': currentItem.progress === 'ได้รับความช่วยเหลือแล้ว'
                                                }">
                                                <option class="text-gray-900 font-medium">รับข้อมูลประเมิน</option>
                                                <option class="text-gray-900 font-medium">ตรวจสอบและจัดสรร</option>
                                                <option class="text-gray-900 font-medium">กำลังจัดส่ง</option>
                                                <option class="text-gray-900 font-medium">ได้รับความช่วยเหลือแล้ว</option>
                                            </select>
                                        </div>
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
