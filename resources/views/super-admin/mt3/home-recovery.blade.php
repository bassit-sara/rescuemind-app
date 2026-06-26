@extends('layouts.admin')
@section('title', 'จัดการข้อมูลฟื้นฟูบ้าน (MT3)')
@section('page-title')
    <x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /> จัดการข้อมูลฟื้นฟูบ้าน
@endsection
@section('content')

<script>
    window.mt3HomeRecoveries = @json($recoveries);
</script>

<div x-data="{ 
    showModal: false, 
    modalMode: 'add', 
    currentItem: { id: null, name: '', phone: '', details: '', province: '', status: 'รอตรวจสอบ' },
    items: [],
    init() {
        // Load data from PHP
        let serverData = window.mt3HomeRecoveries;
        this.items = serverData.map(r => ({
            raw_id: r.id,
            id: '#HR-' + String(r.id).padStart(3, '0'),
            name: r.user ? r.user.name : 'Unknown',
            phone: r.phone || (r.user ? r.user.phone : '-'),
            details: r.additional_details || '-',
            province: r.zip_code || '-',
            status: r.status === 'pending' ? 'รับคำขอความช่วยเหลือ' : (r.status === 'matching' ? 'จับคู่ทีมอาสาสมัคร' : (r.status === 'in_progress' ? 'ทีมอาสาสมัครกำลังลงพื้นที่' : 'การฟื้นฟูเสร็จสมบูรณ์')),
            lat: r.lat,
            lng: r.lng
        }));

        // Listen for new requests via WebSocket
        if (typeof window.Echo !== 'undefined') {
            window.Echo.channel('mt3.home-recovery')
                .listen('HomeRecoveryRequested', (e) => {
                    let r = e.recovery;
                    this.items.unshift({
                        raw_id: r.id,
                        id: '#HR-' + String(r.id).padStart(3, '0'),
                        name: r.user ? r.user.name : 'Unknown',
                        phone: r.phone || (r.user ? r.user.phone : '-'),
                        details: r.additional_details || '-',
                        province: r.zip_code || '-',
                        status: r.status === 'pending' ? 'รับคำขอความช่วยเหลือ' : (r.status === 'matching' ? 'จับคู่ทีมอาสาสมัคร' : (r.status === 'in_progress' ? 'ทีมอาสาสมัครกำลังลงพื้นที่' : 'การฟื้นฟูเสร็จสมบูรณ์')),
                        lat: r.lat,
                        lng: r.lng
                    });
                    
                    // Show a toast notification
                    if(typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'มีคำขอฟื้นฟูบ้านใหม่เข้าสู่ระบบ',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
        }
    },
    async saveItem() {
        if(this.modalMode === 'add') {
            this.currentItem.id = '#HR-' + String(this.items.length + 1).padStart(3, '0');
            this.items.push({...this.currentItem});
            this.showModal = false;
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'เพิ่มข้อมูลสำเร็จ!', confirmButtonColor: '#4f46e5' });
        } else {
            // For editing, send the request to backend
            try {
                let response = await fetch(`/super-admin/mt3/home-recovery/${this.currentItem.raw_id}`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.currentItem)
                });
                if(response.ok) {
                    const index = this.items.findIndex(i => i.id === this.currentItem.id);
                    if(index > -1) this.items[index] = {...this.currentItem};
                    this.showModal = false;
                    Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกการแก้ไขสำเร็จและอัปเดตฐานข้อมูลแล้ว!', confirmButtonColor: '#4f46e5' });
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
                        let response = await fetch(`/super-admin/mt3/home-recovery/${item.raw_id}`, {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        if (response.ok) {
                            this.items = this.items.filter(i => i.id !== id);
                            Swal.fire({ icon: 'success', title: 'ลบข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
                        } else {
                            Swal.fire({ icon: 'error', title: 'ลบไม่สำเร็จ' });
                        }
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'ลบไม่สำเร็จ' });
                    }
                } else {
                    this.items = this.items.filter(i => i.id !== id);
                    Swal.fire({ icon: 'success', title: 'ลบข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
                }
            }
        });
    },
    viewItem(item) {
        Swal.fire({
            title: 'รายละเอียดการขอฟื้นฟูบ้าน',
            width: '850px',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-gray-100',
                title: 'text-2xl font-black text-gray-800 pt-4',
            },
            html: `
                <div class='text-left mt-4 grid grid-cols-1 md:grid-cols-2 gap-5'>
                    <!-- Left Column: Details -->
                    <div class='space-y-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm'>
                        <div class='flex items-center justify-between border-b border-gray-100 pb-3'>
                            <h4 class='font-bold text-indigo-700 flex items-center gap-2'>
                                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg> ข้อมูลผู้แจ้ง
                            </h4>
                            <span class='px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-black tracking-wider border border-indigo-100'>${item.id}</span>
                        </div>
                        
                        <div class='grid grid-cols-1 gap-4 text-sm'>
                            <div>
                                <span class='block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1'>ชื่อผู้แจ้ง</span>
                                <span class='font-bold text-gray-800 text-base'>${item.name}</span>
                            </div>
                            <div>
                                <span class='block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1'>เบอร์ติดต่อ</span>
                                <a href='tel:${item.phone}' class='font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-2 rounded-xl inline-block border border-blue-100 transition-colors'>📞 ${item.phone}</a>
                            </div>
                            <div>
                                <span class='block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1'>รายละเอียดความเสียหาย</span>
                                <p class='text-gray-700 bg-gray-50 p-3 rounded-xl border border-gray-100 text-[13px] leading-relaxed'>${item.details}</p>
                            </div>
                            <div class='grid grid-cols-2 gap-3'>
                                <div>
                                    <span class='block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1'>จังหวัด</span>
                                    <span class='font-medium text-gray-800 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 block'>${item.province}</span>
                                </div>
                                <div>
                                    <span class='block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1'>สถานะ</span>
                                    <span class='px-3 py-1.5 rounded-lg text-xs font-bold inline-block border bg-white shadow-sm w-full text-center ${item.status === 'รอตรวจสอบ' ? 'text-yellow-600 border-yellow-200 bg-yellow-50' : (item.status === 'เสร็จสิ้น' ? 'text-emerald-600 border-emerald-200 bg-emerald-50' : 'text-blue-600 border-blue-200 bg-blue-50')}'>${item.status}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Map -->
                    <div class='bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col h-full'>
                        <h4 class='font-bold text-emerald-700 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4'>
                            <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'></path><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 11a3 3 0 11-6 0 3 3 0 016 0z'></path></svg> ตำแหน่งบนแผนที่
                        </h4>
                        
                        <div class='flex-grow min-h-[200px] relative rounded-xl overflow-hidden border border-gray-200 bg-gray-50 group'>
                            ${item.lat && item.lng 
                                ? `<iframe width='100%' height='100%' frameborder='0' style='border:0; position:absolute; top:0; left:0; width:100%; height:100%;' src='https://maps.google.com/maps?q=${item.lat},${item.lng}&hl=th&z=16&output=embed' allowfullscreen></iframe>`
                                : `<div class='absolute inset-0 flex flex-col items-center justify-center text-gray-400'><svg class='w-10 h-10 mb-2 opacity-30' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'></path></svg><span class='font-bold text-sm'>ไม่ได้ระบุพิกัด GPS</span></div>`
                            }
                        </div>
                        
                        ${item.lat && item.lng ? `
                        <div class='mt-4 flex items-center justify-between bg-gray-50 p-2.5 rounded-xl border border-gray-100'>
                            <span class='text-[11px] font-mono text-gray-500 font-medium px-2'>${item.lat},<br>${item.lng}</span>
                            <a href='https://www.google.com/maps/search/?api=1&query=${item.lat},${item.lng}' target='_blank' class='text-[11px] font-bold text-emerald-700 hover:text-white flex items-center gap-1.5 bg-emerald-100 hover:bg-emerald-600 px-3 py-2 rounded-lg transition-colors border border-emerald-200 hover:border-emerald-600 shrink-0'>
                                เปิดในแอป Maps <svg class='w-3 h-3' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14'></path></svg>
                            </a>
                        </div>
                        ` : ''}
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
        <h2 class="font-bold text-gray-800">รายการขอความช่วยเหลือฟื้นฟูบ้าน</h2>
        <div class="flex gap-2">
            <button @click="showModal = true; modalMode = 'add'; currentItem = { id: null, name: '', phone: '', details: '', province: '', status: 'รับคำขอความช่วยเหลือ' }" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700 flex items-center gap-2 transition-colors">
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
                                      'bg-yellow-100 text-yellow-700': item.status === 'รับคำขอความช่วยเหลือ',
                                      'bg-orange-100 text-orange-700': item.status === 'จับคู่ทีมอาสาสมัคร',
                                      'bg-blue-100 text-blue-700': item.status === 'ทีมอาสาสมัครกำลังลงพื้นที่',
                                      'bg-emerald-100 text-emerald-700': item.status === 'การฟื้นฟูเสร็จสมบูรณ์'
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
            <div x-show="showModal" x-transition.scale class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                <form @submit.prevent="saveItem()">
                    <div class="bg-white px-5 pt-6 pb-4 sm:p-8 sm:pb-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-14 w-14 rounded-2xl bg-indigo-50 border border-indigo-100 sm:mx-0 sm:h-12 sm:w-12 shadow-sm">
                                <x-heroicon-o-document-text class="h-6 w-6 text-indigo-600" />
                            </div>
                            <div class="mt-4 text-center sm:mt-0 sm:ml-5 sm:text-left w-full">
                                <h3 class="text-xl font-black text-gray-900" id="modal-title" x-text="modalMode === 'add' ? 'เพิ่มข้อมูลคำขอใหม่' : 'แก้ไขข้อมูลคำขอ'"></h3>
                                <p class="text-sm text-gray-500 mt-1 font-medium" x-text="modalMode === 'add' ? 'กรอกรายละเอียดเพื่อเพิ่มคำขอเข้าระบบ' : 'อัปเดตข้อมูลรายละเอียดหรือเปลี่ยนสถานะ'"></p>
                                
                                <div class="mt-6 space-y-5">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">ชื่อผู้แจ้ง</label>
                                            <input type="text" x-model="currentItem.name" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-2.5" required placeholder="ระบุชื่อ-นามสกุล">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">เบอร์ติดต่อ</label>
                                            <input type="text" x-model="currentItem.phone" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-2.5" required placeholder="08X-XXX-XXXX">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">รายละเอียดความเสียหาย</label>
                                        <textarea x-model="currentItem.details" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-3" rows="3" required placeholder="อธิบายความเสียหายเบื้องต้น..."></textarea>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">จังหวัด</label>
                                            <select x-model="currentItem.province" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all px-4 py-2.5" required>
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
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">สถานะการดำเนินการ</label>
                                            <select x-model="currentItem.status" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm font-bold transition-all px-4 py-2.5"
                                                :class="{
                                                    'text-yellow-700': currentItem.status === 'รับคำขอความช่วยเหลือ',
                                                    'text-orange-700': currentItem.status === 'จับคู่ทีมอาสาสมัคร',
                                                    'text-blue-700': currentItem.status === 'ทีมอาสาสมัครกำลังลงพื้นที่',
                                                    'text-emerald-700': currentItem.status === 'การฟื้นฟูเสร็จสมบูรณ์'
                                                }">
                                                <option class="text-gray-900 font-medium">รับคำขอความช่วยเหลือ</option>
                                                <option class="text-gray-900 font-medium">จับคู่ทีมอาสาสมัคร</option>
                                                <option class="text-gray-900 font-medium">ทีมอาสาสมัครกำลังลงพื้นที่</option>
                                                <option class="text-gray-900 font-medium">การฟื้นฟูเสร็จสมบูรณ์</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-3xl border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto transition-all">
                            <x-heroicon-s-check-circle class="w-5 h-5" /> บันทึกข้อมูล
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center items-center gap-2 rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto transition-all">
                            ยกเลิก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
