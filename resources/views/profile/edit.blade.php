@extends(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer', 'volunteer']) ? 'layouts.admin' : 'layouts.app')

@section('title', 'โปรไฟล์ของฉัน')
@section('page-title')
    ตั้งค่าบัญชีผู้ใช้
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-gray-800 to-black rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-red-500/20 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            @if(auth()->user()->avatar)
            <div class="w-20 h-20 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white">
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile Photo" class="w-full h-full object-cover">
            </div>
            @else
            <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center text-4xl font-bold border-4 border-white shadow-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            @endif
            <div>
                <h1 class="text-3xl font-bold mb-1">{{ auth()->user()->name }}</h1>
                <p class="text-gray-300">{{ auth()->user()->email }}</p>
                <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold backdrop-blur-sm border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                    {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <h2 class="text-lg font-bold text-gray-900 mb-1">การแจ้งเตือน (Notifications)</h2>
            <p class="text-sm text-gray-600 mb-6">ตั้งค่าการรับการแจ้งเตือนภัยพิบัติฉุกเฉินและสถานะการขอความช่วยเหลือ</p>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-green-200 bg-green-50 rounded-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-sm">
                            L
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">LINE Notify</h3>
                            <p class="text-xs text-gray-600">รับข้อความแจ้งเตือนผ่านแอปพลิเคชัน LINE ทันที</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                        เชื่อมต่อ (Connect)
                    </button>
                </div>
                
                <div class="flex items-center justify-between p-4 border border-gray-200 bg-gray-50 rounded-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-2xl font-bold shadow-sm">
                            <x-heroicon-o-device-phone-mobile class="w-5 h-5 inline-block shrink-0" />
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">SMS Alerts</h3>
                            <p class="text-xs text-gray-600">รับแจ้งเตือนผ่าน SMS เบอร์โทรศัพท์ของคุณ (เบอร์: {{ auth()->user()->phone ?? 'ยังไม่ระบุ' }})</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4">*หมายเหตุ: ระบบการแจ้งเตือนถูกจำลองขึ้นเพื่อแสดง Workflow ในงาน Hackathon</p>
        </div>

        <div class="bg-red-50 rounded-3xl shadow-sm border border-red-100 p-6 sm:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
