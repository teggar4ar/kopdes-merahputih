<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\SavingsTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a test member user
        $member = User::role('member')->first();

        if (!$member) {
            $this->command->warn('No member user found. Please run TestUserSeeder first.');
            return;
        }

        // Create sample savings transactions
        $this->createSampleSavingsTransactions($member);

        // Create sample announcements
        $this->createSampleAnnouncements();

        $this->command->info('Sample data created successfully!');
    }

    private function createSampleSavingsTransactions(User $member): void
    {
        $transactions = [
            // Simpanan Wajib (One-time payment of Rp 100,000)
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'wajib',
                'amount' => 100000,
                'description' => 'Simpanan wajib (pembayaran satu kali)',
                'transaction_date' => now()->subMonths(6),
            ],

            // Simpanan Pokok (Monthly payments of Rp 50,000)
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'pokok',
                'amount' => 50000,
                'description' => 'Simpanan pokok bulan Januari',
                'transaction_date' => now()->subMonths(5),
            ],
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'pokok',
                'amount' => 50000,
                'description' => 'Simpanan pokok bulan Februari',
                'transaction_date' => now()->subMonths(4),
            ],
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'pokok',
                'amount' => 50000,
                'description' => 'Simpanan pokok bulan Maret',
                'transaction_date' => now()->subMonths(3),
            ],
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'pokok',
                'amount' => 50000,
                'description' => 'Simpanan pokok bulan April',
                'transaction_date' => now()->subMonths(2),
            ],
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'pokok',
                'amount' => 50000,
                'description' => 'Simpanan pokok bulan Mei',
                'transaction_date' => now()->subMonths(1),
            ],

            // Simpanan Sukarela
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'sukarela',
                'amount' => 200000,
                'description' => 'Setoran simpanan sukarela',
                'transaction_date' => now()->subMonths(4),
            ],
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'sukarela',
                'amount' => 150000,
                'description' => 'Setoran simpanan sukarela',
                'transaction_date' => now()->subMonths(2),
            ],
            [
                'user_id' => $member->id,
                'transaction_type' => 'setor',
                'savings_type' => 'sukarela',
                'amount' => 100000,
                'description' => 'Setoran simpanan sukarela',
                'transaction_date' => now()->subWeek(),
            ],
            [
                'user_id' => $member->id,
                'transaction_type' => 'tarik',
                'savings_type' => 'sukarela',
                'amount' => 75000,
                'description' => 'Penarikan untuk keperluan mendesak',
                'transaction_date' => now()->subDays(10),
            ],
        ];

        foreach ($transactions as $transaction) {
            SavingsTransaction::create($transaction);
        }

        $this->command->info('Sample savings transactions created.');
    }

    private function createSampleAnnouncements(): void
    {
        // Get an admin user
        $admin = User::role('administrator')->first();

        if (!$admin) {
            $this->command->warn('No admin user found for announcements.');
            return;
        }

        $announcements = [
            [
                'admin_user_id' => $admin->id,
                'title' => 'Selamat Datang di Koperasi Merah Putih Digital',
                'content' => 'Selamat datang di platform digital Koperasi Merah Putih! Kini Anda dapat mengakses informasi simpanan dan pinjaman secara real-time melalui dashboard ini. Jika ada pertanyaan, silakan hubungi pengurus koperasi.',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'admin_user_id' => $admin->id,
                'title' => 'Pengumuman Rapat Anggota Tahunan',
                'content' => 'Diberitahukan kepada seluruh anggota koperasi bahwa Rapat Anggota Tahunan (RAT) akan dilaksanakan pada tanggal 15 Agustus 2025 pukul 09.00 WIB di Balai Desa Tajurhalang. Kehadiran semua anggota sangat diharapkan.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'admin_user_id' => $admin->id,
                'title' => 'Penyesuaian Suku Bunga Pinjaman',
                'content' => 'Mulai tanggal 1 Agustus 2025, suku bunga pinjaman akan disesuaikan menjadi 12% per tahun. Penyesuaian ini berlaku untuk pinjaman baru yang diajukan setelah tanggal tersebut.',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }

        $this->command->info('Sample announcements created.');
    }
}
