@extends(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer', 'volunteer']) ? 'layouts.admin' : 'layouts.app')

@section('title', 'โปรไฟล์ของฉัน')
@section('page-title')
    ตั้งค่าบัญชีผู้ใช้
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header Banner with Avatar Upload --}}
    <div class="bg-gradient-to-br from-gray-800 to-black rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-red-500/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">

            {{-- Avatar with upload trigger --}}
            <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" id="avatar-quick-form">
                @csrf
                <input type="file" id="avatar-quick-input" name="avatar" accept="image/*" class="sr-only"
                       onchange="document.getElementById('avatar-quick-form').submit()">

                <label for="avatar-quick-input" class="relative cursor-pointer group block" title="คลิกเพื่อเปลี่ยนรูปโปรไฟล์">
                    {{-- Avatar circle --}}
                    @if(auth()->user()->avatar)
                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white">
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile Photo" class="w-full h-full object-cover group-hover:opacity-70 transition-opacity">
                    </div>
                    @else
                    <div class="w-24 h-24 bg-red-600 rounded-full flex items-center justify-center text-4xl font-bold border-4 border-white shadow-xl group-hover:bg-red-700 transition-colors">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    @endif

                    {{-- Overlay icon --}}
                    <div class="absolute inset-0 flex items-center justify-center rounded-full bg-black/0 group-hover:bg-black/40 transition-all">
                        <x-heroicon-o-camera class="w-7 h-7 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow" />
                    </div>

                    {{-- Badge --}}
                    <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-red-500 rounded-full border-2 border-white flex items-center justify-center shadow">
                        <x-heroicon-s-pencil class="w-3.5 h-3.5 text-white" />
                    </div>
                </label>
            </form>

            {{-- User info --}}
            <div class="text-center sm:text-left">
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ auth()->user()->name }}</h1>
                <p class="text-gray-300 text-sm flex items-center gap-2 justify-center sm:justify-start">
                    <x-heroicon-o-envelope class="w-4 h-4 text-gray-400" />
                    {{ auth()->user()->email }}
                </p>
                @if(auth()->user()->phone)
                <p class="text-gray-400 text-xs flex items-center gap-2 mt-1 justify-center sm:justify-start">
                    <x-heroicon-o-phone class="w-4 h-4 text-gray-500" />
                    {{ auth()->user()->phone }}
                </p>
                @endif
                <div class="mt-3 flex flex-wrap gap-2 justify-center sm:justify-start">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold backdrop-blur-sm border border-white/10">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
                    </span>
                    @if(auth()->user()->email_verified_at)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold border border-emerald-500/20">
                        <x-heroicon-s-check-badge class="w-3 h-3" /> ยืนยันอีเมลแล้ว
                    </span>
                    @endif
                </div>
                <p class="text-gray-500 text-xs mt-3">คลิกที่รูปเพื่อเปลี่ยนรูปโปรไฟล์</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Profile Info + Password --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Profile Information --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center">
                        <x-heroicon-o-user class="w-5 h-5 text-blue-600" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">ข้อมูลส่วนตัว</h2>
                        <p class="text-xs text-gray-500">อัปเดตชื่อและที่อยู่อีเมลของคุณ</p>
                    </div>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Password --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <x-heroicon-o-lock-closed class="w-5 h-5 text-amber-600" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">เปลี่ยนรหัสผ่าน</h2>
                        <p class="text-xs text-gray-500">ใช้รหัสผ่านที่คาดเดาได้ยากอย่างน้อย 8 ตัวอักษร</p>
                    </div>
                </div>
                @include('profile.partials.update-password-form')
            </div>

        </div>

        {{-- RIGHT: Notifications + Danger Zone --}}
        <div class="space-y-6">

            {{-- Account Overview --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center">
                        <x-heroicon-o-information-circle class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h2 class="text-base font-bold text-gray-900">ข้อมูลบัญชี</h2>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500">สมาชิกตั้งแต่</span>
                        <span class="font-semibold text-gray-800">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500">อัปเดตล่าสุด</span>
                        <span class="font-semibold text-gray-800">{{ auth()->user()->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500">สถานะอีเมล</span>
                        @if(auth()->user()->email_verified_at)
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">ยืนยันแล้ว</span>
                        @else
                            <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">ยังไม่ยืนยัน</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-500">Role</span>
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full">{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</span>
                    </div>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-green-50 rounded-2xl flex items-center justify-center">
                        <x-heroicon-o-bell class="w-5 h-5 text-green-600" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">การแจ้งเตือน</h2>
                        <p class="text-xs text-gray-500">ช่องทางรับข่าวสารฉุกเฉิน</p>
                    </div>
                </div>
                <div class="space-y-3">
                    {{-- LINE --}}
                    <div class="flex items-center justify-between p-3 border border-green-100 bg-green-50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-green-500 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm shrink-0">L</div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">LINE Notify</div>
                                <div class="text-xs text-gray-500">แจ้งเตือนผ่าน LINE ทันที</div>
                            </div>
                        </div>
                        @if(auth()->user()->line_notify_token)
                            <form method="POST" action="{{ route('line-notify.disconnect') }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition-colors shadow-sm whitespace-nowrap shrink-0">ยกเลิก</button>
                            </form>
                        @else
                            <a href="{{ route('line-notify.connect') }}" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm whitespace-nowrap shrink-0">เชื่อมต่อ</a>
                        @endif
                    </div>

                    {{-- SMS --}}
                    <div class="flex items-center justify-between p-3 border border-gray-100 bg-gray-50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 shrink-0">
                                <x-heroicon-o-device-phone-mobile class="w-4 h-4" />
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">SMS Alerts</div>
                                <div class="text-xs text-gray-500">{{ auth()->user()->phone ?? 'ยังไม่ระบุเบอร์' }}</div>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-3">*ระบบการแจ้งเตือนจำลองสำหรับ Hackathon</p>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-red-50 rounded-3xl shadow-sm border border-red-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-red-100 rounded-2xl flex items-center justify-center">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-600" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-red-800">Danger Zone</h2>
                        <p class="text-xs text-red-500">การกระทำที่ไม่สามารถย้อนกลับได้</p>
                    </div>
                </div>
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</div>
@endsection
