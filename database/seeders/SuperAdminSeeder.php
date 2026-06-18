<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@rescuemind.th'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('rescuemind@2024'),
                'phone'    => '0800000001',
                'province' => 'กรุงเทพมหานคร',
                'is_active'=> true,
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create a sample admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@rescuemind.th'],
            [
                'name'     => 'Admin เชียงใหม่',
                'password' => Hash::make('rescuemind@2024'),
                'phone'    => '0800000002',
                'province' => 'เชียงใหม่',
                'is_active'=> true,
            ]
        );
        $admin->assignRole('admin');

        // Create a sample officer
        $officer = User::firstOrCreate(
            ['email' => 'officer@rescuemind.th'],
            [
                'name'     => 'เจ้าหน้าที่กู้ภัย',
                'password' => Hash::make('rescuemind@2024'),
                'phone'    => '0800000003',
                'province' => 'เชียงใหม่',
                'is_active'=> true,
            ]
        );
        $officer->assignRole('officer');

        // Create a sample mental officer
        $mentalOfficer = User::firstOrCreate(
            ['email' => 'mental@rescuemind.th'],
            [
                'name'     => 'นักจิตวิทยา',
                'password' => Hash::make('rescuemind@2024'),
                'phone'    => '0800000004',
                'province' => 'เชียงใหม่',
                'is_active'=> true,
            ]
        );
        $mentalOfficer->assignRole('mental_officer');

        // Create a demo user
        $demoUser = User::firstOrCreate(
            ['email' => 'user@rescuemind.th'],
            [
                'name'     => 'ผู้ใช้ทดสอบ',
                'password' => Hash::make('rescuemind@2024'),
                'phone'    => '0800000005',
                'province' => 'เชียงใหม่',
                'is_active'=> true,
            ]
        );
        $demoUser->assignRole('user');
    }
}
