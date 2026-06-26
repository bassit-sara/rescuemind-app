<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบกำลังปรับปรุง - RescueMind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .soft-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 20px 40px -15px rgba(100, 116, 139, 0.15);
        }
        .bg-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40vh;
            background: linear-gradient(to top, #e0e7ff 0%, transparent 100%);
            z-index: -1;
            opacity: 0.6;
        }
        .floating-icon {
            animation: float-soft 6s ease-in-out infinite;
        }
        @keyframes float-soft {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex items-center justify-center min-h-screen p-4 overflow-hidden relative">
    
    <!-- Soft Background Decor -->
    <div class="bg-wave"></div>
    <div class="absolute top-[-10%] left-[-5%] w-96 h-96 bg-indigo-100 rounded-full blur-[100px] opacity-60"></div>
    <div class="absolute bottom-[20%] right-[-10%] w-80 h-80 bg-sky-100 rounded-full blur-[80px] opacity-60"></div>

    <div class="soft-card p-10 md:p-14 rounded-[2rem] max-w-lg w-full text-center relative z-10 transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/5">
        
        <div class="floating-icon relative w-24 h-24 mx-auto mb-8">
            <div class="absolute inset-0 bg-indigo-50 rounded-full flex items-center justify-center">
                <x-heroicon-o-wrench-screwdriver class="w-10 h-10 text-indigo-400" />
            </div>
            <!-- decorative circles -->
            <div class="absolute top-0 right-0 w-3 h-3 bg-indigo-300 rounded-full"></div>
            <div class="absolute bottom-2 left-0 w-2 h-2 bg-sky-300 rounded-full"></div>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold mb-3 text-slate-800">
            กำลังพัฒนาระบบ
        </h1>
        
        <div class="w-12 h-1 bg-indigo-200 rounded-full mx-auto mb-6"></div>

        <p class="text-slate-500 mb-10 leading-relaxed text-sm md:text-base font-medium px-4">
            {{ $message ?? 'ขออภัยในความไม่สะดวก เรากำลังพัฒนาระบบให้ดียิ่งขึ้น เพื่อประสบการณ์ที่ราบรื่น กรุณากลับมาใหม่ในภายหลัง' }}
        </p>
        
        <div class="flex flex-col gap-4 items-center">
            <a href="{{ route('home') }}" class="group relative inline-flex items-center justify-center px-8 py-3 text-sm font-semibold text-indigo-600 transition-all duration-200 bg-indigo-50 rounded-xl hover:bg-indigo-100 hover:text-indigo-700">
                <span class="flex items-center gap-2">
                    <x-heroicon-o-arrow-path class="w-4 h-4 group-hover:-rotate-180 transition-transform duration-500" />
                    ลองรีเฟรชหน้าเว็บ
                </span>
            </a>
            
            <div class="mt-4 flex items-center gap-2 text-xs text-slate-400 font-medium tracking-wide">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                RescueMind System Maintenance
            </div>
        </div>
    </div>
</body>
</html>
