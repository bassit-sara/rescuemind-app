<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // SOS
            'sos.create', 'sos.view_own', 'sos.view_all', 'sos.assign', 'sos.update_status',
            // Missing Person
            'missing.create', 'missing.view_all', 'missing.update_status',
            // Hazard Report
            'hazard.create', 'hazard.view_all', 'hazard.verify',
            // Mental Health
            'mental.assess', 'mental.view_own', 'mental.view_all',
            'mental.mood', 'mental.journal', 'mental.appointment_create',
            'mental.appointment_manage',
            // Relief Points
            'relief.view', 'relief.manage',
            // Alerts
            'alert.view', 'alert.create', 'alert.manage',
            // Resources
            'resource.view', 'resource.manage',
            // Volunteers
            'volunteer.register', 'volunteer.manage',
            // Users
            'user.manage', 'user.view_all',
            // Dashboard
            'dashboard.officer', 'dashboard.mental_officer',
            'dashboard.admin', 'dashboard.super_admin',
            // Family Safety
            'family.safe_check',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Create Roles and assign permissions
        $guest = Role::firstOrCreate(['name' => 'guest', 'guard_name' => 'web']);
        $guest->syncPermissions(['relief.view', 'alert.view']);

        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->syncPermissions([
            'sos.create', 'sos.view_own',
            'missing.create',
            'hazard.create',
            'mental.assess', 'mental.view_own', 'mental.mood', 'mental.journal',
            'mental.appointment_create',
            'relief.view', 'alert.view',
            'family.safe_check',
        ]);

        $volunteer = Role::firstOrCreate(['name' => 'volunteer', 'guard_name' => 'web']);
        $volunteer->syncPermissions([
            'sos.view_all',
            'missing.view_all', 'missing.update_status',
            'hazard.create', 'hazard.view_all',
            'mental.assess', 'mental.view_own', 'mental.mood', 'mental.journal',
            'mental.appointment_create',
            'relief.view', 'alert.view',
            'family.safe_check',
            'volunteer.register',
        ]);

        $officer = Role::firstOrCreate(['name' => 'officer', 'guard_name' => 'web']);
        $officer->syncPermissions([
            'sos.view_all', 'sos.assign', 'sos.update_status',
            'missing.view_all', 'missing.update_status',
            'hazard.view_all', 'hazard.verify',
            'mental.assess', 'mental.view_own', 'mental.mood', 'mental.journal',
            'mental.appointment_create',
            'relief.view', 'alert.view',
            'resource.view',
            'dashboard.officer',
            'family.safe_check',
        ]);

        $mentalOfficer = Role::firstOrCreate(['name' => 'mental_officer', 'guard_name' => 'web']);
        $mentalOfficer->syncPermissions([
            'mental.assess', 'mental.view_own', 'mental.view_all',
            'mental.mood', 'mental.journal',
            'mental.appointment_create', 'mental.appointment_manage',
            'relief.view', 'alert.view',
            'dashboard.mental_officer',
            'family.safe_check',
        ]);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'sos.view_all', 'sos.assign', 'sos.update_status',
            'missing.view_all', 'missing.update_status',
            'hazard.view_all', 'hazard.verify',
            'mental.view_all', 'mental.appointment_manage',
            'relief.view', 'relief.manage',
            'alert.view', 'alert.create',
            'resource.view', 'resource.manage',
            'volunteer.manage',
            'user.view_all',
            'dashboard.officer', 'dashboard.admin',
        ]);

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());
    }
}
