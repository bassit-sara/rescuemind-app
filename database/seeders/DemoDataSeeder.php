<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\ReliefPoint;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Sample Alerts
        Alert::firstOrCreate(
            ['title' => 'เฝ้าระวังน้ำท่วมเชียงใหม่'],
            [
                'message'      => 'ระดับน้ำในแม่น้ำปิงสูงขึ้นต่อเนื่อง ประชาชนในพื้นที่ลุ่มต่ำให้เตรียมพร้อม',
                'level'        => 1,
                'province'     => 'เชียงใหม่',
                'disaster_type'=> 'flood',
                'issued_at'    => now(),
                'expires_at'   => now()->addDays(3),
                'is_active'    => true,
            ]
        );

        Alert::firstOrCreate(
            ['title' => 'เตือนภัยน้ำท่วมเชียงราย'],
            [
                'message'      => 'ฝนตกหนักต่อเนื่อง คาดว่าจะเกิดน้ำท่วมฉับพลันในอีก 6 ชั่วโมง โปรดเตรียมอพยพ',
                'level'        => 2,
                'province'     => 'เชียงราย',
                'disaster_type'=> 'flood',
                'issued_at'    => now(),
                'expires_at'   => now()->addDays(2),
                'is_active'    => true,
            ]
        );

        Alert::firstOrCreate(
            ['title' => 'อพยพทันที — ดินถล่มน่าน'],
            [
                'message'      => 'เกิดดินถล่มรุนแรงบริเวณอำเภอปัว ประชาชนในรัศมี 5 กม. ให้อพยพออกทันที',
                'level'        => 3,
                'province'     => 'น่าน',
                'disaster_type'=> 'landslide',
                'issued_at'    => now(),
                'expires_at'   => now()->addDay(),
                'is_active'    => true,
            ]
        );

        // Sample Relief Points
        $reliefPoints = [
            [
                'name'              => 'โรงเรียนยุพราชวิทยาลัย (ศูนย์พักพิง)',
                'type'              => 'shelter',
                'province'          => 'เชียงใหม่',
                'district'          => 'เมือง',
                'address'           => 'ถ.พระปกเกล้า อ.เมือง จ.เชียงใหม่',
                'latitude'          => 18.7878,
                'longitude'         => 98.9931,
                'capacity'          => 500,
                'current_occupancy' => 120,
                'is_active'         => true,
            ],
            [
                'name'              => 'โรงพยาบาลนครพิงค์',
                'type'              => 'hospital',
                'province'          => 'เชียงใหม่',
                'district'          => 'เมือง',
                'address'           => '159 ม.10 ต.ดอนแก้ว อ.แม่ริม จ.เชียงใหม่',
                'latitude'          => 18.8560,
                'longitude'         => 98.9578,
                'capacity'          => 800,
                'current_occupancy' => 350,
                'available_beds'    => 450,
                'has_icu'           => true,
                'ambulance_count'   => 8,
                'phone'             => '053-999-200',
                'is_active'         => true,
            ],
            [
                'name'              => 'ศูนย์อาหาร วัดสวนดอก',
                'type'              => 'food',
                'province'          => 'เชียงใหม่',
                'district'          => 'เมือง',
                'address'           => 'วัดสวนดอก ถ.สุเทพ อ.เมือง จ.เชียงใหม่',
                'latitude'          => 18.7889,
                'longitude'         => 98.9724,
                'capacity'          => 0,
                'current_occupancy' => 0,
                'is_active'         => true,
            ],
            [
                'name'              => 'ลานจอดรถ Airport Plaza (จุดอพยพ)',
                'type'              => 'parking',
                'province'          => 'เชียงใหม่',
                'district'          => 'เมือง',
                'address'           => 'Airport Plaza เชียงใหม่',
                'latitude'          => 18.7584,
                'longitude'         => 98.9676,
                'capacity'          => 1000,
                'current_occupancy' => 0,
                'is_active'         => true,
            ],
        ];

        foreach ($reliefPoints as $point) {
            ReliefPoint::firstOrCreate(['name' => $point['name']], $point);
        }

        // Sample Resources
        $resources = [
            ['name' => 'เรือท้องแบน', 'type' => 'boat', 'province' => 'เชียงใหม่', 'quantity' => 20, 'available_quantity' => 15, 'unit' => 'ลำ'],
            ['name' => 'รถบรรทุก 6 ล้อ', 'type' => 'truck', 'province' => 'เชียงใหม่', 'quantity' => 10, 'available_quantity' => 8, 'unit' => 'คัน'],
            ['name' => 'ถุงยังชีพ', 'type' => 'food', 'province' => 'เชียงใหม่', 'quantity' => 2000, 'available_quantity' => 1500, 'unit' => 'ถุง'],
            ['name' => 'น้ำดื่ม 600ml', 'type' => 'water', 'province' => 'เชียงใหม่', 'quantity' => 10000, 'available_quantity' => 8000, 'unit' => 'ขวด'],
            ['name' => 'ยาชุดปฐมพยาบาล', 'type' => 'medicine', 'province' => 'เชียงใหม่', 'quantity' => 500, 'available_quantity' => 450, 'unit' => 'ชุด'],
        ];

        foreach ($resources as $res) {
            Resource::firstOrCreate(['name' => $res['name'], 'province' => $res['province']], $res);
        }
    }
}
