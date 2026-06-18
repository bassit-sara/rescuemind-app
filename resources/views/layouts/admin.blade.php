<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#dc2626">
    <title>@yield('title', 'RescueMind') — ระบบจัดการ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Noto Sans Thai', 'Inter', sans-serif; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 12px; border-radius: 10px; font-size: 0.85rem;
            font-weight: 500; transition: all .15s; color: #374151;
            text-decoration: none;
        }
        .sidebar-link:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sidebar-link.active { background: rgba(255,255,255,.2); color: #fff; font-weight: 700; }
        .sidebar-section { font-size: 0.68rem; font-weight: 800; letter-spacing: .08em;
            text-transform: uppercase; padding: 4px 12px; margin-top: 12px;
            color: rgba(255,255,255,.5); }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(239,68,68,.7); }
            70%  { box-shadow: 0 0 0 20px rgba(239,68,68,0); }
            100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }
        .pulse-sos { animation: pulse-ring 1.5s ease infinite; }
        .priority-critical { background: #fef2f2; border-left: 4px solid #ef4444; }
        .priority-high     { background: #fff7ed; border-left: 4px solid #f97316; }
        .priority-medium   { background: #fefce8; border-left: 4px solid #eab308; }
        .priority-low      { background: #f0fdf4; border-left: 4px solid #22c55e; }
        /* Sidebar */
        #admin-sidebar {
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        @media (max-width: 1023px) {
            #admin-sidebar { transform: translateX(-100%); position: fixed; z-index: 50; }
            #admin-sidebar.open { transform: translateX(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-100" x-data="{ sidebarOpen: false }">

@php
$user = auth()->user();
$role = $user?->getRoleNames()->first() ?? 'user';

// Sidebar gradient per role
$gradients = [
    'super_admin'    => 'from-slate-900 via-slate-800 to-slate-900',
    'admin'          => 'from-blue-900 via-blue-800 to-blue-900',
    'officer'        => 'from-red-900 via-red-800 to-red-900',
    'mental_officer' => 'from-purple-900 via-purple-800 to-purple-900',
];
$gradient = $gradients[$role] ?? 'from-gray-900 via-gray-800 to-gray-900';

$roleLabels = [
    'super_admin'    => '👑 Super Admin',
    'admin'          => '🛡️ Admin',
    'officer'        => '🚨 เจ้าหน้าที่กู้ภัย',
    'mental_officer' => '🧠 นักจิตวิทยา',
];
$roleLabel = $roleLabels[$role] ?? '👤 User';
@endphp

{{-- Critical alert banner --}}
@php $criticalAlerts = \App\Models\Alert::where('is_active',true)->where('level',3)->count(); @endphp
@if($criticalAlerts > 0)
<div class="bg-red-600 text-white text-center py-2 px-4 text-sm font-medium z-50 relative">
    <span class="animate-pulse">⚠️</span>
    มีการแจ้งเตือนระดับ "อพยพทันที" {{ $criticalAlerts }} จังหวัด
    <a href="{{ route('alerts.index') }}" class="underline ml-2 font-bold">ดูรายละเอียด →</a>
</div>
@endif

<div class="flex h-screen overflow-hidden">

    {{-- ═══════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════ --}}
    <aside id="admin-sidebar"
           class="w-64 flex-shrink-0 flex flex-col bg-gradient-to-b {{ $gradient }} h-full overflow-y-auto">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-white flex-shrink-0 shadow-lg border-2 border-white/20">
                <img src="{{ asset('images/logo.png') }}" alt="RescueMind" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <div class="text-white font-black text-sm leading-tight">RescueMind</div>
                <div class="text-white/60 text-xs truncate">{{ $roleLabel }}</div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="px-4 py-3 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-white text-sm font-semibold truncate">{{ $user->name }}</div>
                    <div class="text-white/50 text-xs truncate">{{ $user->province ?? 'ไม่ระบุจังหวัด' }}</div>
                </div>
            </div>
        </div>

        {{-- Sidebar Weather --}}
        <div id="sidebar-weather" class="px-4 py-2.5 border-b border-white/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span id="sw-icon" class="text-lg">🌡️</span>
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span id="sw-temp" class="text-white font-black text-lg leading-none">--</span>
                            <span class="text-white/50 text-xs">°C</span>
                        </div>
                        <div id="sw-desc" class="text-white/40 text-[10px] leading-tight mt-0.5">กำลังโหลด...</div>
                    </div>
                </div>
                <div class="text-right">
                    <div id="sw-wind" class="text-white/40 text-[10px]">💨 --</div>
                    <div id="sw-humidity" class="text-white/40 text-[10px]">💧 --</div>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-4 space-y-0.5">

            {{-- ─── SUPER ADMIN ─── --}}
            @if($role === 'super_admin')
            <div class="sidebar-section">📊 ภาพรวมระบบ</div>
            <a href="{{ route('super-admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                <span>🎛️</span><span>Command Center</span>
            </a>
            <a href="{{ route('super-admin.analytics') }}" class="sidebar-link {{ request()->routeIs('super-admin.analytics') ? 'active' : '' }}">
                <span>📈</span><span>Analytics</span>
            </a>

            <div class="sidebar-section">👥 จัดการผู้ใช้</div>
            <a href="{{ route('super-admin.users') }}" class="sidebar-link {{ request()->routeIs('super-admin.users*') ? 'active' : '' }}">
                <span>👥</span><span>ผู้ใช้ทั้งหมด</span>
            </a>

            <div class="sidebar-section">🆘 ภัยพิบัติ</div>
            <a href="{{ route('officer.sos.index') }}" class="sidebar-link {{ request()->routeIs('officer.sos.*') ? 'active' : '' }}">
                <span>🆘</span><span>คิว SOS</span>
            </a>
            <a href="{{ route('officer.hazard.index') }}" class="sidebar-link {{ request()->routeIs('officer.hazard.*') ? 'active' : '' }}">
                <span>⚠️</span><span>รายงานภัย</span>
            </a>
            <a href="{{ route('officer.missing.index') }}" class="sidebar-link {{ request()->routeIs('officer.missing.*') ? 'active' : '' }}">
                <span>🔍</span><span>คนหาย</span>
            </a>

            <div class="sidebar-section">🏕️ ทรัพยากร</div>
            <a href="{{ route('super-admin.shelter') }}" class="sidebar-link {{ request()->routeIs('super-admin.shelter') ? 'active' : '' }}">
                <span>🏕️</span><span>ที่พักพิง</span>
            </a>
            <a href="{{ route('super-admin.resources') }}" class="sidebar-link {{ request()->routeIs('super-admin.resources') ? 'active' : '' }}">
                <span>📦</span><span>ทรัพยากร</span>
            </a>

            <div class="sidebar-section">📢 จัดการเนื้อหา</div>
            <a href="{{ route('admin.alerts.index') }}" class="sidebar-link {{ request()->routeIs('admin.alerts.*') ? 'active' : '' }}">
                <span>🚨</span><span>การแจ้งเตือน</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <span>📰</span><span>ข่าวสาร</span>
            </a>
            <a href="{{ route('admin.relief-points.index') }}" class="sidebar-link {{ request()->routeIs('admin.relief-points.*') ? 'active' : '' }}">
                <span>🏥</span><span>จุดช่วยเหลือ</span>
            </a>

            <div class="sidebar-section">🧠 สุขภาพจิต</div>
            <a href="{{ route('mental-officer.dashboard') }}" class="sidebar-link {{ request()->routeIs('mental-officer.dashboard') ? 'active' : '' }}">
                <span>📊</span><span>ภาพรวมจิตใจ</span>
            </a>
            <a href="{{ route('mental-officer.assessments') }}" class="sidebar-link {{ request()->routeIs('mental-officer.assessments*') ? 'active' : '' }}">
                <span>📋</span><span>ผลการประเมิน</span>
            </a>
            <a href="{{ route('mental-officer.appointments') }}" class="sidebar-link {{ request()->routeIs('mental-officer.appointments*') ? 'active' : '' }}">
                <span>📅</span><span>นัดหมาย</span>
            </a>
            @endif

            {{-- ─── ADMIN ─── --}}
            @if($role === 'admin')
            <div class="sidebar-section">📊 ภาพรวม</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>🎛️</span><span>แดชบอร์ด</span>
            </a>

            <div class="sidebar-section">🆘 ภัยพิบัติ</div>
            <a href="{{ route('officer.sos.index') }}" class="sidebar-link {{ request()->routeIs('officer.sos.*') ? 'active' : '' }}">
                <span>🆘</span><span>คิว SOS</span>
            </a>
            <a href="{{ route('officer.hazard.index') }}" class="sidebar-link {{ request()->routeIs('officer.hazard.*') ? 'active' : '' }}">
                <span>⚠️</span><span>รายงานภัย</span>
            </a>
            <a href="{{ route('officer.missing.index') }}" class="sidebar-link {{ request()->routeIs('officer.missing.*') ? 'active' : '' }}">
                <span>🔍</span><span>คนหาย</span>
            </a>

            <div class="sidebar-section">📢 จัดการเนื้อหา</div>
            <a href="{{ route('admin.alerts.index') }}" class="sidebar-link {{ request()->routeIs('admin.alerts.*') ? 'active' : '' }}">
                <span>🚨</span><span>การแจ้งเตือน</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <span>📰</span><span>ข่าวสาร</span>
            </a>
            <a href="{{ route('admin.relief-points.index') }}" class="sidebar-link {{ request()->routeIs('admin.relief-points.*') ? 'active' : '' }}">
                <span>🏥</span><span>จุดช่วยเหลือ</span>
            </a>
            <a href="{{ route('admin.resources.index') }}" class="sidebar-link {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
                <span>📦</span><span>ทรัพยากร</span>
            </a>

            <div class="sidebar-section">👥 จัดการผู้ใช้</div>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span>👥</span><span>ผู้ใช้ในระบบ</span>
            </a>
            @endif

            {{-- ─── OFFICER ─── --}}
            @if($role === 'officer')
            <div class="sidebar-section">🆘 งานของฉัน</div>
            <a href="{{ route('officer.dashboard') }}" class="sidebar-link {{ request()->routeIs('officer.dashboard') ? 'active' : '' }}">
                <span>🎛️</span><span>แดชบอร์ด</span>
            </a>
            <a href="{{ route('officer.sos.index') }}" class="sidebar-link {{ request()->routeIs('officer.sos.*') ? 'active' : '' }}">
                <span>🆘</span><span>คิว SOS</span>
            </a>
            <a href="{{ route('officer.hazard.index') }}" class="sidebar-link {{ request()->routeIs('officer.hazard.*') ? 'active' : '' }}">
                <span>⚠️</span><span>รายงานภัย</span>
            </a>
            <a href="{{ route('officer.missing.index') }}" class="sidebar-link {{ request()->routeIs('officer.missing.*') ? 'active' : '' }}">
                <span>🔍</span><span>คนหาย</span>
            </a>

            <div class="sidebar-section">📍 ข้อมูลพื้นที่</div>
            <a href="{{ route('alerts.index') }}" class="sidebar-link {{ request()->routeIs('alerts.*') ? 'active' : '' }}">
                <span>🚨</span><span>การแจ้งเตือน</span>
            </a>
            <a href="{{ route('relief-points.index') }}" class="sidebar-link {{ request()->routeIs('relief-points.*') ? 'active' : '' }}">
                <span>🏥</span><span>จุดช่วยเหลือ</span>
            </a>
            @endif

            {{-- ─── MENTAL OFFICER ─── --}}
            @if($role === 'mental_officer')
            <div class="sidebar-section">🧠 งานของฉัน</div>
            <a href="{{ route('mental-officer.dashboard') }}" class="sidebar-link {{ request()->routeIs('mental-officer.dashboard') ? 'active' : '' }}">
                <span>📊</span><span>ภาพรวม</span>
            </a>
            <a href="{{ route('mental-officer.assessments') }}" class="sidebar-link {{ request()->routeIs('mental-officer.assessments*') ? 'active' : '' }}">
                <span>📋</span><span>ผลการประเมิน</span>
            </a>
            <a href="{{ route('mental-officer.appointments') }}" class="sidebar-link {{ request()->routeIs('mental-officer.appointments*') ? 'active' : '' }}">
                <span>📅</span><span>นัดหมาย</span>
            </a>

            <div class="sidebar-section">📚 เครื่องมือ</div>
            <a href="{{ route('mental.articles') }}" class="sidebar-link {{ request()->routeIs('mental.articles') ? 'active' : '' }}">
                <span>📖</span><span>บทความสุขภาพจิต</span>
            </a>
            @endif

            {{-- ─── SHARED ─── --}}
            <div class="sidebar-section mt-4 border-t border-white/10 pt-3">ทั่วไป</div>
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span>👤</span><span>โปรไฟล์</span>
            </a>
            <a href="{{ route('home') }}" class="sidebar-link">
                <span>🌐</span><span>ไปหน้าเว็บหลัก</span>
            </a>
        </nav>

        {{-- Logout --}}
        <div class="px-2 py-3 border-t border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-300 hover:text-white hover:!bg-red-600/40">
                    <span>🚪</span><span>ออกจากระบบ</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-200 shadow-sm px-4 py-3 flex items-center justify-between gap-3 flex-shrink-0">
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger --}}
                <button onclick="document.getElementById('admin-sidebar').classList.toggle('open')"
                        class="lg:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="font-bold text-gray-800 text-sm sm:text-base leading-tight">@yield('page-title', 'แดชบอร์ด')</h1>
                    <p class="text-xs text-gray-400 hidden sm:block">{{ now()->locale('th')->isoFormat('D MMMM YYYY') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Alerts badge --}}
                @if($criticalAlerts > 0)
                <a href="{{ route('alerts.index') }}" class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-bold animate-pulse">
                    ⚠️ {{ $criticalAlerts }} วิกฤต
                </a>
                @endif

                {{-- ฉันปลอดภัย --}}
                <form action="{{ route('family.safe') }}" method="POST" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-green-500 text-white text-xs font-semibold rounded-full hover:bg-green-600 transition-colors">
                        ✅ ฉันปลอดภัย
                    </button>
                </form>

                {{-- Profile avatar --}}
                <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <span class="hidden sm:block text-sm text-gray-700 font-medium max-w-[120px] truncate">{{ $user->name }}</span>
            </div>
        </header>

        {{-- Overlay for mobile sidebar --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"
             onclick="document.getElementById('admin-sidebar').classList.remove('open'); this.classList.add('hidden')"></div>

        {{-- Main --}}
        <main class="flex-1 overflow-y-auto p-4 sm:p-6">
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3" x-data x-init="setTimeout(()=>$el.remove(),5000)">
                <span class="text-xl">✅</span>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                <span class="text-xl">❌</span>
                <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById('admin-sidebar');
        if (sidebar) {
            // Restore scroll position
            const scrollPos = sessionStorage.getItem('adminSidebarScroll');
            if (scrollPos) {
                sidebar.scrollTop = scrollPos;
            }
            // Save scroll position before unloading
            window.addEventListener('beforeunload', function() {
                sessionStorage.setItem('adminSidebarScroll', sidebar.scrollTop);
            });
        }

        // Fetch weather for sidebar
        function fetchSidebarWeather() {
            // Default Bangkok
            let lat = 13.7563;
            let lon = 100.5018;
            
            // Check cache
            const cachedLat = localStorage.getItem('rm_lat');
            const cachedLon = localStorage.getItem('rm_lon');
            if(cachedLat && cachedLon) {
                lat = parseFloat(cachedLat);
                lon = parseFloat(cachedLon);
            }
            
            const doFetch = () => {
                const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=Asia%2FBangkok`;
                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        if(!data.current) return;
                        const c = data.current;
                        const swTemp = document.getElementById('sw-temp');
                        if(swTemp) swTemp.textContent = Math.round(c.temperature_2m);
                        
                        const swWind = document.getElementById('sw-wind');
                        if(swWind) swWind.textContent = `💨 ${Math.round(c.wind_speed_10m)} km/h`;
                        
                        const swHumidity = document.getElementById('sw-humidity');
                        if(swHumidity) swHumidity.textContent = `💧 ${c.relative_humidity_2m}%`;
                        
                        const wcodes = {
                            0: {d:'ฟ้าโปร่ง', i:'☀️'}, 1: {d:'ส่วนใหญ่โปร่ง', i:'🌤️'}, 2: {d:'มีเมฆบางส่วน', i:'⛅'}, 3: {d:'มีเมฆมาก', i:'☁️'},
                            45: {d:'หมอก', i:'🌫️'}, 48: {d:'หมอก', i:'🌫️'},
                            51: {d:'ฝนละออง', i:'🌦️'}, 53: {d:'ฝนละออง', i:'🌦️'}, 55: {d:'ฝนละออง', i:'🌧️'},
                            61: {d:'ฝนตกเบา', i:'🌦️'}, 63: {d:'ฝนตกปานกลาง', i:'🌧️'}, 65: {d:'ฝนตกหนัก', i:'🌧️'}, 
                            80: {d:'ฝนเป็นพัก', i:'🌦️'}, 81: {d:'ฝนเป็นพัก', i:'🌧️'}, 82: {d:'ฝนเป็นพัก', i:'⛈️'},
                            95: {d:'พายุฟ้าคะนอง', i:'⛈️'}, 96: {d:'พายุฟ้าคะนอง', i:'⛈️'}, 99: {d:'พายุฟ้าคะนอง', i:'⛈️'}
                        };
                        const info = wcodes[c.weather_code] || {d: 'ฝนตก/เมฆมาก', i: '🌦️'};
                        const swIcon = document.getElementById('sw-icon');
                        if(swIcon) swIcon.textContent = info.i;
                        const swDesc = document.getElementById('sw-desc');
                        if(swDesc) swDesc.textContent = info.d;
                    })
                    .catch(() => {
                        const swDesc = document.getElementById('sw-desc');
                        if(swDesc) swDesc.textContent = "โหลดไม่สำเร็จ";
                    });
            };
            
            // Always try to get fresh location if possible, but run doFetch first with cached/default
            doFetch();
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => { 
                        if(lat !== pos.coords.latitude || lon !== pos.coords.longitude) {
                            lat = pos.coords.latitude; 
                            lon = pos.coords.longitude;
                            localStorage.setItem('rm_lat', lat);
                            localStorage.setItem('rm_lon', lon);
                            doFetch(); // Re-fetch with exact location
                        }
                    },
                    () => { console.log("Location access denied or failed."); }
                );
            }
        }
        fetchSidebarWeather();
    });
</script>

@yield('scripts')
@stack('scripts')

</body>
</html>
