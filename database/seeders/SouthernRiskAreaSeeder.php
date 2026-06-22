<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskArea;

class SouthernRiskAreaSeeder extends Seeder
{
    public function run(): void
    {
        RiskArea::truncate();

        $jsonPath = base_path('district_coords.json');
        if (!file_exists($jsonPath)) {
            $this->command->error('district_coords.json not found. Run fetch_coords.php first.');
            return;
        }

        $districtCoords = json_decode(file_get_contents($jsonPath), true);

        foreach ($districtCoords as $provName => $districts) {
            foreach ($districts as $districtName => $coords) {
                if (!$coords) continue; // skip if not found

                $centerLat = $coords['lat'];
                $centerLng = $coords['lng'];

                // Generate 3-8 subdistricts per district to make it dense and realistic
                $tambonCount = rand(3, 8);
                for ($i = 1; $i <= $tambonCount; $i++) {
                    // Offset between -0.04 to +0.04 degrees (~4km spread around district center)
                    $latOffset = (rand(-400, 400) / 10000);
                    $lngOffset = (rand(-400, 400) / 10000);

                    // Randomly skew the scores to create some hotspots and safe spots
                    $isHotspot = rand(1, 10) > 8; // 20% chance of being a hotspot
                    
                    if ($isHotspot) {
                        $rain = rand(60, 100);
                        $water = rand(60, 100);
                        $sos = rand(2, 10);
                        $missing = rand(0, 2);
                        $hazard = rand(1, 4);
                    } else {
                        $rain = rand(0, 50);
                        $water = rand(0, 40);
                        $sos = rand(0, 1);
                        $missing = 0;
                        $hazard = rand(0, 1);
                    }

                    $riskArea = new RiskArea([
                        'province' => $provName,
                        'district' => $districtName,
                        'subdistrict' => 'ตำบลที่ ' . $i,
                        'area_name' => 'ต.ตำบลที่ ' . $i . ' อ.' . $districtName . ' จ.' . $provName,
                        'latitude' => $centerLat + $latOffset,
                        'longitude' => $centerLng + $lngOffset,
                        'rain_score' => $rain,
                        'water_score' => $water,
                        'sos_count' => $sos,
                        'missing_count' => $missing,
                        'hazard_count' => $hazard,
                    ]);
                    
                    $riskArea->calculateRiskScore();
                    $riskArea->save();
                }
            }
        }
    }
}
