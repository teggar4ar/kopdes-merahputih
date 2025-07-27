<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test member
        $member = User::firstOrCreate(
            ['email' => 'member@test.com'],
            [
                'name' => 'Test Member',
                'nik' => '1234567890123456',
                'phone_number' => '081234567890',
                'address' => 'Desa Tajurhalang',
                'password' => Hash::make('password123'),
                'account_status' => 'active',
            ]
        );
        $member->assignRole('member');

        // Create test administrator
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Test Administrator',
                'nik' => '1234567890123457',
                'phone_number' => '081234567891',
                'address' => 'Desa Tajurhalang',
                'password' => Hash::make('password123'),
                'account_status' => 'active',
            ]
        );
        $admin->assignRole('administrator');

        // Create test supervisor
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@test.com'],
            [
                'name' => 'Test Supervisor',
                'nik' => '1234567890123458',
                'phone_number' => '081234567892',
                'address' => 'Desa Tajurhalang',
                'password' => Hash::make('password123'),
                'account_status' => 'active',
            ]
        );
        $supervisor->assignRole('supervisor');

        $this->command->info('Test users created successfully!');
        $this->command->info('Member: member@test.com / password123');
        $this->command->info('Admin: admin@test.com / password123');
        $this->command->info('Supervisor: supervisor@test.com / password123');
    }
}
