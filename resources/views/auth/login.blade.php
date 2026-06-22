<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>เข้าสู่ระบบ - {{ config('app.name', 'RescueMind') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-login-image {
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

        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating Labels */
        .input-floating label {
            padding: 0 0.25rem;
            margin-left: -0.25rem;
            border-radius: 0.25rem;
        }

        .input-floating:focus-within label,
        .input-floating input:not(:placeholder-shown)+label {
            transform: translateY(-1.55rem) scale(0.85);
            color: #dc2626;
            /* red-600 */
            background-color: white;
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
    </style>
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body class="antialiased bg-gray-50 text-gray-900 min-h-screen flex selection:bg-red-500 selection:text-white">

    <!-- Left Side: Image & Branding (Desktop Only) -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-login-image items-center justify-center overflow-hidden">
        <!-- Overlays -->
        <div class="absolute inset-0 bg-gradient-to-br from-red-950/90 via-gray-900/80 to-black/95 mix-blend-multiply">
        </div>
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] opacity-40">
        </div>

        <!-- Content -->
        <div class="relative z-10 p-16 flex flex-col justify-center h-full max-w-2xl text-white animate-fade-in-up">
            <div
                class="w-40 h-40 bg-white rounded-full flex items-center justify-center p-5 mb-8 shadow-2xl transform hover:scale-105 transition-transform duration-500 ring-8 ring-white/20">
                <x-application-logo class="w-full h-full object-contain drop-shadow-md" />
            </div>

            <h1 class="text-5xl font-black mb-6 leading-tight tracking-tight">
                RescueMind<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-300">Empowering
                    Survival</span>
            </h1>

            <p class="text-lg text-gray-300 mb-10 leading-relaxed font-light max-w-lg">
                ระบบจัดการภัยพิบัติและดูแลสุขภาพจิตแบบครบวงจร
                เชื่อมต่อความช่วยเหลือถึงมือคุณในทุกสถานการณ์ พร้อมดูแลเยียวยาจิตใจในทุกช่วงเวลา
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

    <!-- Right Side: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative overflow-hidden bg-gray-50">
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
            class="w-full max-w-md relative z-10 glass-panel p-8 sm:p-10 rounded-3xl shadow-2xl animate-fade-in-up delay-100">

            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center justify-center mb-8">
                <div
                    class="w-28 h-28 bg-white rounded-full shadow-lg flex items-center justify-center p-4 ring-4 ring-white border border-gray-100">
                    <x-application-logo class="w-full h-full object-contain drop-shadow-sm" />
                </div>
            </div>

            <div class="mb-10 lg:mb-8 text-center lg:text-left">
                <h2 class="text-3xl font-black text-gray-900 mb-2 tracking-tight">ยินดีต้อนรับ <x-heroicon-o-hand-raised class="w-5 h-5 inline-block mr-1 -mt-1" /></h2>
                <p class="text-gray-500 font-medium text-sm sm:text-base">เข้าสู่ระบบเพื่อดำเนินการต่อ</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status
                class="mb-6 bg-green-50 text-green-700 p-3 rounded-xl text-sm font-medium border border-green-100"
                :status="session('status')" />

            <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6"
                onsubmit="event.preventDefault(); confirmLogin();">
                @csrf

                <!-- Email Address -->
                <div class="relative input-floating">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="block w-full px-5 py-4 bg-white/80 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm"
                        placeholder="Email" />
                    <label for="email"
                        class="absolute left-5 top-4 text-gray-500 text-sm transition-all peer-focus:-translate-y-6 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left">อีเมล
                        (Email)</label>
                    <x-input-error :messages="$errors->get('email')"
                        class="mt-2 text-xs text-red-600 font-medium ml-1" />
                </div>

                <!-- Password -->
                <div class="relative input-floating">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="block w-full px-5 py-4 pr-12 bg-white/80 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm"
                        placeholder="Password" />
                    <label for="password"
                        class="absolute left-5 top-4 text-gray-500 text-sm transition-all peer-focus:-translate-y-6 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left">รหัสผ่าน
                        (Password)</label>
                    <button type="button" onclick="const p = document.getElementById('password'); const e1 = document.getElementById('eye-icon-1'); const e2 = document.getElementById('eye-icon-2'); if(p.type === 'password') { p.type = 'text'; e1.classList.add('hidden'); e2.classList.remove('hidden'); } else { p.type = 'password'; e1.classList.remove('hidden'); e2.classList.add('hidden'); }" class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <svg id="eye-icon-1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg id="eye-icon-2" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                    <x-input-error :messages="$errors->get('password')"
                        class="mt-2 text-xs text-red-600 font-medium ml-1" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-sm mt-6">
                    <label for="remember_me" class="flex items-center cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input id="remember_me" type="checkbox" class="peer sr-only" name="remember">
                            <div
                                class="w-5 h-5 bg-white border-2 border-gray-300 rounded peer-checked:bg-red-500 peer-checked:border-red-500 transition-all shadow-sm">
                            </div>
                            <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span
                            class="ml-3 text-gray-600 font-semibold group-hover:text-gray-900 transition-colors">จดจำฉัน</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="font-bold text-red-600 hover:text-red-500 transition-all"
                            href="{{ route('password.request') }}">
                            ลืมรหัสผ่าน?
                        </a>
                    @endif
                </div>

                <!-- Cloudflare Turnstile Widget -->
                @if(config('services.turnstile.site_key'))
                <div class="flex justify-center my-2">
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
                </div>
                @error('cf-turnstile-response')
                    <div class="text-center text-xs text-red-600 font-medium">{{ $message }}</div>
                @enderror
                @endif

                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex items-center justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl text-base font-bold text-white bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-500 hover:to-orange-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform transition-all hover:-translate-y-1 hover:shadow-red-500/30 active:scale-95">
                        เข้าสู่ระบบ
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>



            <div class="mt-8 text-center text-sm">
                <p class="text-gray-500 font-medium">ยังไม่มีบัญชีใช่หรือไม่?
                    <a href="{{ route('register') }}"
                        class="font-bold text-red-600 hover:text-red-500 transition-all">สมัครสมาชิกใหม่</a>
                </p>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-400 hover:text-gray-700 transition-colors bg-white/50 px-3 py-1.5 rounded-full border border-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    กลับสู่หน้าหลัก
                </a>
            </div>
        </div>

        <!-- Footer on Right Side (Desktop) -->
        <div class="absolute bottom-6 text-center w-full lg:block hidden">
            <p class="text-xs text-gray-400 font-medium">&copy; {{ date('Y') }} RescueMind. All rights reserved.</p>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogin() {
            Swal.fire({
                title: 'นโยบายความเป็นส่วนตัว',
                html: 'การเข้าสู่ระบบถือว่าคุณรับทราบและยอมรับเงื่อนไขของเรา<br><br><a href="{{ route("privacy.policy") }}" target="_blank" class="inline-block mt-2 px-4 py-2 bg-red-50 text-red-600 font-bold rounded-lg hover:bg-red-100 transition-colors text-sm"><svg class="w-4 h-4 inline-block mr-1 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> อ่าน นโยบายความเป็นส่วนตัว (Privacy Policy)</a>',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // red-500
                cancelButtonColor: '#9ca3af', // gray-400
                confirmButtonText: 'ยอมรับและเข้าสู่ระบบ',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                    cancelButton: 'rounded-xl font-bold px-6 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('loginForm').submit();
                }
            });
        }
    </script>
</body>
</html>