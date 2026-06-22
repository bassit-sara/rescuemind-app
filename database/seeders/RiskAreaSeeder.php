<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RiskAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RiskArea::truncate();

        $areas = [
            [
                'province' => 'ยะลา',
                'district' => 'เมืองยะลา',
                'subdistrict' => 'สะเตง',
                'area_name' => 'อ.เมืองยะลา (ยะลา)',
                'latitude' => 6.5411,
                'longitude' => 101.2804,
                'rain_score' => 20,
                'water_score' => 10,
                'sos_count' => 0,
                'missing_count' => 0,
                'hazard_count' => 0,
            ],
            [
                'province' => 'ปัตตานี',
                'district' => 'เมืองปัตตานี',
                'subdistrict' => 'สะบารัง',
                'area_name' => 'อ.เมืองปัตตานี (ปัตตานี)',
                'latitude' => 6.8673,
                'longitude' => 101.2501,
                'rain_score' => 50,
                'water_score' => 40,
                'sos_count' => 1,
                'missing_count' => 0,
                'hazard_count' => 0,
            ],
            [
                'province' => 'นราธิวาส',
                'district' => 'เมืองนราธิวาส',
                'subdistrict' => 'บางนาค',
                'area_name' => 'อ.เมืองนราธิวาส (นราธิวาส)',
                'latitude' => 6.4255,
                'longitude' => 101.8253,
                'rain_score' => 80,
                'water_score' => 70,
                'sos_count' => 4,
                'missing_count' => 0,
                'hazard_count' => 2,
            ],
            [
                'province' => 'สงขลา',
                'district' => 'หาดใหญ่',
                'subdistrict' => 'หาดใหญ่',
                'area_name' => 'อ.หาดใหญ่ (สงขลา)',
                'latitude' => 7.0097,
                'longitude' => 100.4746,
                'rain_score' => 100,
                'water_score' => 90,
                'sos_count' => 12,
                'missing_count' => 1,
                'hazard_count' => 3,
            ],
            [
                'province' => 'สตูล',
                'district' => 'เมืองสตูล',
                'subdistrict' => 'พิมาน',
                'area_name' => 'อ.เมืองสตูล (สตูล)',
                'latitude' => 6.6238,
                'longitude' => 100.0674,
                'rain_score' => 90,
                'water_score' => 100,
                'sos_count' => 15,
                'missing_count' => 2,
                'hazard_count' => 5,
            ]
        ];

        foreach ($areas as $data) {
            $riskArea = new \App\Models\RiskArea($data);
            $riskArea->calculateRiskScore();
            $riskArea->save();
        }
    }
}
