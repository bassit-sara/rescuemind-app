<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>สมัครสมาชิก - {{ config('app.name', 'RescueMind') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-register-image {
            background-image: url('https://images.unsplash.com/photo-1599059813005-11265ba4b4ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .input-floating:focus-within label,
        .input-floating input:not(:placeholder-shown)~label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #dc2626;
            /* text-red-600 */
            background-color: white;
            padding: 0 0.25rem;
            border-radius: 0.25rem;
        }

        /* Custom Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        /* Animated Background Blobs */
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite alternate;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-900 min-h-screen flex selection:bg-red-500 selection:text-white"
    x-data="{ showPolicy: true }">

    <!-- Left Side: Image & Branding (Desktop Only) -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-register-image items-center justify-center overflow-hidden">
        <!-- Overlays -->
        <div class="absolute inset-0 bg-gradient-to-br from-red-950/90 via-gray-900/80 to-black/95 mix-blend-multiply">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>

        <!-- Content -->
        <div class="relative z-10 p-16 text-center flex flex-col items-center max-w-2xl animate-fade-in-up">
            <div
                class="w-40 h-40 bg-white rounded-full flex items-center justify-center p-5 mb-8 shadow-2xl transform hover:scale-105 transition-transform duration-500 ring-8 ring-white/20">
                <x-application-logo class="w-full h-full object-contain drop-shadow-md" />
            </div>

            <h1 class="text-5xl font-black mb-6 leading-tight tracking-tight text-white">
                RescueMind<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-300">Empowering
                    Survival</span>
            </h1>

            <p class="text-lg text-gray-300 mb-10 leading-relaxed font-light max-w-lg">
                มาร่วมเป็นส่วนหนึ่งกับเราในการสร้างสังคมที่ปลอดภัย
                พร้อมเป็นหูเป็นตาและส่งต่อความช่วยเหลือให้เพื่อนมนุษย์ในยามเกิดเหตุ
            </p>

            <div
                class="flex items-center gap-4 text-sm font-medium text-gray-400 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 w-fit">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-gray-900" src="https://i.pravatar.cc/100?img=33"
                        alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-gray-900" src="https://i.pravatar.cc/100?img=47"
                        alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-gray-900" src="https://i.pravatar.cc/100?img=12"
                        alt="User">
                    <div
                        class="w-10 h-10 rounded-full border-2 border-gray-900 bg-gradient-to-tr from-red-600 to-orange-500 flex items-center justify-center text-xs text-white font-bold shadow-inner">
                        +5k</div>
                </div>
                <span class="pl-2">ผู้ใช้งานและอาสาสมัครทั่วประเทศ</span>
            </div>
        </div>
    </div>

    <!-- Right Side: Register Form -->
    <div
        class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-8 lg:p-12 relative overflow-hidden bg-gray-50 h-screen overflow-y-auto">
        <!-- Abstract Background blobs -->
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob">
        </div>
        <div
            class="absolute bottom-0 left-0 w-80 h-80 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-20 -right-20 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000">
        </div>

        <div
            class="w-full max-w-lg relative z-10 glass-panel p-6 sm:p-8 rounded-3xl shadow-2xl animate-fade-in-up delay-100 my-auto">

            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center justify-center mb-6">
                <div
                    class="w-20 h-20 bg-white rounded-full shadow-lg flex items-center justify-center p-3 ring-4 ring-white border border-gray-100">
                    <x-application-logo class="w-full h-full object-contain drop-shadow-sm" />
                </div>
            </div>

            <div class="mb-6 text-center lg:text-left">
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-1 tracking-tight">สร้างบัญชีเจ้าหน้าที่ <x-heroicon-o-sparkles class="w-5 h-5 inline-block shrink-0" />
                </h2>
                <p class="text-gray-500 font-medium text-sm">ลงทะเบียนสำหรับเจ้าหน้าที่กู้ภัยและนักจิตวิทยา</p>
            </div>

            <form method="POST" action="{{ route('register.officer.store') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div class="relative input-floating">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        autocomplete="name"
                        class="block w-full px-4 py-3 bg-white/80 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm"
                        placeholder=" " />
                    <label for="name"
                        class="absolute left-4 top-3 text-gray-500 text-sm transition-all peer-focus:-translate-y-5 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left">ชื่อ-นามสกุล
                        (Name)</label>
                    <x-input-error :messages="$errors->get('name')"
                        class="mt-1 text-xs text-red-600 font-medium ml-1" />
                </div>

                <!-- Email Address -->
                <div class="relative input-floating">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="username"
                        class="block w-full px-4 py-3 bg-white/80 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm"
                        placeholder=" " />
                    <label for="email"
                        class="absolute left-4 top-3 text-gray-500 text-sm transition-all peer-focus:-translate-y-5 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left">อีเมล
                        (Email)</label>
                    <x-input-error :messages="$errors->get('email')"
                        class="mt-1 text-xs text-red-600 font-medium ml-1" />
                </div>

                <!-- Password & Confirm Password (Grid) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="relative input-floating">
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="block w-full px-4 py-3 bg-white/80 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm"
                            placeholder=" " />
                        <label for="password"
                            class="absolute left-4 top-3 text-gray-500 text-sm transition-all peer-focus:-translate-y-5 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left">รหัสผ่าน
                            (Password)</label>
                        <x-input-error :messages="$errors->get('password')"
                            class="mt-1 text-xs text-red-600 font-medium ml-1" />
                    </div>

                    <div class="relative input-floating">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            autocomplete="new-password"
                            class="block w-full px-4 py-3 bg-white/80 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm"
                            placeholder=" " />
                        <label for="password_confirmation"
                            class="absolute left-4 top-3 text-gray-500 text-sm transition-all peer-focus:-translate-y-5 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left">ยืนยันรหัสผ่าน</label>
                        <x-input-error :messages="$errors->get('password_confirmation')"
                            class="mt-1 text-xs text-red-600 font-medium ml-1" />
                    </div>
                </div>

                <!-- Role & Province (Grid) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="relative input-floating">
                        <select id="role" name="role" required
                            class="block w-full px-4 py-3 bg-white/80 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer shadow-sm">
                            <option value="" disabled selected>เลือกตำแหน่ง</option>
                            <option value="officer" {{ old('role') == 'officer' ? 'selected' : '' }}>เจ้าหน้าที่กู้ภัย
                            </option>
                            <option value="mental_officer" {{ old('role') == 'mental_officer' ? 'selected' : '' }}>นักจิตวิทยา
                            </option>
                        </select>
                        <label for="role"
                            class="absolute left-4 top-3 text-gray-500 text-sm transition-all peer-focus:-translate-y-5 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left -translate-y-5 text-xs bg-white px-1">ตำแหน่ง</label>
                        <x-input-error :messages="$errors->get('role')"
                            class="mt-1 text-xs text-red-600 font-medium ml-1" />
                    </div>

                    <div class="relative input-floating">
                        @php
                            $provincesList = ['ยะลา', 'ปัตตานี', 'นราธิวาส', 'สงขลา', 'สตูล'];
                        @endphp
                        <select id="province" name="province" required
                            class="block w-full px-4 py-3 bg-white/80 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer shadow-sm">
                            <option value="" disabled selected>เลือกจังหวัด</option>
                            @foreach($provincesList as $p)
                                <option value="{{ $p }}" {{ old('province') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        <label for="province"
                            class="absolute left-4 top-3 text-gray-500 text-sm transition-all peer-focus:-translate-y-5 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left -translate-y-5 text-xs bg-white px-1">จังหวัด</label>
                        <x-input-error :messages="$errors->get('province')"
                            class="mt-1 text-xs text-red-600 font-medium ml-1" />
                    </div>
                </div>

                <!-- Privacy Policy Checkbox & Modal Trigger -->
                <div class="pt-1 pb-1">
                    <label class="flex items-start gap-2 cursor-pointer group">
                        <div class="relative flex items-center pt-0.5">
                            <input id="policy_checkbox" type="checkbox" required
                                class="w-4 h-4 border-2 border-gray-300 rounded text-red-600 focus:ring-red-500 focus:ring-offset-1 transition-colors cursor-pointer group-hover:border-red-500 shadow-sm">
                        </div>
                        <span class="text-xs text-gray-600 leading-relaxed">
                            ฉันได้อ่านและยอมรับ
                            <button type="button" @click="showPolicy = true"
                                class="font-bold text-red-600 hover:text-red-700 underline decoration-2 underline-offset-2 transition-colors focus:outline-none">
                                นโยบายความเป็นส่วนตัว
                            </button>
                            เกี่ยวกับการจัดเก็บข้อมูลสุขภาพจิต และยินยอมให้ประมวลผลข้อมูล
                        </span>
                    </label>
                </div>

                <div class="pt-1">
                    <button type="submit"
                        class="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-500 hover:to-orange-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform transition-all hover:-translate-y-0.5 hover:shadow-red-500/30 active:scale-95">
                        ลงทะเบียน
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center text-xs">
                <p class="text-gray-500 font-medium">มีบัญชีผู้ใช้อยู่แล้ว?
                    <a href="{{ route('login') }}"
                        class="font-bold text-red-600 hover:text-red-500 hover:underline transition-all">เข้าสู่ระบบเลย</a>
                </p>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-400 hover:text-gray-700 transition-colors bg-white/50 px-3 py-1.5 rounded-full border border-gray-100">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    กลับสู่หน้าหลัก
                </a>
            </div>
        </div>
    </div>

    <!-- Footer on Right Side (Desktop) -->
    <div class="absolute bottom-4 text-center w-full lg:block hidden">
        <p class="text-xs text-gray-400 font-medium">&copy; {{ date('Y') }} RescueMind. All rights reserved.</p>
    </div>
    </div>

    <!-- Policy Modal -->
    <div x-show="showPolicy" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showPolicy" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="showPolicy = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showPolicy" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                <div
                    class="bg-gradient-to-br from-blue-900 to-indigo-900 px-6 py-5 flex justify-between items-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl">
                    </div>
                    <h3 class="text-xl leading-6 font-bold text-white relative z-10" id="modal-title">
                        <x-heroicon-o-shield-check class="w-5 h-5 inline-block shrink-0" /> นโยบายความเป็นส่วนตัว (สรุปย่อ)
                    </h3>
                    <button @click="showPolicy = false"
                        class="text-blue-200 hover:text-white transition-colors relative z-10 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="px-6 pt-5 pb-6 text-gray-700 space-y-4 max-h-[60vh] overflow-y-auto">
                    <p class="text-sm font-medium text-gray-900 bg-blue-50 p-3 rounded-xl border border-blue-100">
                        โครงการ ReMind Project ให้ความสำคัญกับความเป็นส่วนตัวและข้อมูลสุขภาพจิตของคุณเป็นอันดับแรก
                    </p>
                    <div>
                        <h4 class="font-bold text-gray-900 flex items-center gap-2 mb-1"><span
                                class="text-blue-500">1.</span> ข้อมูลที่เราจัดเก็บ</h4>
                        <p class="text-sm pl-5">ข้อมูลระบุตัวตน, <strong class="text-red-600">ข้อมูลสุขภาพจิต (Sensitive
                                Data)</strong>, ปัจจัยเสี่ยง และประวัติการใช้งาน</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 flex items-center gap-2 mb-1"><span
                                class="text-blue-500">2.</span> วัตถุประสงค์</h4>
                        <p class="text-sm pl-5">เพื่อประเมินสุขภาพจิตเบื้องต้น คัดกรองความเสี่ยง ติดตามช่วยเหลือ
                            และงานวิจัยสาธารณสุข</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 flex items-center gap-2 mb-1"><span
                                class="text-blue-500">3.</span> การรักษาความปลอดภัย</h4>
                        <p class="text-sm pl-5">เราใช้ระบบเข้ารหัส TLS/SSL, เข้ารหัสฐานข้อมูล
                            และควบคุมการเข้าถึงอย่างเคร่งครัด</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 flex items-center gap-2 mb-1"><span
                                class="text-blue-500">4.</span> สิทธิของคุณ</h4>
                        <p class="text-sm pl-5">คุณมีสิทธิในการเข้าถึง แก้ไข ลบ โอนย้าย ระงับการใช้ คัดค้าน
                            และถอนความยินยอมได้ตลอดเวลา ตามพ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA)</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="button"
                        @click="showPolicy = false; document.getElementById('policy_checkbox').checked = true;"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        ฉันเข้าใจและยอมรับ
                    </button>
                    <a href="{{ route('privacy.policy') }}" target="_blank"
                        class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                        อ่านฉบับเต็ม
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>