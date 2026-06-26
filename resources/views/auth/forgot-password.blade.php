<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ลืมรหัสผ่าน - {{ config('app.name', 'ReMind') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=prompt:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-prompt antialiased bg-gray-50 text-gray-900 selection:bg-rose-500 selection:text-white">
    <div class="min-h-screen relative flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
        
        <!-- Decorative Background Elements -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden bg-gradient-to-br from-rose-50 via-pink-50 to-purple-50">
            <!-- Blobs -->
            <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] rounded-full bg-rose-200/50 mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-[35vw] h-[35vw] rounded-full bg-purple-200/50 mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-[45vw] h-[45vw] rounded-full bg-pink-200/50 mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>
            <!-- Texture overlay -->
            <div class="absolute inset-0 opacity-[0.02] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">
            
            <!-- Logo Section -->
            <div class="text-center mb-10 transform transition-all duration-500 hover:scale-105">
                <a href="/" class="inline-flex flex-col items-center justify-center group">
                    <div class="w-24 h-24 bg-white/60 p-2 rounded-full shadow-lg backdrop-blur-md border border-white mb-4 group-hover:shadow-rose-200 group-hover:border-rose-200 transition-all duration-300 relative overflow-hidden">
                        <x-application-logo class="w-full h-full object-cover rounded-full shadow-inner relative z-10 bg-white" />
                        <div class="absolute inset-0 bg-gradient-to-tr from-rose-100 to-purple-100 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <h1 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rose-500 to-purple-600 tracking-tight drop-shadow-sm">
                        ReMind
                    </h1>
                </a>
            </div>

            <!-- Card Section -->
            <div class="bg-white/80 backdrop-blur-xl shadow-2xl rounded-[2.5rem] border border-white p-8 sm:p-10 transform transition-all hover:shadow-rose-100/50">
                
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 mb-5 shadow-sm border border-rose-100">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-extrabold text-gray-800 mb-3 tracking-tight">ลืมรหัสผ่านใช่ไหม?</h2>
                    <p class="text-[13px] text-gray-500 leading-relaxed font-medium">
                        ไม่ต้องกังวล! เพียงกรอกอีเมลของคุณด้านล่าง เราจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ไปให้ทางอีเมลทันที
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6 p-4 rounded-2xl bg-emerald-50/80 backdrop-blur border border-emerald-200/50 text-emerald-700 text-sm font-semibold text-center shadow-sm" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-rose-500 transition-colors">
                            <svg class="w-5 h-5 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="กรอกอีเมลของคุณที่นี่..."
                            class="block w-full pl-12 pr-5 py-4 bg-white/70 backdrop-blur-sm border-2 border-gray-100 text-gray-900 rounded-2xl focus:ring-0 focus:border-rose-400 transition-all shadow-sm placeholder-gray-400 font-medium text-[15px] hover:border-gray-200" />
                    </div>
                    @error('email')
                        <p class="text-sm text-red-500 font-bold mt-2 flex items-center gap-1.5 px-1">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror

                    <div class="pt-3">
                        <button type="submit" class="group relative w-full flex justify-center items-center gap-2 py-4 px-6 border border-transparent rounded-2xl shadow-lg text-[15px] font-bold text-white bg-gradient-to-r from-rose-500 to-purple-600 hover:from-rose-600 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-rose-500/30 transform transition-all active:scale-[0.98] overflow-hidden">
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out"></div>
                            <span class="relative z-10">ส่งลิงก์รีเซ็ตรหัสผ่าน</span>
                            <svg class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                    
                    <div class="mt-8 text-center pt-6 border-t border-gray-100/80">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 text-sm font-bold text-gray-500 hover:text-rose-600 transition-colors group">
                            <span class="p-1.5 rounded-full bg-gray-50 group-hover:bg-rose-50 transition-colors">
                                <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            </span>
                            กลับไปหน้าเข้าสู่ระบบ
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 text-center text-xs font-bold text-gray-400 tracking-wide uppercase">
                &copy; {{ date('Y') }} ReMind. All rights reserved.
            </div>
        </div>
    </div>
    
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>
