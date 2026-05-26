<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sanggar;

class SanggarSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sanggar = Sanggar::first();
        if ($sanggar) {
            $sanggar->update([
                'pendaftaran_dibuka' => now()->subDays(5), // Sudah dibuka 5 hari lalu
                'pendaftaran_ditutup' => now()->addMonths(1), // Ditutup bulan depan
            ]);
        }
    }
}
