{{-- Weather Widget — Premium Mobile UI (Soft Dark Theme) --}}
<div id="weather-widget" class="bg-gradient-to-br from-[#2b2c30] to-[#1e1e24] rounded-[2rem] text-white p-6 font-sans shadow-xl relative overflow-hidden ring-1 ring-white/5 border-t border-white/10 w-full h-full flex flex-col justify-between">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 relative z-10">
        <div class="flex items-center gap-3">
            <h2 class="text-lg font-medium text-gray-100 tracking-wide">สภาพอากาศ</h2>
        </div>
        <div class="flex items-center gap-2 relative">
            <button onclick="addWeatherArea()" class="text-gray-400 hover:text-white transition-colors p-1 rounded-full hover:bg-white/10" title="เพิ่มสภาพอากาศในพื้นที่อื่น">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <button onclick="toggleWeatherOptions(event)" class="text-gray-400 hover:text-white transition-colors p-1 rounded-full hover:bg-white/10" title="ตัวเลือกเพิ่มเติม">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
            </button>
            
            <div id="weather-options-dropdown" class="absolute right-0 top-full mt-2 w-48 bg-[#383a40] border border-white/10 rounded-xl shadow-2xl py-1 z-[60] overflow-hidden opacity-0 pointer-events-none transition-all duration-200 transform scale-95 origin-top-right">
                <button type="button" onclick="triggerWeatherRefresh()" class="w-full text-left px-4 py-2.5 text-sm text-gray-200 hover:bg-white/10 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    รีเฟรชข้อมูล
                </button>
                <button type="button" onclick="triggerWeatherAreaPrompt()" class="w-full text-left px-4 py-2.5 text-sm text-gray-200 hover:bg-white/10 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    เปลี่ยนพื้นที่
                </button>
                <div class="my-1 border-t border-white/10"></div>
                <button type="button" onclick="triggerWeatherSettings()" class="w-full text-left px-4 py-2.5 text-sm text-gray-400 hover:text-gray-200 hover:bg-white/10 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    ตั้งค่า
                </button>
            </div>
        </div>
    </div>

    {{-- Loading State --}}
    <div id="weather-loading" class="flex items-center justify-center py-10 relative z-10">
        <div class="inline-block w-8 h-8 border-3 border-gray-600 border-t-white rounded-full animate-spin"></div>
    </div>

    {{-- Error State --}}
    <div id="weather-error" class="hidden text-center py-10 relative z-10">
        <div class="text-3xl mb-2"><x-heroicon-o-exclamation-triangle class="w-5 h-5 inline-block shrink-0" /></div>
        <p class="text-gray-400 text-sm font-medium">ไม่สามารถโหลดข้อมูลได้</p>
        <button onclick="refreshWeather()" class="mt-3 px-4 py-1.5 bg-white/10 rounded-full text-xs hover:bg-white/20 transition-colors">ลองอีกครั้ง</button>
    </div>

    {{-- Weather Content --}}
    <div id="weather-content" class="hidden relative z-10">
        {{-- Next Forecast Banner --}}
        <div class="bg-white/5 hover:bg-white/10 transition-colors rounded-2xl px-5 py-3.5 flex items-center gap-3 mb-6 shadow-sm border border-white/5">
            <span class="text-xl" id="weather-forecast-icon"><x-heroicon-o-cloud class="w-5 h-5 inline-block mr-1 -mt-1" />️</span>
            <span id="weather-next-forecast" class="text-[15px] font-medium text-gray-200 tracking-tight">กำลังประมวลผล...</span>
        </div>

        {{-- Current Weather --}}
        <div class="flex justify-between items-end mb-6 px-1">
            <div>
                <div class="text-[13px] text-gray-400 mb-1.5 font-medium tracking-wide">ตอนนี้</div>
                <div class="flex items-start">
                    <span id="weather-temp" class="text-[5.5rem] font-light leading-[0.8] tracking-tighter text-gray-50">--</span>
                    <span class="text-[2.5rem] leading-none relative -top-2 font-light text-gray-300">°</span>
                    <span id="weather-icon" class="text-[3.5rem] ml-3 drop-shadow-lg self-center leading-none">--</span>
                </div>
            </div>
            <div class="text-right pb-1">
                <div id="weather-desc" class="text-[1.1rem] font-medium mb-1.5 text-gray-200">--</div>
                <div class="text-[13px] text-gray-400 tracking-wide">รู้สึกเหมือน <span id="weather-feels">--</span>°</div>
            </div>
        </div>

        {{-- Location / Alert Banner --}}
        <div class="bg-[#383a40]/60 backdrop-blur-md rounded-2xl px-2 py-2 flex items-center justify-between text-[14px] mb-8 border border-white/5 cursor-pointer hover:bg-white/10 transition-colors shadow-inner">
            <div class="flex items-center gap-3 max-w-[90%]">
                <div class="bg-red-400/20 rounded-full p-1.5 flex-shrink-0">
                    <span class="bg-red-400 text-white rounded-full w-4 h-4 flex items-center justify-center text-[9px]"><x-heroicon-o-sun class="w-5 h-5 inline-block mr-1 -mt-1" />️</span>
                </div>
                <span class="text-gray-300 truncate font-medium" id="weather-location">กำลังระบุตำแหน่ง...</span>
            </div>
            <svg class="w-4 h-4 text-gray-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>

        {{-- Hourly Forecast --}}
        <div id="weather-hourly" class="flex gap-5 overflow-x-auto pb-4 pt-1 scrollbar-hide text-center mb-6 snap-x relative z-10 mask-edges">
            <!-- JS fills this -->
        </div>

        {{-- Bottom Daily Cards --}}
        <div id="weather-daily" class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide snap-x relative z-10 pt-2 mask-edges">
            <!-- JS fills this -->
        </div>
    </div>

    {{-- Decorative Background (simulating the cute frog background) --}}
    <div class="absolute bottom-0 left-0 right-0 h-48 pointer-events-none opacity-40 z-0 bg-gradient-to-t from-[#2f3542] to-transparent"></div>
</div>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar-hide::-webkit-scrollbar { display: none; }
/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
/* Gradient mask for smooth horizontal scrolling edges */
.mask-edges {
    -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
    mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
}
</style>

<script>
(function() {
    const ICONS = {
        sun: `<svg class="w-[1em] h-[1em] inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>`,
        cloudSun: `<svg class="w-[1em] h-[1em] inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="M20 12h2"/><path d="m19.07 4.93-1.41 1.41"/><path d="M15.947 12.65a4 4 0 0 0-5.925-4.128"/><path d="M13 22H7a5 5 0 1 1 4.9-6H13a3 3 0 0 1 0 6Z"/></svg>`,
        cloud: `<svg class="w-[1em] h-[1em] inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>`,
        drizzle: `<svg class="w-[1em] h-[1em] inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M8 19v2"/><path d="M8 13v2"/><path d="M16 19v2"/><path d="M16 13v2"/><path d="M12 21v2"/><path d="M12 15v2"/></svg>`,
        rain: `<svg class="w-[1em] h-[1em] inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M16 14v6"/><path d="M8 14v6"/><path d="M12 16v6"/></svg>`,
        storm: `<svg class="w-[1em] h-[1em] inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 16.326A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 .5 8.973"/><path d="m13 12-3 5h4l-3 5"/></svg>`,
        snow: `<svg class="w-[1em] h-[1em] inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><line x1="12" x2="12" y1="2" y2="22"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4 4 4"/></svg>`,
        fog: `<svg class="w-[1em] h-[1em] inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M16 17H4"/><path d="M16 21H4"/></svg>`
    };

    const WMO_CODES = {
        0:  { desc: 'ฟ้าโปร่ง', icon: ICONS.sun },
        1:  { desc: 'ส่วนใหญ่โปร่ง', icon: ICONS.sun },
        2:  { desc: 'มีเมฆบางส่วน', icon: ICONS.cloudSun },
        3:  { desc: 'มีเมฆมาก', icon: ICONS.cloud },
        45: { desc: 'หมอก', icon: ICONS.fog },
        48: { desc: 'หมอกเยือกแข็ง', icon: ICONS.fog },
        51: { desc: 'ฝนละออง เบา', icon: ICONS.drizzle },
        53: { desc: 'ฝนละออง ปานกลาง', icon: ICONS.drizzle },
        55: { desc: 'ฝนละออง หนัก', icon: ICONS.drizzle },
        56: { desc: 'ฝนละอองเยือกแข็ง', icon: ICONS.drizzle },
        57: { desc: 'ฝนละอองเยือกแข็งหนัก', icon: ICONS.drizzle },
        61: { desc: 'ฝนตก เบา', icon: ICONS.rain },
        63: { desc: 'ฝนตก ปานกลาง', icon: ICONS.rain },
        65: { desc: 'ฝนตก หนัก', icon: ICONS.rain },
        66: { desc: 'ฝนเยือกแข็ง', icon: ICONS.snow },
        67: { desc: 'ฝนเยือกแข็งหนัก', icon: ICONS.snow },
        71: { desc: 'หิมะตก เบา', icon: ICONS.snow },
        73: { desc: 'หิมะตก ปานกลาง', icon: ICONS.snow },
        75: { desc: 'หิมะตก หนัก', icon: ICONS.snow },
        77: { desc: 'เกล็ดหิมะ', icon: ICONS.snow },
        80: { desc: 'ฝนตกเป็นพัก เบา', icon: ICONS.rain },
        81: { desc: 'ฝนตกเป็นพัก ปานกลาง', icon: ICONS.rain },
        82: { desc: 'ฝนตกเป็นพัก หนัก', icon: ICONS.rain },
        85: { desc: 'หิมะตกเป็นพัก', icon: ICONS.snow },
        86: { desc: 'หิมะตกหนักเป็นพัก', icon: ICONS.snow },
        95: { desc: 'พายุฝนฟ้าคะนอง', icon: ICONS.storm },
        96: { desc: 'พายุฝนฟ้าคะนอง+ลูกเห็บ', icon: ICONS.storm },
        99: { desc: 'พายุฝนฟ้าคะนอง+ลูกเห็บหนัก', icon: ICONS.storm },
    };

    function getWeatherInfo(code) {
        return WMO_CODES[code] || { desc: 'ไม่ทราบ', icon: ICONS.cloud };
    }

    let lat = 6.5411; // Default to Yala for demo matching the user's screenshot
    let lon = 101.2804;

    window.refreshWeather = function() {
        document.getElementById('weather-loading').classList.remove('hidden');
        document.getElementById('weather-content').classList.add('hidden');
        document.getElementById('weather-error').classList.add('hidden');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => { lat = pos.coords.latitude; lon = pos.coords.longitude; fetchWeather(); },
                () => { fetchWeather(); },
                { timeout: 5000 }
            );
        } else {
            fetchWeather();
        }
    };

    function fetchWeather() {
        // Fetch 7 days daily, hourly precipitation_probability, apparent_temperature
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,apparent_temperature,weather_code&hourly=temperature_2m,precipitation_probability,weather_code&daily=temperature_2m_max,temperature_2m_min,weather_code&timezone=Asia%2FBangkok&forecast_days=7`;

        fetch(url)
            .then(r => { if (!r.ok) throw new Error('API error'); return r.json(); })
            .then(data => renderWeather(data))
            .catch(() => {
                document.getElementById('weather-loading').classList.add('hidden');
                document.getElementById('weather-error').classList.remove('hidden');
            });
    }

    function renderWeather(data) {
        const current = data.current;
        const hourly = data.hourly;
        const daily = data.daily;
        const info = getWeatherInfo(current.weather_code);

        // Update Current
        document.getElementById('weather-temp').textContent = Math.round(current.temperature_2m);
        document.getElementById('weather-icon').innerHTML = info.icon;
        document.getElementById('weather-desc').textContent = info.desc;
        document.getElementById('weather-feels').textContent = Math.round(current.apparent_temperature);

        // Also update navbar if exists
        if(document.getElementById('nav-weather-temp')) {
            document.getElementById('nav-weather-temp').textContent = Math.round(current.temperature_2m);
            document.getElementById('nav-weather-icon').innerHTML = info.icon;
        }

        // Determine Next Forecast (find next hour with >30% rain chance)
        let nextRainText = 'คาดว่าสภาพอากาศจะปลอดโปร่ง';
        let nextRainIcon = ICONS.sun;
        
        function getLocalIsoHour() {
            const now = new Date();
            return now.getFullYear() + '-' + 
                   String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                   String(now.getDate()).padStart(2, '0') + 'T' + 
                   String(now.getHours()).padStart(2, '0') + ':00';
        }

        if (hourly && hourly.time) {
            const currentHourStr = getLocalIsoHour();
            let startIndex = hourly.time.findIndex(t => t >= currentHourStr);
            if (startIndex === -1) startIndex = 0;
            
            for (let i = startIndex; i < startIndex + 12; i++) {
                if (hourly.precipitation_probability[i] >= 30) {
                    const rainTime = new Date(hourly.time[i]);
                    const hourText = rainTime.getHours().toString().padStart(2, '0') + ':00';
                    nextRainText = `คาดว่าจะมีฝนตกช่วง ${hourText}`;
                    nextRainIcon = ICONS.rain;
                    break;
                }
            }
        }
        document.getElementById('weather-next-forecast').textContent = nextRainText;
        document.getElementById('weather-forecast-icon').innerHTML = nextRainIcon;

        // Render Hourly
        const hourlyContainer = document.getElementById('weather-hourly');
        if (hourly && hourly.time) {
            let hHtml = '';
            const currentHourStr = getLocalIsoHour();
            let startIndex = hourly.time.findIndex(t => t >= currentHourStr);
            if (startIndex === -1) startIndex = 0;

            for (let i = startIndex; i < startIndex + 24; i++) {
                const tDate = new Date(hourly.time[i]);
                const isNow = i === startIndex;
                const timeLabel = isNow ? 'ตอนนี้' : tDate.getHours().toString().padStart(2, '0') + ':00';
                const temp = Math.round(hourly.temperature_2m[i]);
                const pop = hourly.precipitation_probability[i];
                const hInfo = getWeatherInfo(hourly.weather_code[i]);
                
                hHtml += `
                    <div class="flex flex-col items-center flex-shrink-0 w-[55px] snap-center">
                        <div class="text-[15px] font-medium text-gray-100 mb-2">${temp}°</div>
                        <div class="text-[10px] font-bold text-indigo-400 mb-1 h-3">${pop > 0 ? pop + '%' : ''}</div>
                        <div class="text-[1.35rem] mb-2 drop-shadow-sm">${hInfo.icon}</div>
                        <div class="text-xs text-gray-400 font-medium">${timeLabel}</div>
                    </div>
                `;
            }
            hourlyContainer.innerHTML = hHtml;
        }

        // Render Daily
        const dailyContainer = document.getElementById('weather-daily');
        if (daily && daily.time) {
            let dHtml = '';
            const dayNames = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์', 'เสาร์'];
            for (let i = 0; i < daily.time.length; i++) {
                const d = new Date(daily.time[i]);
                const dayName = i === 0 ? 'วันนี้' : dayNames[d.getDay()];
                const dInfo = getWeatherInfo(daily.weather_code[i]);
                const max = Math.round(daily.temperature_2m_max[i]);
                const min = Math.round(daily.temperature_2m_min[i]);
                const isToday = i === 0;

                dHtml += `
                    <div class="flex-shrink-0 w-[88px] bg-[#383a40]/40 rounded-[1.2rem] p-3.5 text-center snap-center border border-white/5 ${isToday ? 'ring-1 ring-white/20 bg-[#383a40]/80 shadow-md' : 'hover:bg-white/5'} transition-colors cursor-default">
                        <div class="text-[13px] text-gray-300 font-medium mb-2.5">${dayName}</div>
                        <div class="text-[1.4rem] mb-2 drop-shadow-sm">${dInfo.icon}</div>
                        <div class="text-[13px] font-medium text-gray-100 flex justify-center gap-1.5 items-baseline">
                            <span>${max}°</span><span class="text-gray-500 font-normal text-xs">/</span><span class="text-gray-400 font-normal">${min}°</span>
                        </div>
                    </div>
                `;
            }
            dailyContainer.innerHTML = dHtml;
        }

        fetchLocationName(lat, lon);

        document.getElementById('weather-loading').classList.add('hidden');
        document.getElementById('weather-content').classList.remove('hidden');
    }

    function fetchLocationName(lat, lon) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&accept-language=th`)
            .then(r => r.json())
            .then(data => {
                const addr = data.address || {};
                let name = 'พิกัดปัจจุบัน';
                if(addr.village || addr.suburb) {
                    name = (addr.village || addr.suburb) + (addr.county ? ' ' + addr.county : '');
                } else if (addr.city || addr.town) {
                    name = addr.city || addr.town;
                }
                
                if(addr.state || addr.province) {
                    name += ' · ' + (addr.state || addr.province).replace('จ.', '').trim();
                }

                document.getElementById('weather-location').textContent = name;
            })
            .catch(() => {
                document.getElementById('weather-location').textContent = `พิกัด ${lat.toFixed(2)}, ${lon.toFixed(2)}`;
            });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => { 
        window.refreshWeather(); 
    });

    // Mock function to add new weather area using custom modal
    window.addWeatherArea = function() {
        const modal = document.getElementById('weather-prompt-modal');
        const content = document.getElementById('weather-prompt-content');
        const input = document.getElementById('weather-prompt-input');
        
        // Force teleport modal to body on demand to bypass any SPA issues
        if (modal && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        
        // Close dropdown if open
        window.closeWeatherOptions();
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95', 'translate-y-4');
        
        setTimeout(() => input.focus(), 100);
        
        input.onkeydown = function(e) {
            if (e.key === 'Enter') window.submitWeatherPrompt();
            if (e.key === 'Escape') window.closeWeatherPrompt();
        };
    };

    window.closeWeatherPrompt = function() {
        const modal = document.getElementById('weather-prompt-modal');
        const content = document.getElementById('weather-prompt-content');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.add('scale-95', 'translate-y-4');
    };

    window.submitWeatherPrompt = function() {
        const input = document.getElementById('weather-prompt-input');
        const newLocation = input.value.trim() || 'เชียงใหม่';
        
        closeWeatherPrompt();
        
        // Show loading
        document.getElementById('weather-loading').classList.remove('hidden');
        document.getElementById('weather-content').classList.add('hidden');
        document.getElementById('weather-error').classList.add('hidden');

        // Forward geocoding to get real lat/lon
        fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(newLocation)}&format=json&limit=1&accept-language=th`)
            .then(r => r.json())
            .then(data => {
                if(data && data.length > 0) {
                    lat = parseFloat(data[0].lat);
                    lon = parseFloat(data[0].lon);
                    
                    // Fetch real weather
                    window.refreshWeather();
                    
                    // Force the name to match what they searched exactly
                    setTimeout(() => {
                        document.getElementById('weather-location').textContent = newLocation;
                    }, 500);
                    setTimeout(() => {
                        document.getElementById('weather-location').textContent = newLocation;
                    }, 2500);
                } else {
                    alert('ไม่พบข้อมูลพื้นที่: ' + newLocation + ' (กรุณาลองระบุชื่อจังหวัดให้ชัดเจนขึ้น)');
                    document.getElementById('weather-loading').classList.add('hidden');
                    document.getElementById('weather-content').classList.remove('hidden');
                }
            })
            .catch(() => {
                alert('ระบบไม่สามารถค้นหาพิกัดได้ในขณะนี้');
                document.getElementById('weather-loading').classList.add('hidden');
                document.getElementById('weather-content').classList.remove('hidden');
            });
    };

    // Vanilla JS Dropdown Handlers
    window.toggleWeatherOptions = function(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('weather-options-dropdown');
        if (dropdown.classList.contains('opacity-0')) {
            dropdown.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        } else {
            window.closeWeatherOptions();
        }
    };

    window.closeWeatherOptions = function() {
        const dropdown = document.getElementById('weather-options-dropdown');
        if (dropdown) {
            dropdown.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        }
    };

    window.triggerWeatherRefresh = function() {
        window.closeWeatherOptions();
        window.refreshWeather();
    };

    window.triggerWeatherAreaPrompt = function() {
        window.closeWeatherOptions();
        window.addWeatherArea();
    };

    window.triggerWeatherSettings = function() {
        window.closeWeatherOptions();
        setTimeout(() => alert('ฟีเจอร์การตั้งค่าหน่วยวัดกำลังอยู่ในช่วงพัฒนา'), 100);
    };

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('weather-options-dropdown');
        if (dropdown && !dropdown.classList.contains('opacity-0') && !event.target.closest('#weather-options-dropdown') && !event.target.closest('button[onclick="toggleWeatherOptions(event)"]')) {
            window.closeWeatherOptions();
        }
    });
})();
</script>

{{-- Custom Prompt Modal (Teleported to body level implicitly by fixed positioning) --}}
<div id="weather-prompt-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-gradient-to-b from-[#2b2c30] to-[#1e1e24] border border-white/10 rounded-[2rem] p-7 w-full max-w-sm shadow-2xl transform scale-95 translate-y-4 transition-all duration-300 ease-out" id="weather-prompt-content">
        <div class="w-12 h-12 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">เพิ่มพื้นที่ใหม่</h3>
        <p class="text-sm text-gray-400 mb-6 leading-relaxed">กรุณาระบุชื่อจังหวัดหรือพื้นที่ที่คุณต้องการดูข้อมูลสภาพอากาศ</p>
        
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <select id="weather-prompt-input" class="w-full bg-black/20 border border-white/10 rounded-2xl pl-11 pr-10 py-3.5 text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all font-medium text-sm appearance-none cursor-pointer">
                <option value="กรุงเทพมหานคร" class="bg-[#2b2c30]">กรุงเทพมหานคร</option>
                <option value="กระบี่" class="bg-[#2b2c30]">กระบี่</option>
                <option value="กาญจนบุรี" class="bg-[#2b2c30]">กาญจนบุรี</option>
                <option value="กาฬสินธุ์" class="bg-[#2b2c30]">กาฬสินธุ์</option>
                <option value="กำแพงเพชร" class="bg-[#2b2c30]">กำแพงเพชร</option>
                <option value="ขอนแก่น" class="bg-[#2b2c30]">ขอนแก่น</option>
                <option value="จันทบุรี" class="bg-[#2b2c30]">จันทบุรี</option>
                <option value="ฉะเชิงเทรา" class="bg-[#2b2c30]">ฉะเชิงเทรา</option>
                <option value="ชลบุรี" class="bg-[#2b2c30]">ชลบุรี</option>
                <option value="ชัยนาท" class="bg-[#2b2c30]">ชัยนาท</option>
                <option value="ชัยภูมิ" class="bg-[#2b2c30]">ชัยภูมิ</option>
                <option value="ชุมพร" class="bg-[#2b2c30]">ชุมพร</option>
                <option value="เชียงราย" class="bg-[#2b2c30]">เชียงราย</option>
                <option value="เชียงใหม่" class="bg-[#2b2c30]">เชียงใหม่</option>
                <option value="ตรัง" class="bg-[#2b2c30]">ตรัง</option>
                <option value="ตราด" class="bg-[#2b2c30]">ตราด</option>
                <option value="ตาก" class="bg-[#2b2c30]">ตาก</option>
                <option value="นครนายก" class="bg-[#2b2c30]">นครนายก</option>
                <option value="นครปฐม" class="bg-[#2b2c30]">นครปฐม</option>
                <option value="นครพนม" class="bg-[#2b2c30]">นครพนม</option>
                <option value="นครราชสีมา" class="bg-[#2b2c30]">นครราชสีมา</option>
                <option value="นครศรีธรรมราช" class="bg-[#2b2c30]">นครศรีธรรมราช</option>
                <option value="นครสวรรค์" class="bg-[#2b2c30]">นครสวรรค์</option>
                <option value="นนทบุรี" class="bg-[#2b2c30]">นนทบุรี</option>
                <option value="นราธิวาส" class="bg-[#2b2c30]">นราธิวาส</option>
                <option value="น่าน" class="bg-[#2b2c30]">น่าน</option>
                <option value="บึงกาฬ" class="bg-[#2b2c30]">บึงกาฬ</option>
                <option value="บุรีรัมย์" class="bg-[#2b2c30]">บุรีรัมย์</option>
                <option value="ปทุมธานี" class="bg-[#2b2c30]">ปทุมธานี</option>
                <option value="ประจวบคีรีขันธ์" class="bg-[#2b2c30]">ประจวบคีรีขันธ์</option>
                <option value="ปราจีนบุรี" class="bg-[#2b2c30]">ปราจีนบุรี</option>
                <option value="ปัตตานี" class="bg-[#2b2c30]">ปัตตานี</option>
                <option value="พระนครศรีอยุธยา" class="bg-[#2b2c30]">พระนครศรีอยุธยา</option>
                <option value="พะเยา" class="bg-[#2b2c30]">พะเยา</option>
                <option value="พังงา" class="bg-[#2b2c30]">พังงา</option>
                <option value="พัทลุง" class="bg-[#2b2c30]">พัทลุง</option>
                <option value="พิจิตร" class="bg-[#2b2c30]">พิจิตร</option>
                <option value="พิษณุโลก" class="bg-[#2b2c30]">พิษณุโลก</option>
                <option value="เพชรบุรี" class="bg-[#2b2c30]">เพชรบุรี</option>
                <option value="เพชรบูรณ์" class="bg-[#2b2c30]">เพชรบูรณ์</option>
                <option value="แพร่" class="bg-[#2b2c30]">แพร่</option>
                <option value="ภูเก็ต" class="bg-[#2b2c30]">ภูเก็ต</option>
                <option value="มหาสารคาม" class="bg-[#2b2c30]">มหาสารคาม</option>
                <option value="มุกดาหาร" class="bg-[#2b2c30]">มุกดาหาร</option>
                <option value="แม่ฮ่องสอน" class="bg-[#2b2c30]">แม่ฮ่องสอน</option>
                <option value="ยโสธร" class="bg-[#2b2c30]">ยโสธร</option>
                <option value="ยะลา" class="bg-[#2b2c30]">ยะลา</option>
                <option value="ร้อยเอ็ด" class="bg-[#2b2c30]">ร้อยเอ็ด</option>
                <option value="ระนอง" class="bg-[#2b2c30]">ระนอง</option>
                <option value="ระยอง" class="bg-[#2b2c30]">ระยอง</option>
                <option value="ราชบุรี" class="bg-[#2b2c30]">ราชบุรี</option>
                <option value="ลพบุรี" class="bg-[#2b2c30]">ลพบุรี</option>
                <option value="ลำปาง" class="bg-[#2b2c30]">ลำปาง</option>
                <option value="ลำพูน" class="bg-[#2b2c30]">ลำพูน</option>
                <option value="เลย" class="bg-[#2b2c30]">เลย</option>
                <option value="ศรีสะเกษ" class="bg-[#2b2c30]">ศรีสะเกษ</option>
                <option value="สกลนคร" class="bg-[#2b2c30]">สกลนคร</option>
                <option value="สงขลา" class="bg-[#2b2c30]">สงขลา</option>
                <option value="สตูล" class="bg-[#2b2c30]">สตูล</option>
                <option value="สมุทรปราการ" class="bg-[#2b2c30]">สมุทรปราการ</option>
                <option value="สมุทรสงคราม" class="bg-[#2b2c30]">สมุทรสงคราม</option>
                <option value="สมุทรสาคร" class="bg-[#2b2c30]">สมุทรสาคร</option>
                <option value="สระแก้ว" class="bg-[#2b2c30]">สระแก้ว</option>
                <option value="สระบุรี" class="bg-[#2b2c30]">สระบุรี</option>
                <option value="สิงห์บุรี" class="bg-[#2b2c30]">สิงห์บุรี</option>
                <option value="สุโขทัย" class="bg-[#2b2c30]">สุโขทัย</option>
                <option value="สุพรรณบุรี" class="bg-[#2b2c30]">สุพรรณบุรี</option>
                <option value="สุราษฎร์ธานี" class="bg-[#2b2c30]">สุราษฎร์ธานี</option>
                <option value="สุรินทร์" class="bg-[#2b2c30]">สุรินทร์</option>
                <option value="หนองคาย" class="bg-[#2b2c30]">หนองคาย</option>
                <option value="หนองบัวลำภู" class="bg-[#2b2c30]">หนองบัวลำภู</option>
                <option value="อ่างทอง" class="bg-[#2b2c30]">อ่างทอง</option>
                <option value="อำนาจเจริญ" class="bg-[#2b2c30]">อำนาจเจริญ</option>
                <option value="อุดรธานี" class="bg-[#2b2c30]">อุดรธานี</option>
                <option value="อุตรดิตถ์" class="bg-[#2b2c30]">อุตรดิตถ์</option>
                <option value="อุทัยธานี" class="bg-[#2b2c30]">อุทัยธานี</option>
                <option value="อุบลราชธานี" class="bg-[#2b2c30]">อุบลราชธานี</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
        </div>
        
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="closeWeatherPrompt()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-300 hover:text-white hover:bg-white/10 transition-colors">ยกเลิก</button>
            <button type="button" onclick="submitWeatherPrompt()" class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/30 transition-all">ค้นหา</button>
        </div>
    </div>
</div>

