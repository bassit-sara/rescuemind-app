<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Alert;
use Carbon\Carbon;

class CheckWeatherAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch weather data from Open-Meteo and automatically generate alerts for severe weather.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting weather check...");

        $locations = [
            ['province' => 'กรุงเทพมหานคร', 'lat' => 13.7563, 'lon' => 100.5018],
            ['province' => 'เชียงใหม่', 'lat' => 18.7883, 'lon' => 98.9853],
            ['province' => 'ขอนแก่น', 'lat' => 16.4322, 'lon' => 102.8236],
            ['province' => 'ภูเก็ต', 'lat' => 7.8804, 'lon' => 98.3923],
            ['province' => 'ชลบุรี', 'lat' => 13.3611, 'lon' => 100.9846],
        ];

        foreach ($locations as $loc) {
            $this->info("Checking {$loc['province']}...");
            
            $url = "https://api.open-meteo.com/v1/forecast?latitude={$loc['lat']}&longitude={$loc['lon']}&current=weather_code,wind_speed_10m&timezone=Asia%2FBangkok";
            
            try {
                $response = Http::timeout(10)->get($url);
                if ($response->successful()) {
                    $data = $response->json();
                    if (!isset($data['current'])) continue;
                    
                    $code = $data['current']['weather_code'];
                    $wind = $data['current']['wind_speed_10m'];
                    
                    $this->checkAndCreateAlert($loc['province'], $code, $wind);
                } else {
                    $this->error("API returned error for {$loc['province']}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to fetch data for {$loc['province']}: " . $e->getMessage());
            }
        }
        
        $this->info("Weather check completed.");
    }

    private function checkAndCreateAlert($province, $code, $wind)
    {
        // WMO Codes
        // 61, 63, 65: Rain (slight, moderate, heavy)
        // 80, 81, 82: Rain showers (slight, moderate, violent)
        // 95: Thunderstorm
        // 96, 99: Thunderstorm with hail
        
        $severity = 0;
        $message = '';
        
        if (in_array($code, [65, 82])) {
            $severity = 2; // Level 2
            $message = "ตรวจพบกลุ่มฝนตกหนักถึงหนักมากในพื้นที่ อาจเกิดน้ำท่วมฉับพลัน กรุณาเฝ้าระวังและเตรียมพร้อมรับมือ";
        } elseif (in_array($code, [95, 96, 99])) {
            $severity = 3; // Level 3
            $message = "พายุฝนฟ้าคะนองรุนแรงในพื้นที่ ขอให้งดกิจกรรมกลางแจ้งและอยู่ในที่ปลอดภัยทันที";
        } elseif ($wind > 60) {
            $severity = 2; // Level 2
            $message = "ตรวจพบลมกระโชกแรง (ความเร็วลมเกิน 60 กม./ชม.) ขอให้ระวังป้ายโฆษณาและต้นไม้ใหญ่หักโค่น";
        }
        
        if ($severity > 0) {
            $this->createAlert($province, $severity, $message);
        } else {
            $this->info("  - Weather is normal (Code: $code, Wind: $wind km/h)");
        }
    }

    private function createAlert($province, $severity, $message)
    {
        // Check if an active alert for this province already exists
        $existing = Alert::query()->where('province', $province)
            ->where('is_active', true)
            ->where('disaster_type', 'storm')
            ->where('created_at', '>=', now()->subHours(12))
            ->exists();
            
        if ($existing) {
            $this->info("  - Alert already active for $province.");
            return;
        }
        
        $title = $severity == 3 ? "ด่วน! เตือนภัยพายุรุนแรง" : "ระวัง! สภาพอากาศแปรปรวน";
        
        Alert::create([
            'title' => $title,
            'message' => $message,
            'level' => $severity,
            'province' => $province,
            'disaster_type' => 'storm',
            'issued_at' => now(),
            'expires_at' => now()->addHours(6),
            'is_active' => true,
            'issued_by' => null, // System generated
        ]);
        
        $this->warn("  *** ALERT CREATED FOR $province ***");
    }
}
