<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'setting_key' => 'loan_interest_rate',
                'setting_value' => '12.0',
                'description' => 'Annual loan interest rate percentage'
            ],
            [
                'setting_key' => 'max_loan_amount',
                'setting_value' => '10000000.00',
                'description' => 'Maximum loan amount allowed'
            ],
            [
                'setting_key' => 'min_savings_balance',
                'setting_value' => '50000.00',
                'description' => 'Minimum savings balance required'
            ],
            [
                'setting_key' => 'app_name',
                'setting_value' => 'Koperasi Merah Putih',
                'description' => 'Application name for display'
            ],
            [
                'setting_key' => 'cooperative_address',
                'setting_value' => 'Desa Tajurhalang',
                'description' => 'Cooperative office address'
            ]
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->updateOrInsert(
                ['setting_key' => $setting['setting_key']],
                $setting
            );
        }
    }
}
