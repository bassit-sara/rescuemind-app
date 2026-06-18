@extends(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer']) ? 'layouts.admin' : 'layouts.app')

@section('title', 'RescueMind — ระบบบริหารจัดการภัยพิบัติและฟื้นฟูสุขภาพจิต')

@section('page-title', 'หน้าแรก')

@section('content')

    {{-- Hero Section --}}
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 via-red-950 to-gray-900 mb-8 text-white">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-64 h-64 bg-red-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-80 h-80 bg-orange-500 rounded-full blur-3xl"></div>
        </div>
        <div class="relative px-8 py-12 lg:px-16 lg:py-20">
            <div class="max-w-4xl w-full">
                <div
                    class="inline-flex flex-wrap items-center justify-center gap-1 sm:gap-2 px-3 py-1.5 bg-red-600/30 border border-red-500/50 rounded-2xl sm:rounded-full text-red-300 text-xs sm:text-sm mb-6 text-center max-w-full">
                    <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse flex-shrink-0"></span>
                    <span>National Disaster Management & Mental Resilience Platform</span>
                </div>
                <h1 class="text-4xl lg:text-6xl font-bold mb-4 leading-tight break-words">
                    <span class="text-white">Rescue</span><span class="text-red-400">Mind</span>
                </h1>
                <p class="text-gray-300 text-lg lg:text-xl mb-8 max-w-2xl">
                    แพลตฟอร์มบริหารจัดการภัยพิบัติและฟื้นฟูสุขภาพจิตแบบครบวงจร<br>
                    ครอบคลุมตั้งแต่ก่อนเกิดภัย ระหว่างเกิดภัย และหลังเกิดภัย
                </p>
                <div class="flex flex-wrap gap-4">
                    @auth
                        <a href="{{ route('sos.create') }}"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl text-lg transition-all transform hover:scale-105 pulse-sos">
                            🆘 กด SOS ขอความช่วยเหลือ
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-2xl text-lg transition-all backdrop-blur-sm border border-white/20">
                            📊 ไปยัง Dashboard
                        </a>
                    @else
                        <a href="{{ route('sos.create') }}"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl text-lg transition-all transform hover:scale-105 pulse-sos">
                            🆘 กด SOS ขอความช่วยเหลือ
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-2xl text-lg transition-all backdrop-blur-sm border border-white/20">
                            สมัครสมาชิกฟรี →
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Top Widgets Section --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
        {{-- Weather (Spans 2 columns on tablet/desktop) --}}
        <div class="md:col-span-2">
            <x-weather-widget />
        </div>
        
        {{-- Side Info Cards --}}
        <div class="space-y-6 flex flex-col">
            {{-- Emergency Hotlines --}}
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">☎️ สายด่วนฉุกเฉิน</h3>
                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">โทรฟรี 24 ชม.</span>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-red-50 transition-colors cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-red-500 group-hover:text-red-600">🚑</div>
                            <div>
                                <div class="font-bold text-gray-800 text-lg leading-none">1669</div>
                                <div class="text-xs text-gray-500">เจ็บป่วยฉุกเฉิน</div>
                            </div>
                        </div>
                        <a href="tel:1669" class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600 opacity-0 group-hover:opacity-100 transition-opacity">📞</a>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-orange-50 transition-colors cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-orange-500 group-hover:text-orange-600">🚒</div>
                            <div>
                                <div class="font-bold text-gray-800 text-lg leading-none">199</div>
                                <div class="text-xs text-gray-500">แจ้งเหตุไฟไหม้/สัตว์ร้าย</div>
                            </div>
                        </div>
                        <a href="tel:199" class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 opacity-0 group-hover:opacity-100 transition-opacity">📞</a>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-colors cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-500 group-hover:text-blue-600">🚓</div>
                            <div>
                                <div class="font-bold text-gray-800 text-lg leading-none">191</div>
                                <div class="text-xs text-gray-500">เหตุด่วนเหตุร้าย</div>
                            </div>
                        </div>
                        <a href="tel:191" class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity">📞</a>
                    </div>
                </div>
            </div>

            {{-- Daily Tip --}}
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-[2rem] p-6 border border-blue-100 shadow-sm flex-shrink-0">
                <div class="flex items-start gap-4">
                    <div class="text-3xl">💡</div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-800 mb-1">เกร็ดความรู้ประจำวัน</h3>
                        <p class="text-xs text-blue-600/80 leading-relaxed font-medium">เตรียม "กระเป๋าฉุกเฉิน" ให้พร้อมเสมอ ใส่เอกสารสำคัญ ยาสามัญ น้ำดื่ม และไฟฉาย ไว้ในที่หยิบจับง่าย</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Alerts Banner --}}
    @if($alerts->count() > 0)
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                การแจ้งเตือนที่ใช้งานอยู่
            </h2>
            <div class="space-y-2">
                @foreach($alerts as $alert)
                    <a href="{{ route('alerts.show', $alert) }}"
                        class="block rounded-xl p-4 transition-all hover:shadow-md
                        {{ $alert->level == 3 ? 'bg-red-50 border-l-4 border-red-500' : ($alert->level == 2 ? 'bg-orange-50 border-l-4 border-orange-500' : 'bg-yellow-50 border-l-4 border-yellow-500') }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">{{ $alert->level == 3 ? '🔴' : ($alert->level == 2 ? '🟠' : '🟡') }}</span>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $alert->title }}</div>
                                    <div class="text-sm text-gray-600">{{ $alert->province ?? 'ทั่วประเทศ' }} •
                                        {{ $alert->disaster_label }}</div>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 text-xs font-bold rounded-full
                                {{ $alert->level == 3 ? 'bg-red-100 text-red-700' : ($alert->level == 2 ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700') }}">
                                ระดับ {{ $alert->level }}: {{ $alert->level_label }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- SOS Total --}}
        <div class="relative bg-white rounded-2xl p-5 shadow-sm border border-gray-100 overflow-hidden">
            <div class="absolute -right-2 -bottom-2 opacity-20 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-24 h-24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="relative z-10">
                <div class="text-3xl font-black text-gray-800">{{ number_format($totalSos) }}</div>
                <div class="text-sm font-medium text-gray-500 mt-1">SOS ทั้งหมด</div>
            </div>
        </div>

        {{-- Resolved SOS --}}
        <div class="relative bg-white rounded-2xl p-5 shadow-sm border border-gray-100 overflow-hidden">
            <div class="absolute -right-2 -bottom-2 opacity-20 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-24 h-24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="relative z-10">
                <div class="text-3xl font-black text-green-600">{{ number_format($resolvedSos) }}</div>
                <div class="text-sm font-medium text-gray-500 mt-1">ช่วยเหลือสำเร็จ</div>
            </div>
        </div>

        {{-- Relief Points --}}
        <div class="relative bg-white rounded-2xl p-5 shadow-sm border border-gray-100 overflow-hidden">
            <div class="absolute -right-2 -bottom-2 opacity-20 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-24 h-24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <div class="relative z-10">
                <div class="text-3xl font-black text-blue-600">{{ number_format($reliefPoints) }}</div>
                <div class="text-sm font-medium text-gray-500 mt-1">จุดช่วยเหลือ</div>
            </div>
        </div>

        {{-- Alerts --}}
        <div class="relative bg-white rounded-2xl p-5 shadow-sm border border-gray-100 overflow-hidden">
            <div class="absolute -right-2 -bottom-2 opacity-20 text-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-24 h-24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>
            <div class="relative z-10">
                <div class="text-3xl font-black text-orange-600">{{ $alerts->count() }}</div>
                <div class="text-sm font-medium text-gray-500 mt-1">การแจ้งเตือน</div>
            </div>
        </div>
    </div>

    {{-- 4 Dimensions --}}
    <h2 class="text-xl font-bold text-gray-800 mb-4">ระบบ 4 มิติ</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

        {{-- Dimension 1 --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white">
            <div class="text-4xl mb-3">🌊</div>
            <h3 class="text-xl font-bold mb-2">Early Warning & Preparedness</h3>
            <p class="text-blue-100 text-sm mb-4">แจ้งเตือนภัยล่วงหน้า พื้นที่เสี่ยง จุดอพยพ และการเตรียมพร้อม</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('alerts.index') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">🚨
                    การแจ้งเตือน</a>
                <a href="{{ route('relief-points.index') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">🏥
                    จุดช่วยเหลือ</a>
                <a href="{{ route('preparedness.index') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">📋 Checklist</a>
            </div>
        </div>

        {{-- Dimension 2 --}}
        <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl p-6 text-white">
            <div class="text-4xl mb-3">🆘</div>
            <h3 class="text-xl font-bold mb-2">Emergency Response</h3>
            <p class="text-red-100 text-sm mb-4">ขอความช่วยเหลือฉุกเฉิน ติดตาม SOS รายงานภัย และแจ้งคนหาย</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('sos.create') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">🆘 กด SOS</a>
                <a href="{{ route('missing-persons.index') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">🔍 คนหาย</a>
                <a href="{{ route('hazard-reports.index') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">⚠️ รายงานภัย</a>
            </div>
        </div>

        {{-- Dimension 3 --}}
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white">
            <div class="text-4xl mb-3">🧠</div>
            <h3 class="text-xl font-bold mb-2">Recovery & Mental Health</h3>
            <p class="text-purple-100 text-sm mb-4">ประเมินสุขภาพจิต ติดตามอารมณ์ บันทึก Journal และนัดหมายผู้เชี่ยวชาญ</p>
            <div class="flex flex-wrap gap-2">
                @auth
                    <a href="{{ route('mental.assess.create', 'phq9') }}"
                        class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">📊 PHQ-9</a>
                    <a href="{{ route('mental.mood.index') }}"
                        class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">😊 Mood</a>
                    <a href="{{ route('mental.journal.index') }}"
                        class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">📔 Journal</a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">เข้าสู่ระบบเพื่อใช้งาน</a>
                @endauth
            </div>
        </div>

        {{-- Dimension 4 --}}
        <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl p-6 text-white">
            <div class="text-4xl mb-3">📡</div>
            <h3 class="text-xl font-bold mb-2">Command & Analytics</h3>
            <p class="text-orange-100 text-sm mb-4">ศูนย์บัญชาการ Dashboard วิเคราะห์ KPI และติดตามทรัพยากรแบบ Real-time</p>
            <div class="flex flex-wrap gap-2">
                @hasrole('officer|admin|super_admin')
                <a href="{{ route('officer.dashboard') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">👮 Officer</a>
                @endhasrole
                @hasrole('admin|super_admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">⚙️ Admin</a>
                @endhasrole
                @hasrole('super_admin')
                <a href="{{ route('super-admin.dashboard') }}"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">🌐 Super
                    Admin</a>
                @endhasrole
                @guest
                    <span class="px-3 py-1.5 bg-white/10 rounded-lg text-sm text-white/70">สำหรับเจ้าหน้าที่เท่านั้น</span>
                @endguest
            </div>
        </div>
    </div>


@endsection