<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for Member features
        $memberPermissions = [
            'view own dashboard',
            'view own savings',
            'view own loans',
            'view own profile',
            'update own profile',
            'request loan',
            'request withdrawal',
            'view announcements',
        ];

        // Create permissions for Administrator features
        $adminPermissions = [
            'manage members',
            'approve member registration',
            'activate member accounts',
            'deactivate member accounts',
            'manage savings transactions',
            'process deposits',
            'process withdrawals',
            'manage loans',
            'approve loan applications',
            'reject loan applications',
            'record loan payments',
            'manage announcements',
            'create announcements',
            'edit announcements',
            'delete announcements',
            'manage system settings',
            'configure interest rates',
            'generate reports',
            'export reports',
            'view audit logs',
        ];

        // Create permissions for Supervisor features (read-only versions)
        $supervisorPermissions = [
            'view all members',
            'view all savings transactions',
            'view all loans',
            'view all announcements',
            'view system settings',
            'view reports',
            'export reports',
            'view audit logs',
            'view comprehensive audit logs',
        ];

        // Create all permissions
        $allPermissions = array_merge($memberPermissions, $adminPermissions, $supervisorPermissions);
        foreach (array_unique($allPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Member Role
        $memberRole = Role::firstOrCreate(['name' => 'member']);
        $memberRole->syncPermissions($memberPermissions);

        // Administrator Role
        $adminRole = Role::firstOrCreate(['name' => 'administrator']);
        $adminRole->syncPermissions(array_merge($memberPermissions, $adminPermissions));

        // Supervisor Role (read-only access to most admin features)
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        $supervisorRole->syncPermissions($supervisorPermissions);

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Created roles: member, administrator, supervisor');
        $this->command->info('Total permissions created: ' . count(array_unique($allPermissions)));
    }
}
