@extends('layouts.admin')
@section('title', 'จัดการข้อมูลฟื้นฟูบ้าน (MT3)')
@section('page-title')
    <x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /> จัดการข้อมูลฟื้นฟูบ้าน
@endsection
@section('content')

<div x-data="{ 
    showModal: false, 
    modalMode: 'add', 
    currentItem: { id: null, name: '', phone: '', details: '', province: '', status: 'รอตรวจสอบ' },
    items: [
        { id: '#HR-001', name: 'นายสมชาย รักดี', phone: '081-xxx-xxxx', details: 'หลังคาทะลุ, น้ำท่วมชั้น 1', province: 'เชียงราย', status: 'รอตรวจสอบ' },
        { id: '#HR-002', name: 'นางสมหญิง บุญมา', phone: '089-xxx-xxxx', details: 'ดินสไลด์ทับกำแพงบ้านพัง', province: 'เชียงใหม่', status: 'กำลังซ่อมแซม' }
    ],
    init() {
        const stored = localStorage.getItem('mt3_home_recovery');
        if (stored) {
            this.items = JSON.parse(stored);
        }
        this.$watch('items', value => {
            localStorage.setItem('mt3_home_recovery', JSON.stringify(value));
        });
    },
    saveItem() {
        if(this.modalMode === 'add') {
            this.currentItem.id = '#HR-' + String(this.items.length + 1).padStart(3, '0');
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
            title: 'รายละเอียดการขอฟื้นฟูบ้าน',
            html: `
                <div class='text-left space-y-2 mt-4 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100'>
                    <p><span class='font-bold text-gray-900'>ID:</span> ${item.id}</p>
                    <p><span class='font-bold text-gray-900'>ผู้แจ้ง:</span> ${item.name}</p>
                    <p><span class='font-bold text-gray-900'>เบอร์ติดต่อ:</span> ${item.phone}</p>
                    <p><span class='font-bold text-gray-900'>รายละเอียดความเสียหาย:</span> ${item.details}</p>
                    <p><span class='font-bold text-gray-900'>จังหวัด:</span> ${item.province}</p>
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
        <h2 class="font-bold text-gray-800">รายการขอความช่วยเหลือฟื้นฟูบ้าน</h2>
        <div class="flex gap-2">
            <button @click="showModal = true; modalMode = 'add'; currentItem = { id: null, name: '', phone: '', details: '', province: '', status: 'รอตรวจสอบ' }" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700 flex items-center gap-2 transition-colors">
                <x-heroicon-o-plus class="w-4 h-4" /> เพิ่มข้อมูลใหม่
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ชื่อผู้แจ้ง</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">รายละเอียด</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">จังหวัด</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">สถานะ</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.id"></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800" x-text="item.name"></div>
                            <div class="text-xs text-gray-500" x-text="item.phone"></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.details"></td>
                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.province"></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-bold"
                                  :class="{
                                      'bg-yellow-100 text-yellow-700': item.status === 'รอตรวจสอบ',
                                      'bg-blue-100 text-blue-700': item.status === 'กำลังซ่อมแซม',
                                      'bg-emerald-100 text-emerald-700': item.status === 'เสร็จสิ้น' || item.status === 'ซ่อมแซมเสร็จสิ้น'
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
                                <x-heroicon-o-document-text class="h-6 w-6 text-indigo-600" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title" x-text="modalMode === 'add' ? 'เพิ่มข้อมูลคำขอ' : 'แก้ไขข้อมูลคำขอ'"></h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">ชื่อผู้แจ้ง</label>
                                        <input type="text" x-model="currentItem.name" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">เบอร์ติดต่อ</label>
                                        <input type="text" x-model="currentItem.phone" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">รายละเอียดความเสียหาย</label>
                                        <textarea x-model="currentItem.details" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" rows="3" required></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">จังหวัด</label>
                                        <select x-model="currentItem.province" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                            <option value="">-- เลือกจังหวัด --</option>
                                            <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
                                            <option value="กระบี่">กระบี่</option>
                                            <option value="กาญจนบุรี">กาญจนบุรี</option>
                                            <option value="กาฬสินธุ์">กาฬสินธุ์</option>
                                            <option value="กำแพงเพชร">กำแพงเพชร</option>
                                            <option value="ขอนแก่น">ขอนแก่น</option>
                                            <option value="จันทบุรี">จันทบุรี</option>
                                            <option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
                                            <option value="ชลบุรี">ชลบุรี</option>
                                            <option value="ชัยนาท">ชัยนาท</option>
                                            <option value="ชัยภูมิ">ชัยภูมิ</option>
                                            <option value="ชุมพร">ชุมพร</option>
                                            <option value="เชียงราย">เชียงราย</option>
                                            <option value="เชียงใหม่">เชียงใหม่</option>
                                            <option value="ตรัง">ตรัง</option>
                                            <option value="ตราด">ตราด</option>
                                            <option value="ตาก">ตาก</option>
                                            <option value="นครนายก">นครนายก</option>
                                            <option value="นครปฐม">นครปฐม</option>
                                            <option value="นครพนม">นครพนม</option>
                                            <option value="นครราชสีมา">นครราชสีมา</option>
                                            <option value="นครศรีธรรมราช">นครศรีธรรมราช</option>
                                            <option value="นครสวรรค์">นครสวรรค์</option>
                                            <option value="นนทบุรี">นนทบุรี</option>
                                            <option value="นราธิวาส">นราธิวาส</option>
                                            <option value="น่าน">น่าน</option>
                                            <option value="บึงกาฬ">บึงกาฬ</option>
                                            <option value="บุรีรัมย์">บุรีรัมย์</option>
                                            <option value="ปทุมธานี">ปทุมธานี</option>
                                            <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
                                            <option value="ปราจีนบุรี">ปราจีนบุรี</option>
                                            <option value="ปัตตานี">ปัตตานี</option>
                                            <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
                                            <option value="พะเยา">พะเยา</option>
                                            <option value="พังงา">พังงา</option>
                                            <option value="พัทลุง">พัทลุง</option>
                                            <option value="พิจิตร">พิจิตร</option>
                                            <option value="พิษณุโลก">พิษณุโลก</option>
                                            <option value="เพชรบุรี">เพชรบุรี</option>
                                            <option value="เพชรบูรณ์">เพชรบูรณ์</option>
                                            <option value="แพร่">แพร่</option>
                                            <option value="ภูเก็ต">ภูเก็ต</option>
                                            <option value="มหาสารคาม">มหาสารคาม</option>
                                            <option value="มุกดาหาร">มุกดาหาร</option>
                                            <option value="แม่ฮ่องสอน">แม่ฮ่องสอน</option>
                                            <option value="ยโสธร">ยโสธร</option>
                                            <option value="ยะลา">ยะลา</option>
                                            <option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
                                            <option value="ระนอง">ระนอง</option>
                                            <option value="ระยอง">ระยอง</option>
                                            <option value="ราชบุรี">ราชบุรี</option>
                                            <option value="ลพบุรี">ลพบุรี</option>
                                            <option value="ลำปาง">ลำปาง</option>
                                            <option value="ลำพูน">ลำพูน</option>
                                            <option value="เลย">เลย</option>
                                            <option value="ศรีสะเกษ">ศรีสะเกษ</option>
                                            <option value="สกลนคร">สกลนคร</option>
                                            <option value="สงขลา">สงขลา</option>
                                            <option value="สตูล">สตูล</option>
                                            <option value="สมุทรปราการ">สมุทรปราการ</option>
                                            <option value="สมุทรสงคราม">สมุทรสงคราม</option>
                                            <option value="สมุทรสาคร">สมุทรสาคร</option>
                                            <option value="สระแก้ว">สระแก้ว</option>
                                            <option value="สระบุรี">สระบุรี</option>
                                            <option value="สิงห์บุรี">สิงห์บุรี</option>
                                            <option value="สุโขทัย">สุโขทัย</option>
                                            <option value="สุพรรณบุรี">สุพรรณบุรี</option>
                                            <option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
                                            <option value="สุรินทร์">สุรินทร์</option>
                                            <option value="หนองคาย">หนองคาย</option>
                                            <option value="หนองบัวลำภู">หนองบัวลำภู</option>
                                            <option value="อ่างทอง">อ่างทอง</option>
                                            <option value="อำนาจเจริญ">อำนาจเจริญ</option>
                                            <option value="อุดรธานี">อุดรธานี</option>
                                            <option value="อุตรดิตถ์">อุตรดิตถ์</option>
                                            <option value="อุทัยธานี">อุทัยธานี</option>
                                            <option value="อุบลราชธานี">อุบลราชธานี</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">สถานะ</label>
                                        <select x-model="currentItem.status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option>รอตรวจสอบ</option>
                                            <option>กำลังซ่อมแซม</option>
                                            <option>เสร็จสิ้น</option>
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
