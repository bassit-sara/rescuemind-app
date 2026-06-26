<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Status & Maintenance
            ['group' => 'status', 'key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'label' => 'โหมดปรับปรุงระบบ (ปิดเว็บชั่วคราว)'],
            ['group' => 'status', 'key' => 'maintenance_message', 'value' => 'กำลังปรับปรุงระบบ กรุณากลับมาใหม่ในภายหลัง', 'type' => 'string', 'label' => 'ข้อความแจ้งเตือนตอนปิดระบบ'],
            ['group' => 'status', 'key' => 'global_announcement', 'value' => '', 'type' => 'string', 'label' => 'ประกาศฉุกเฉิน (แสดงแถบด้านบนสุด)'],

            // Features Toggle
            ['group' => 'features', 'key' => 'allow_registration', 'value' => 'true', 'type' => 'boolean', 'label' => 'เปิดรับสมัครสมาชิกใหม่'],
            ['group' => 'features', 'key' => 'allow_public_sos', 'value' => 'true', 'type' => 'boolean', 'label' => 'เปิดให้บุคคลทั่วไปแจ้ง SOS'],
            ['group' => 'features', 'key' => 'enable_ai_assistant', 'value' => 'true', 'type' => 'boolean', 'label' => 'เปิดใช้งานระบบผู้ช่วย AI (Gemini)'],

            // Notifications
            ['group' => 'notifications', 'key' => 'enable_line_notify', 'value' => 'true', 'type' => 'boolean', 'label' => 'เปิดแจ้งเตือน Line Notify'],
            ['group' => 'notifications', 'key' => 'sos_auto_reply', 'value' => 'เราได้รับแจ้งเหตุของคุณแล้ว เจ้าหน้าที่จะติดต่อกลับโดยเร็วที่สุด', 'type' => 'string', 'label' => 'ข้อความตอบกลับ SOS อัตโนมัติ'],

            // System Variables
            ['group' => 'system', 'key' => 'volunteer_alert_radius_km', 'value' => '10', 'type' => 'integer', 'label' => 'รัศมีการแจ้งเตือนอาสาสมัคร (กิโลเมตร)'],
            ['group' => 'system', 'key' => 'default_map_lat', 'value' => '13.7563', 'type' => 'string', 'label' => 'ละติจูดเริ่มต้นของแผนที่'],
            ['group' => 'system', 'key' => 'default_map_lng', 'value' => '100.5018', 'type' => 'string', 'label' => 'ลองจิจูดเริ่มต้นของแผนที่'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
