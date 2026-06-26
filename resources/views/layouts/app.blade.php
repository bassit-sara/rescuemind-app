<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#dc2626">
    <title>@yield('title', 'RescueMind') — ระบบบริหารจัดการภัยพิบัติ</title>
    <meta name="description" content="RescueMind — National Disaster Management & Mental Resilience Platform">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Noto Sans Thai', 'Inter', sans-serif; }

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

        /* Bottom nav safe area */
        .bottom-nav { padding-bottom: env(safe-area-inset-bottom, 0px); }

        /* Add bottom padding to main when bottom nav is shown */
        .has-bottom-nav { padding-bottom: 80px; }

        /* Mobile drawer */
        #mobile-drawer {
            transform: translateX(100%);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        #mobile-drawer.open { transform: translateX(0); }
        #drawer-overlay {
            opacity: 0; pointer-events: none;
            transition: opacity .3s;
        }
        #drawer-overlay.open { opacity: 1; pointer-events: auto; }

        /* Active bottom tab */
        .btab-active { color: #dc2626 !important; }
        .btab-active svg { stroke: #dc2626 !important; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-50" x-data="{ sidebarOpen: false }">

@php
    $showBottomNav = true;
@endphp

{{-- Active Alert Banner --}}
@php $highestAlert = \App\Models\Alert::where('is_active', true)->orderBy('level', 'desc')->first(); @endphp
@if($highestAlert)
    @php
        $bannerClass = 'bg-yellow-500 text-yellow-900'; $alertText = 'เฝ้าระวัง'; $alertIcon = 'x-heroicon-o-information-circle';
        if ($highestAlert->level == 3) { $bannerClass = 'bg-red-600 text-white'; $alertText = 'อพยพทันที'; $alertIcon = 'x-heroicon-o-exclamation-triangle'; }
        elseif ($highestAlert->level == 2) { $bannerClass = 'bg-orange-500 text-white'; $alertText = 'เตรียมพร้อม'; $alertIcon = 'x-heroicon-o-exclamation-circle'; }
        $alertCount = \App\Models\Alert::where('is_active', true)->where('level', $highestAlert->level)->count();
    @endphp
    <div class="{{ $bannerClass }} text-center py-1.5 px-3 text-[11px] font-medium z-50 relative flex items-center justify-center gap-1 shadow-sm">
        <span class="animate-pulse flex items-center">@svg($alertIcon, 'w-3.5 h-3.5')</span>
        <span>แจ้งเตือนภัยระดับ "{{ $alertText }}" ({{ $alertCount }} จังหวัด)</span>
        <a href="{{ route('alerts.index') }}" class="underline ml-1 font-bold">ดูรายละเอียด</a>
    </div>
@endif

<div class="flex h-full min-h-screen">

    <div class="flex-1 flex flex-col min-w-0">

        {{-- TOP HEADER --}}
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between px-3 sm:px-5 py-3 gap-2">

                {{-- Left: Logo / Page Title --}}
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex items-center gap-2 flex-shrink-0 min-w-0">
                        <a href="{{ route('home') }}" class="block flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center overflow-hidden border-2 border-gray-100 shadow-md bg-white">
                                <img src="{{ asset('images/logo.png') }}" alt="RescueMind" class="w-full h-full object-cover rounded-full">
                            </div>
                        </a>
                        <div class="flex flex-col justify-center min-w-0">
                            <span class="font-bold text-gray-800 text-lg leading-tight">RescueMind</span>
                            <span class="text-xs font-medium text-gray-500 truncate leading-tight">@yield('page-title', 'หน้าแรก')</span>
                        </div>
                    </div>
                </div>

                {{-- Center: Desktop Nav (all users) --}}
                <nav class="hidden lg:flex items-center gap-5 flex-1 justify-center">
                    {{-- หน้าหลัก --}}
                    <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                        <a href="{{ route('home') }}" class="flex items-center gap-1 text-sm font-medium {{ request()->routeIs('home','privacy.policy') ? 'text-red-600' : 'text-gray-600 hover:text-red-600' }} transition-colors whitespace-nowrap py-2">
                            หน้าหลัก <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div x-show="open" x-transition class="absolute top-full left-0 mt-0 w-52 bg-white border border-gray-100 rounded-xl shadow-xl py-2 z-50">
                            <a href="{{ route('privacy.policy') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600"><x-heroicon-o-shield-check class="w-5 h-5 inline-block shrink-0" /> นโยบายความเป็นส่วนตัว</a>
                        </div>
                    </div>
                    {{-- MT1 --}}
                    <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                        <a href="{{ route('mt1') }}" class="flex items-center gap-1 text-sm font-medium {{ request()->routeIs('mt1','alerts.*','relief-points.*','preparedness.*') ? 'text-red-600' : 'text-gray-600 hover:text-red-600' }} transition-colors whitespace-nowrap py-2">
                            MT1 ก่อนเกิดภัย <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div x-show="open" x-transition class="absolute top-full left-0 mt-0 w-52 bg-white border border-gray-100 rounded-xl shadow-xl py-2 z-50">
                            <p class="px-4 pt-1 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">ก่อนเกิดภัย</p>
                            <a href="{{ route('alerts.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600"><x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /> การแจ้งเตือน</a>
                            <a href="{{ route('relief-points.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600"><x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" /> จุดช่วยเหลือ</a>
                            <a href="{{ route('preparedness.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600"><x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" /> เตรียมพร้อม</a>
                        </div>
                    </div>

                    {{-- MT2 --}}
                    <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                        <a href="{{ route('mt2') }}" class="flex items-center gap-1 text-sm font-medium {{ request()->routeIs('mt2','sos.*','missing-persons.*','hazard-reports.*','family.*') ? 'text-red-600' : 'text-gray-600 hover:text-red-600' }} transition-colors whitespace-nowrap py-2">
                            MT2 ระหว่างเกิดภัย <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div x-show="open" x-transition class="absolute top-full left-0 mt-0 w-56 bg-white border border-gray-100 rounded-xl shadow-xl py-2 z-50">
                            <p class="px-4 pt-1 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">ขอความช่วยเหลือ</p>
                            <a href="{{ route('sos.create') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"><x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /> กด SOS ขอความช่วยเหลือ</a>
                            @auth<a href="{{ route('sos.my') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> ติดตาม SOS ของฉัน</a>@endauth
                            <hr class="my-1 border-gray-100">
                            <p class="px-4 pt-1 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">รายงาน & แจ้งเหตุ</p>
                            <a href="{{ route('missing-persons.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600"><x-heroicon-o-magnifying-glass class="w-5 h-5 inline-block shrink-0" /> แจ้งคนหาย</a>
                            <a href="{{ route('hazard-reports.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /> รายงานภัย</a>
                            @auth<a href="{{ route('family.status') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600"><x-heroicon-o-user-group class="w-5 h-5 inline-block shrink-0" /> ตรวจสอบความปลอดภัย</a>@endauth
                        </div>
                    </div>

                    {{-- MT3 --}}
                    <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                        <a href="{{ route('mt3') }}" class="flex items-center gap-1 text-sm font-medium {{ request()->routeIs('mt3','mental.*') ? 'text-red-600' : 'text-gray-600 hover:text-red-600' }} transition-colors whitespace-nowrap py-2">
                            MT3 หลังเกิดภัย <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div x-show="open" x-transition class="absolute top-full left-0 mt-0 w-72 bg-white border border-gray-100 rounded-xl shadow-xl py-2 z-50">
                            {{-- Section 1 --}}
                            <p class="px-4 pt-1 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">ฟื้นฟูที่อยู่อาศัยและชุมชน</p>
                            <a href="{{ route('mt3.home-recovery') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600"><x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /> ขอความช่วยเหลือฟื้นฟูบ้าน</a>
                            <a href="{{ route('mt3.community-needs') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-600"><x-heroicon-o-megaphone class="w-5 h-5 inline-block shrink-0" /> ประเมินความต้องการชุมชน</a>
                            <a href="{{ route('mt3.recovery-tracking') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600"><x-heroicon-o-clipboard-document-check class="w-5 h-5 inline-block shrink-0" /> ติดตามการฟื้นฟู</a>
                            <hr class="my-1 border-gray-100">
                            
                            {{-- Section 2 --}}
                            <p class="px-4 pt-1 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">อาสาสมัครและการบริจาค</p>
                            <a href="{{ route('mt3.volunteer') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600"><x-heroicon-o-users class="w-5 h-5 inline-block shrink-0" /> ระบบอาสาสมัครฟื้นฟู</a>
                            <a href="{{ route('mt3.donation') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600"><x-heroicon-o-gift class="w-5 h-5 inline-block shrink-0" /> ศูนย์รับบริจาค</a>

                            <hr class="my-1 border-gray-100">

                            {{-- Section 3 --}}
                            <p class="px-4 pt-1 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">ฟื้นฟูอาชีพและเศรษฐกิจ</p>
                            <a href="{{ route('mt3.livelihood') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-600"><x-heroicon-o-briefcase class="w-5 h-5 inline-block shrink-0" /> แจ้งความเสียหายและฟื้นฟูอาชีพ</a>
                            <a href="{{ route('mt3.analytics') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900"><x-heroicon-o-chart-bar class="w-5 h-5 inline-block shrink-0" /> Recovery Analytics</a>
                            <hr class="my-1 border-gray-100">

                            {{-- Section 4 --}}
                            <p class="px-4 pt-1 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">สุขภาพจิต</p>
                            <a href="{{ route('mental.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600"><x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" /> ศูนย์สุขภาพจิต</a>
                            <a href="{{ route('mental.journal.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600"><x-heroicon-o-book-open class="w-5 h-5 inline-block shrink-0" /> บันทึกความรู้สึก</a>
                            <a href="{{ route('mental.appointments.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600"><x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /> นัดหมายผู้เชี่ยวชาญ</a>
                            <a href="{{ route('mental.articles') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600"><x-heroicon-o-book-open class="w-5 h-5 inline-block shrink-0" /> บทความสุขภาพจิต</a>
                            <a href="#" onclick="event.preventDefault(); document.querySelector('[x-data=\'aiCompanion()\'] button')?.click()" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600"><span class="w-5 h-5 flex items-center justify-center shrink-0 text-base"><x-heroicon-o-cpu-chip class="w-5 h-5 inline-block mr-1 -mt-1" /></span> AI Companion</a>
                        </div>
                    </div>

                    <a href="{{ route('news.index') }}" class="text-sm font-medium {{ request()->routeIs('news.*') ? 'text-red-600' : 'text-gray-600 hover:text-red-600' }} transition-colors whitespace-nowrap"><x-heroicon-o-newspaper class="w-5 h-5 inline-block shrink-0" /> ข่าวสาร</a>
                </nav>

                {{-- Right: Auth Buttons & Weather --}}
                <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
                    {{-- Mini Weather --}}
                    <div class="flex items-center gap-1 sm:gap-1.5 px-2 py-1 bg-gray-50 border border-gray-200 rounded-full cursor-help group relative" title="กำลังระบุตำแหน่ง..." id="nav-weather-container">
                        <span id="nav-weather-icon" class="text-sm"><x-heroicon-o-sun class="w-5 h-5 inline-block mr-1 -mt-1" />️</span>
                        <span class="text-xs font-bold text-gray-700">
                            <span id="nav-weather-temp">--</span>°
                        </span>
                        <div class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 p-3 hidden group-hover:block z-[60]">
                            <div class="text-[10px] text-gray-500 mb-1" id="nav-weather-desc">กำลังโหลดข้อมูล...</div>
                            <div class="flex justify-between text-xs text-gray-700">
                                <span id="nav-weather-wind"><x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" /> --</span>
                                <span id="nav-weather-humidity"><x-heroicon-o-sparkles class="w-5 h-5 inline-block mr-1 -mt-1" /> --</span>
                            </div>
                        </div>
                    </div>

                    @auth
                    {{-- Notification Bell --}}
                    @php
                        $notifications = auth()->user()->notifications()->take(10)->get() ?? collect();
                        $hasUnread = auth()->user()->unreadNotifications()->count() > 0;
                    @endphp
                    <div class="relative" x-data="{ open: false, hasUnread: {{ $hasUnread ? 'true' : 'false' }} }" @click.outside="open=false">
                        <button @click="open=!open" class="relative p-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors">
                            <x-heroicon-o-bell class="w-6 h-6" />
                            <span x-show="hasUnread" x-cloak class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
                            </span>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-72 sm:w-80 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden" style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                                <h3 class="font-bold text-gray-800">การแจ้งเตือน</h3>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($notifications as $notification)
                                <a href="javascript:void(0)" 
                                   onclick="if(this.classList.contains('bg-blue-50/30')) { fetch('{{ route('notifications.read', $notification->id) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json'}}); let dot = this.querySelector('.red-dot'); if(dot) dot.remove(); this.classList.remove('bg-blue-50/30'); }" 
                                   class="flex items-start gap-3 p-4 hover:bg-blue-50/50 transition-colors border-b border-gray-50 relative group {{ is_null($notification->read_at) ? 'bg-blue-50/30' : '' }}">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                        <x-heroicon-o-bell class="w-6 h-6 inline-block" />
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ $notification->data['title'] ?? 'การแจ้งเตือนใหม่' }}</h4>
                                        <p class="text-[13px] text-gray-500 mt-0.5 leading-snug">{{ $notification->data['message'] ?? '' }}</p>
                                        <span class="text-[10px] font-medium text-gray-400 mt-1.5 block">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if(is_null($notification->read_at))
                                    <span class="red-dot w-2 h-2 rounded-full bg-red-500 shrink-0 mt-1.5 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                                    @endif
                                </a>
                                @empty
                                <div class="p-4 text-center text-sm text-gray-500">ไม่มีการแจ้งเตือนใหม่</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- ฉันปลอดภัย --}}
                    <form action="{{ route('family.safe') }}" method="POST" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-green-500 text-white text-xs font-semibold rounded-full hover:bg-green-600 transition-colors">
                            <x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> ฉันปลอดภัย
                        </button>
                    </form>

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open=false">
                        <button @click="open=!open" class="flex items-center gap-2 px-2 py-1.5 rounded-full hover:bg-gray-100 transition-colors">
                            <div class="w-7 h-7 bg-red-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:block text-sm text-gray-700 max-w-24 truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-52 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                                <div class="text-xs font-semibold text-red-600">{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</div>
                            </div>
                            @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer', 'volunteer']))
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><x-heroicon-o-adjustments-horizontal class="w-5 h-5 inline-block shrink-0" /> แดชบอร์ด</a>
                            @endif
                            @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" /> จัดการที่พักพิง</a>
                            @endif
                            <a href="{{ route('bookings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /> ประวัติการจอง</a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><x-heroicon-o-user class="w-5 h-5 inline-block shrink-0" /> โปรไฟล์</a>
                            @auth
                            <form action="{{ route('family.safe') }}" method="POST" class="sm:hidden">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-green-600 hover:bg-green-50"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /> ฉันปลอดภัย</button>
                            </form>
                            @endauth
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50"><x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 inline-block shrink-0" /> ออกจากระบบ</button>
                            </form>
                        </div>
                    </div>

                    {{-- Hamburger for mobile → opens right sidebar --}}
                    @if($showBottomNav)
                    <button onclick="openRSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600" title="เมนูทั้งหมด">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    @endif
                    @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-red-600 transition-colors whitespace-nowrap">เข้าสู่ระบบ</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex px-3 py-1.5 text-sm font-semibold bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm whitespace-nowrap">สมัครสมาชิก</a>
                    @endguest
                </div>
            </div>
        </header>

        {{-- MAIN --}}
        <main class="flex-1 p-3 sm:p-4 lg:p-6 {{ $showBottomNav ? 'has-bottom-nav' : '' }}">
            @if(session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: '{{ session("success") }}',
                        icon: 'success',
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'ตกลง'
                    });
                });
            </script>
            @endif
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                <span class="text-xl"><x-heroicon-o-x-circle class="w-5 h-5 inline-block shrink-0" /></span>
                <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MOBILE BOTTOM NAVIGATION (guest + regular user)
     5 tabs: หน้าแรก | MT1 | MT2 | MT3 | ข่าวสาร
     Shows on screens < lg
══════════════════════════════════════════════ --}}
@if($showBottomNav)

{{-- RIGHT SIDEBAR OVERLAY --}}
<div id="rsidebar-overlay" class="fixed inset-0 bg-black/50 z-[9998] hidden lg:hidden" onclick="closeRSidebar()"></div>

{{-- RIGHT SIDEBAR DRAWER --}}
<aside id="rsidebar" class="fixed top-0 right-0 bottom-0 z-[9999] w-72 bg-white shadow-2xl flex flex-col lg:hidden"
       style="transform: translateX(100%); transition: transform .3s cubic-bezier(.4,0,.2,1);">

    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-100 bg-gradient-to-r from-red-600 to-red-700 flex-shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center overflow-hidden shadow-md border-2 border-white/20">
                <img src="{{ asset('images/logo.png') }}" alt="RescueMind" class="w-full h-full object-cover rounded-full">
            </div>
            <div>
                <div class="text-white font-bold text-base">RescueMind</div>
                @auth<div class="text-red-100 text-xs truncate max-w-36">{{ auth()->user()->name }}</div>@endauth
            </div>
        </div>
        <button onclick="closeRSidebar()" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white hover:bg-white/30 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Menu Items --}}
    <div class="flex-1 overflow-y-auto">
        <div class="p-3 space-y-0.5 pb-24">

            {{-- MT1 --}}
            <div x-data="{ expanded: false }" class="mb-1">
                <div @click="expanded = !expanded" class="pt-3 pb-2 px-3 flex justify-between items-center cursor-pointer">
                    <span class="text-sm font-black text-blue-600 uppercase tracking-wider"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /> MT1 ก่อนเกิดภัย</span>
                    <svg class="w-4 h-4 text-blue-600 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div x-show="expanded" style="display: none;" class="space-y-0.5">
            <a href="{{ route('alerts.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('alerts.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-bell class="w-5 h-5 inline-block shrink-0" /></span><span>การแจ้งเตือน</span>
            </a>
            <a href="{{ route('relief-points.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('relief-points.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-building-office-2 class="w-5 h-5 inline-block shrink-0" /></span><span>จุดช่วยเหลือ</span>
            </a>
            <a href="{{ route('preparedness.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('preparedness.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" /></span><span>เตรียมพร้อม (Checklist)</span>
            </a>

                </div>
            </div>
            <div class="my-2 border-t border-gray-100"></div>

            {{-- MT2 --}}
            <div x-data="{ expanded: false }" class="mb-1">
                <div @click="expanded = !expanded" class="pt-3 pb-2 px-3 flex justify-between items-center cursor-pointer">
                    <span class="text-sm font-black text-red-600 uppercase tracking-wider"><x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /> MT2 ระหว่างเกิดภัย</span>
                    <svg class="w-4 h-4 text-red-600 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div x-show="expanded" style="display: none;" class="space-y-0.5">
            <a href="{{ route('sos.create') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base font-bold bg-red-600 text-white hover:bg-red-700 transition-colors shadow-sm">
                <span class="text-base w-6 text-center"><x-heroicon-o-lifebuoy class="w-5 h-5 inline-block shrink-0" /></span><span>กด SOS ขอความช่วยเหลือ</span>
            </a>
            @auth
            <a href="{{ route('sos.my') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('sos.my','sos.show') ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-map-pin class="w-5 h-5 inline-block shrink-0" /></span><span>ติดตาม SOS ของฉัน</span>
            </a>
            @endauth
            <a href="{{ route('missing-persons.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('missing-persons.*') ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-magnifying-glass class="w-5 h-5 inline-block shrink-0" /></span><span>แจ้งคนหาย</span>
            </a>
            <a href="{{ route('hazard-reports.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('hazard-reports.*') ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /></span><span>รายงานภัย</span>
            </a>
            @auth
            <a href="{{ route('family.status') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('family.*') ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-user-group class="w-5 h-5 inline-block shrink-0" /></span><span>ตรวจสอบความปลอดภัย</span>
            </a>
            @endauth

                </div>
            </div>
            <div class="my-2 border-t border-gray-100"></div>

            {{-- MT3 --}}
            <div x-data="{ expanded: false }" class="mb-1">
                <div @click="expanded = !expanded" class="pt-3 pb-2 px-3 flex justify-between items-center cursor-pointer">
                    <span class="text-sm font-black text-purple-600 uppercase tracking-wider"><x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" /> MT3 หลังเกิดภัย</span>
                    <svg class="w-4 h-4 text-purple-600 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div x-show="expanded" style="display: none;" class="space-y-0.5">
            <p class="px-4 pt-3 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">ฟื้นฟูที่อยู่อาศัยและชุมชน</p>
            <a href="{{ route('mt3.home-recovery') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /></span><span>ขอความช่วยเหลือฟื้นฟูบ้าน</span>
            </a>
            <a href="{{ route('mt3.community-needs') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-megaphone class="w-5 h-5 inline-block shrink-0" /></span><span>ประเมินความต้องการชุมชน</span>
            </a>
            <a href="{{ route('mt3.recovery-tracking') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-clipboard-document-check class="w-5 h-5 inline-block shrink-0" /></span><span>ติดตามการฟื้นฟู</span>
            </a>

            <p class="px-4 pt-3 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">อาสาสมัครและการบริจาค</p>
            <a href="{{ route('mt3.volunteer') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-users class="w-5 h-5 inline-block shrink-0" /></span><span>ระบบอาสาสมัครฟื้นฟู</span>
            </a>
            <a href="{{ route('mt3.donation') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-gift class="w-5 h-5 inline-block shrink-0" /></span><span>ศูนย์รับบริจาค</span>
            </a>


            <p class="px-4 pt-3 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">ฟื้นฟูอาชีพและเศรษฐกิจ</p>
            <a href="{{ route('mt3.livelihood') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-briefcase class="w-5 h-5 inline-block shrink-0" /></span><span>แจ้งความเสียหายและฟื้นฟูอาชีพ</span>
            </a>
            <a href="{{ route('mt3.analytics') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-gray-50 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-chart-bar class="w-5 h-5 inline-block shrink-0" /></span><span>Recovery Analytics</span>
            </a>

            <p class="px-4 pt-3 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">สุขภาพจิต</p>
            <a href="{{ route('mental.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('mental.index','mental.assess.*') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-600' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-s-sparkles class="w-5 h-5 inline-block shrink-0" /></span><span>ศูนย์สุขภาพจิต</span>
            </a>
            <a href="{{ route('mental.journal.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('mental.journal.*') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-600' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-book-open class="w-5 h-5 inline-block shrink-0" /></span><span>บันทึกความรู้สึก</span>
            </a>
            <a href="{{ route('mental.appointments.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('mental.appointments.*') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-600' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-calendar-days class="w-5 h-5 inline-block shrink-0" /></span><span>นัดหมายผู้เชี่ยวชาญ</span>
            </a>
            <a href="{{ route('mental.articles') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-book-open class="w-5 h-5 inline-block shrink-0" /></span><span>บทความสุขภาพจิต</span>
            </a>
            <a href="#" onclick="event.preventDefault(); closeRSidebar(); document.querySelector('[x-data=\'aiCompanion()\'] button')?.click()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-cpu-chip class="w-5 h-5 inline-block mr-1 -mt-1" /></span><span>AI Companion</span>
            </a>

                </div>
            </div>
            <div class="my-2 border-t border-gray-100"></div>

            {{-- Policy --}}
            <div x-data="{ expanded: false }" class="mb-1">
                <div @click="expanded = !expanded" class="pt-3 pb-2 px-3 flex justify-between items-center cursor-pointer">
                    <span class="text-sm font-black text-blue-500 uppercase tracking-wider"><x-heroicon-o-shield-check class="w-5 h-5 inline-block shrink-0" /> นโยบายความเป็นส่วนตัว</span>
                    <svg class="w-4 h-4 text-blue-600 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div x-show="expanded" style="display: none;" class="space-y-0.5">
            <a href="{{ route('privacy.policy') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('privacy.policy') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-document-text class="w-5 h-5 inline-block mr-1 -mt-1" /></span><span>Privacy Policy</span>
            </a>

                </div>
            </div>
            <div class="my-2 border-t border-gray-100"></div>

            {{-- Other --}}
            <div x-data="{ expanded: false }" class="mb-1">
                <div @click="expanded = !expanded" class="pt-3 pb-2 px-3 flex justify-between items-center cursor-pointer">
                    <span class="text-sm font-black text-gray-400 uppercase tracking-wider"><x-heroicon-o-globe-alt class="w-5 h-5 inline-block shrink-0" /> อื่นๆ</span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div x-show="expanded" style="display: none;" class="space-y-0.5">
            <a href="{{ route('news.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base {{ request()->routeIs('news.*') ? 'bg-gray-100 text-gray-800 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-newspaper class="w-5 h-5 inline-block shrink-0" /></span><span>ข่าวสาร & ประกาศ</span>
            </a>
            @auth
            @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
            <a href="{{ route('admin.bookings.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-gray-50 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-clipboard-document-list class="w-5 h-5 inline-block shrink-0" /></span><span>จัดการที่พักพิง</span>
            </a>
            @endif
            <a href="{{ route('bookings.index') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-gray-50 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-home-modern class="w-5 h-5 inline-block shrink-0" /></span><span>ประวัติการจองที่พักพิง</span>
            </a>
            <a href="{{ route('profile.edit') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-gray-50 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-user class="w-5 h-5 inline-block shrink-0" /></span><span>โปรไฟล์ของฉัน</span>
            </a>
            @else
            <a href="{{ route('login') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base text-gray-700 hover:bg-gray-50 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-key class="w-5 h-5 inline-block shrink-0" /></span><span>เข้าสู่ระบบ</span>
            </a>
            <a href="{{ route('register') }}" onclick="closeRSidebar()" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base font-bold bg-red-600 text-white hover:bg-red-700 transition-colors">
                <span class="text-base w-6 text-center"><x-heroicon-o-sparkles class="w-5 h-5 inline-block shrink-0" /></span><span>สมัครสมาชิก</span>
            </a>
            @endguest
                </div>
            </div>

        </div>
    </div>

    @auth
    <div class="p-4 border-t border-gray-100 bg-white">
        <form action="{{ route('family.safe') }}" method="POST" class="mb-2">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-base font-medium bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                <span class="text-lg w-6 text-center"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></span><span>แจ้งว่าฉันปลอดภัย</span>
            </button>
        </form>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-base font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                <span class="text-lg w-6 text-center"><x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 inline-block shrink-0" /></span><span>ออกจากระบบ</span>
            </button>
        </form>
    </div>
    @endauth
</aside>

{{-- MOBILE BOTTOM NAV — 5 TABS --}}
<nav class="bottom-nav fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-lg lg:hidden">
    <div class="flex items-stretch h-[58px]">

        {{-- Tab: หน้าแรก --}}
        <a href="{{ route('home') }}"
           class="flex-1 flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold transition-colors
                  {{ request()->routeIs('home') ? 'text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>หน้าแรก</span>
        </a>

        {{-- Tab: MT1 --}}
        <a href="{{ route('mt1') }}"
           class="flex-1 flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold transition-colors
                  {{ request()->routeIs('mt1','alerts.*','relief-points.*','preparedness.*') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
            <span class="relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </span>
            <span class="{{ request()->routeIs('mt1','alerts.*','relief-points.*','preparedness.*') ? 'text-blue-600 font-bold' : '' }}">MT1</span>
        </a>

        {{-- Tab: MT2 --}}
        <a href="{{ route('mt2') }}"
           class="flex-1 flex flex-col items-center justify-center gap-0.5 text-[10px] font-bold transition-colors
                  {{ request()->routeIs('mt2','sos.*','missing-persons.*','hazard-reports.*','family.*') ? 'text-red-700' : 'text-red-500 hover:text-red-600' }} relative">
            <div class="w-11 h-11 bg-red-600 rounded-full flex items-center justify-center shadow-lg -mt-5 border-4 border-white pulse-sos">
                <span class="text-white text-[11px] font-black leading-none">MT2</span>
            </div>
            <span class="text-red-500 font-bold -mt-0.5">ฉุกเฉิน</span>
        </a>

        {{-- Tab: MT3 --}}
        <a href="{{ route('mt3') }}"
           class="flex-1 flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold transition-colors
                  {{ request()->routeIs('mt3','mental.*') ? 'text-purple-600' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <span class="{{ request()->routeIs('mt3','mental.*') ? 'text-purple-600 font-bold' : '' }}">MT3</span>
        </a>

        {{-- Tab: ข่าวสาร --}}
        <a href="{{ route('news.index') }}"
           class="flex-1 flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold transition-colors
                  {{ request()->routeIs('news.*') ? 'text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('news.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <span>ข่าวสาร</span>
        </a>

    </div>
</nav>

<script>
function openRSidebar(){
    document.getElementById('rsidebar').style.transform = 'translateX(0)';
    document.getElementById('rsidebar-overlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeRSidebar(){
    document.getElementById('rsidebar').style.transform = 'translateX(100%)';
    document.getElementById('rsidebar-overlay').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
@endif

{{-- AI Companion --}}
@if(!request()->routeIs('mt3.ai-companion'))
    @include('components.ai-companion')
@endif


<script>
    // Fetch weather for public navbar
    function fetchAppWeather() {
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
                    const navTemp = document.getElementById('nav-weather-temp');
                    if(navTemp) navTemp.textContent = Math.round(c.temperature_2m);
                    
                    const navWind = document.getElementById('nav-weather-wind');
                    if(navWind) navWind.innerHTML = `<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" /> ${Math.round(c.wind_speed_10m)} km/h`;
                    
                    const navHum = document.getElementById('nav-weather-humidity');
                    if(navHum) navHum.innerHTML = `<x-heroicon-o-sparkles class="w-5 h-5 inline-block mr-1 -mt-1" /> ${c.relative_humidity_2m}%`;
                    
                    const wcodes = {
                        0: {d:'ฟ้าโปร่ง', i:`<x-heroicon-o-sun class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 1: {d:'ส่วนใหญ่โปร่ง', i:`<x-heroicon-o-sun class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 2: {d:'มีเมฆบางส่วน', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />`}, 3: {d:'มีเมฆมาก', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`},
                        45: {d:'หมอก', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 48: {d:'หมอก', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`},
                        51: {d:'ฝนละออง', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 53: {d:'ฝนละออง', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 55: {d:'ฝนละออง', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`},
                        61: {d:'ฝนตกเบา', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 63: {d:'ฝนตกปานกลาง', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 65: {d:'ฝนตกหนัก', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 
                        80: {d:'ฝนเป็นพัก', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 81: {d:'ฝนเป็นพัก', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 82: {d:'ฝนเป็นพัก', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`},
                        95: {d:'พายุฟ้าคะนอง', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 96: {d:'พายุฟ้าคะนอง', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}, 99: {d:'พายุฟ้าคะนอง', i:`<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`}
                    };
                    const info = wcodes[c.weather_code] || {d: 'ฝนตก/เมฆมาก', i: `<x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️`};
                    const navIcon = document.getElementById('nav-weather-icon');
                    if(navIcon) navIcon.innerHTML = info.i;
                    const navDesc = document.getElementById('nav-weather-desc');
                    if(navDesc) navDesc.textContent = info.d;
                })
                .catch(() => {
                    const navDesc = document.getElementById('nav-weather-desc');
                    if(navDesc) navDesc.textContent = "โหลดไม่สำเร็จ";
                });
        };
        
        doFetch();
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => { 
                    if(lat !== pos.coords.latitude || lon !== pos.coords.longitude) {
                        lat = pos.coords.latitude; 
                        lon = pos.coords.longitude;
                        localStorage.setItem('rm_lat', lat);
                        localStorage.setItem('rm_lon', lon);
                        doFetch();
                    }
                },
                () => { console.log("Location access denied."); }
            );
        }
    }
    
    document.addEventListener("DOMContentLoaded", fetchAppWeather);
</script>

@stack('scripts')
</body>
</html>
