{{-- Weather Widget — Premium Mobile UI (Soft Dark Theme) --}}
<div id="weather-widget" class="bg-gradient-to-br from-[#2b2c30] to-[#1e1e24] rounded-[2rem] text-white p-6 font-sans shadow-xl relative overflow-hidden ring-1 ring-white/5 border-t border-white/10 w-full h-full flex flex-col justify-between">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 relative z-10">
        <h2 class="text-lg font-medium text-gray-100 tracking-wide">สภาพอากาศ</h2>
        <button class="text-gray-400 hover:text-white transition-colors" title="ตัวเลือกเพิ่มเติม">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
        </button>
    </div>

    {{-- Loading State --}}
    <div id="weather-loading" class="flex items-center justify-center py-10 relative z-10">
        <div class="inline-block w-8 h-8 border-3 border-gray-600 border-t-white rounded-full animate-spin"></div>
    </div>

    {{-- Error State --}}
    <div id="weather-error" class="hidden text-center py-10 relative z-10">
        <div class="text-3xl mb-2">⚠️</div>
        <p class="text-gray-400 text-sm font-medium">ไม่สามารถโหลดข้อมูลได้</p>
        <button onclick="refreshWeather()" class="mt-3 px-4 py-1.5 bg-white/10 rounded-full text-xs hover:bg-white/20 transition-colors">ลองอีกครั้ง</button>
    </div>

    {{-- Weather Content --}}
    <div id="weather-content" class="hidden relative z-10">
        {{-- Next Forecast Banner --}}
        <div class="bg-white/5 hover:bg-white/10 transition-colors rounded-2xl px-5 py-3.5 flex items-center gap-3 mb-6 shadow-sm border border-white/5">
            <span class="text-xl" id="weather-forecast-icon">☁️</span>
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
                    <span class="bg-red-400 text-white rounded-full w-4 h-4 flex items-center justify-center text-[9px]">🌡️</span>
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
    const WMO_CODES = {
        0:  { desc: 'ฟ้าโปร่ง', icon: '☀️' },
        1:  { desc: 'ส่วนใหญ่โปร่ง', icon: '🌤️' },
        2:  { desc: 'มีเมฆบางส่วน', icon: '⛅' },
        3:  { desc: 'มีเมฆมาก', icon: '☁️' },
        45: { desc: 'หมอก', icon: '🌫️' },
        48: { desc: 'หมอกเยือกแข็ง', icon: '🌫️' },
        51: { desc: 'ฝนละออง เบา', icon: '🌦️' },
        53: { desc: 'ฝนละออง ปานกลาง', icon: '🌦️' },
        55: { desc: 'ฝนละออง หนัก', icon: '🌧️' },
        56: { desc: 'ฝนละอองเยือกแข็ง', icon: '🌧️' },
        57: { desc: 'ฝนละอองเยือกแข็งหนัก', icon: '🌧️' },
        61: { desc: 'ฝนตก เบา', icon: '🌦️' },
        63: { desc: 'ฝนตก ปานกลาง', icon: '🌧️' },
        65: { desc: 'ฝนตก หนัก', icon: '🌧️' },
        66: { desc: 'ฝนเยือกแข็ง', icon: '🌨️' },
        67: { desc: 'ฝนเยือกแข็งหนัก', icon: '🌨️' },
        71: { desc: 'หิมะตก เบา', icon: '❄️' },
        73: { desc: 'หิมะตก ปานกลาง', icon: '❄️' },
        75: { desc: 'หิมะตก หนัก', icon: '❄️' },
        77: { desc: 'เกล็ดหิมะ', icon: '❄️' },
        80: { desc: 'ฝนตกเป็นพัก เบา', icon: '🌦️' },
        81: { desc: 'ฝนตกเป็นพัก ปานกลาง', icon: '🌧️' },
        82: { desc: 'ฝนตกเป็นพัก หนัก', icon: '⛈️' },
        85: { desc: 'หิมะตกเป็นพัก', icon: '🌨️' },
        86: { desc: 'หิมะตกหนักเป็นพัก', icon: '🌨️' },
        95: { desc: 'พายุฝนฟ้าคะนอง', icon: '⛈️' },
        96: { desc: 'พายุฝนฟ้าคะนอง+ลูกเห็บ', icon: '⛈️' },
        99: { desc: 'พายุฝนฟ้าคะนอง+ลูกเห็บหนัก', icon: '⛈️' },
    };

    function getWeatherInfo(code) {
        return WMO_CODES[code] || { desc: 'ไม่ทราบ', icon: '☁️' };
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
        document.getElementById('weather-icon').textContent = info.icon;
        document.getElementById('weather-desc').textContent = info.desc;
        document.getElementById('weather-feels').textContent = Math.round(current.apparent_temperature);

        // Also update navbar if exists
        if(document.getElementById('nav-weather-temp')) {
            document.getElementById('nav-weather-temp').textContent = Math.round(current.temperature_2m);
            document.getElementById('nav-weather-icon').textContent = info.icon;
        }

        // Determine Next Forecast (find next hour with >30% rain chance)
        let nextRainText = 'คาดว่าสภาพอากาศจะปลอดโปร่ง';
        let nextRainIcon = '☀️';
        
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
                    nextRainIcon = '🌧️';
                    break;
                }
            }
        }
        document.getElementById('weather-next-forecast').textContent = nextRainText;
        document.getElementById('weather-forecast-icon').textContent = nextRainIcon;

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
    document.addEventListener('DOMContentLoaded', () => { window.refreshWeather(); });
})();
</script>
