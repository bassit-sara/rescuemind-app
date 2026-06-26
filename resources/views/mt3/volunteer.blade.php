@extends('layouts.app')
@section('title', 'ระบบอาสาสมัครฟื้นฟู')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mt3') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 font-medium hover:text-emerald-600 hover:bg-emerald-50 shadow-sm transition-colors shrink-0">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-o-users class="w-7 h-7 text-emerald-500" />
                        ระบบลงทะเบียนอาสาสมัครฟื้นฟู
                    </h2>
                    <p class="text-gray-600 text-[15px] font-medium mt-1">ร่วมเป็นส่วนหนึ่งในการฟื้นฟูชุมชนและช่วยเหลือผู้ประสบภัย</p>
                </div>
            </div>
            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
            <div class="flex items-center gap-2 shrink-0">
                <button class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-bold shadow-sm hover:bg-emerald-200 flex items-center gap-2 transition-colors">
                    <x-heroicon-o-pencil-square class="w-4 h-4" /> จัดการรายชื่ออาสาสมัคร
                </button>
            </div>
            @endif
        </div>


        <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
            <div class="p-0 sm:p-8">
                <!-- Hero Banner Image (Mock) -->
                <div class="h-48 bg-emerald-600 rounded-2xl mb-8 relative overflow-hidden hidden sm:block">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-white p-6 text-center">
                        <x-heroicon-s-heart class="w-12 h-12 mb-2 text-emerald-200" />
                        <h3 class="text-2xl font-black tracking-wide text-white">พลังอาสา ฟื้นฟูชุมชน</h3>
                        <p class="text-emerald-100 mt-1">ทุกทักษะของคุณ มีค่าสำหรับผู้ประสบภัย</p>
                    </div>
                </div>

                <div x-data="volunteerForm()">
                    <form @submit.prevent="submitForm" class="p-6 sm:p-0">
                        @csrf
                    
                    <div class="space-y-6">
                        <!-- Personal Info -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2">1. ข้อมูลส่วนตัว</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล</label>
                                    <input type="text" x-model="formData.name" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ติดต่อ</label>
                                    <input type="text" x-model="formData.phone" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปแบบอาสาสมัคร</label>
                                    <select x-model="formData.volunteer_type" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow">
                                        <option value="อาสาสมัครรายบุคคล">อาสาสมัครรายบุคคล</option>
                                        <option value="มาเป็นทีม/กลุ่ม/องค์กร">มาเป็นทีม/กลุ่ม/องค์กร</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Skills -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">2. ทักษะและความเชี่ยวชาญ (เลือกได้มากกว่า 1)</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" x-model="formData.skills" value="แรงงาน/ทำความสะอาด" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">แรงงาน/ทำความสะอาด</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" x-model="formData.skills" value="ช่างไฟฟ้า" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">ช่างไฟฟ้า</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" x-model="formData.skills" value="ช่างประปา" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">ช่างประปา</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" x-model="formData.skills" value="ช่างก่อสร้าง/ไม้" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">ช่างก่อสร้าง/ไม้</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" x-model="formData.skills" value="แพทย์/พยาบาล/กู้ชีพ" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">แพทย์/พยาบาล/กู้ชีพ</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors group">
                                    <input type="checkbox" x-model="formData.skills" value="นักจิตวิทยา/ที่ปรึกษา" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-3 font-black text-gray-800 group-hover:text-emerald-700">นักจิตวิทยา/ที่ปรึกษา</span>
                                </label>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div>
                            <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-2 mt-8">3. วันและพื้นที่ที่สะดวก</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">พื้นที่ที่สะดวกลงปฏิบัติงาน (จังหวัด/อำเภอ)</label>
                                    <input type="text" x-model="formData.province" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" placeholder="เช่น ยะลา">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ระยะเวลาที่สามารถช่วยได้ (วัน)</label>
                                    <input type="number" x-model="formData.duration_days" min="1" required class="w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-shadow" placeholder="เช่น 3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('mt3') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-bold transition-colors">ยกเลิก</a>
                        <button type="submit" class="w-full sm:w-auto text-center px-8 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-bold shadow-md shadow-emerald-200 transition-all transform hover:-translate-y-0.5"
                                :disabled="isSubmitting" :class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                            <span x-show="!isSubmitting">ลงทะเบียนอาสาสมัคร</span>
                            <span x-show="isSubmitting" style="display: none;">กำลังลงทะเบียน...</span>
                        </button>
                    </div>
                </form>

                {{-- Success Modal --}}
                <div x-show="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm"></div>
                        <div class="relative w-full max-w-sm p-6 bg-white rounded-2xl shadow-xl text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-100 mb-4">
                                <span class="text-3xl"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0 text-emerald-600" /></span>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 mb-2">ลงทะเบียนสำเร็จ!</h3>
                            <p class="text-[15px] text-gray-600 font-medium mb-6">ขอบคุณที่ร่วมเป็นส่วนหนึ่งในการฟื้นฟูชุมชน ทีมงานจะติดต่อกลับและประสานงานโดยเร็วที่สุด</p>
                            <a href="{{ route('mt3') }}" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none">
                                กลับสู่หน้าหลัก
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function volunteerForm() {
    return {
        isSubmitting: false,
        showSuccessModal: false,
        formData: {
            name: '{{ auth()->user()->name ?? '' }}',
            phone: '',
            volunteer_type: 'อาสาสมัครรายบุคคล',
            duration_days: 1,
            province: '',
            skills: []
        },
        
        async submitForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
            
            try {
                let response = await fetch('{{ route('mt3.volunteer.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });
                
                let result = await response.json();
                
                if (response.ok && result.success) {
                    this.showSuccessModal = true;
                } else {
                    alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                }
            } catch (error) {
                console.error(error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
@endpush



