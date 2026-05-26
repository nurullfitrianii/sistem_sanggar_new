<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramKelas;
use App\Models\JadwalLatihan;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SyncProgramScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure 'Seni Tari' and 'Karawitan' exist
        $seniTari = ProgramKelas::firstOrCreate(
            ['nama_program' => 'Seni Tari'],
            ['deskripsi' => 'Program Seni Tari', 'biaya' => 50000, 'status' => 'Aktif']
        );
        $seniTari->update(['status' => 'Aktif']);

        $karawitan = ProgramKelas::firstOrCreate(
            ['nama_program' => 'Karawitan'],
            ['deskripsi' => 'Program Karawitan', 'biaya' => 50000, 'status' => 'Aktif']
        );
        $karawitan->update(['status' => 'Aktif']);

        // 2. Handle 'Tari Klasik Sunda'
        $tariKlasik = ProgramKelas::where('nama_program', 'Tari Klasik Sunda')->first();
        if ($tariKlasik) {
            // Update existing students to Seni Tari
            Pendaftaran::where('id_program', $tariKlasik->id_program)->update(['id_program' => $seniTari->id_program]);
            
            // Delete or set to Tidak Aktif
            $tariKlasik->update(['status' => 'Tidak Aktif']);
            // If we want to fully delete, we can also delete schedules related to it:
            JadwalLatihan::where('id_program', $tariKlasik->id_program)->delete();
            $tariKlasik->delete();
        }

        // 3. Clear existing schedules to avoid duplicates for these programs
        JadwalLatihan::whereIn('id_program', [$seniTari->id_program, $karawitan->id_program])->delete();

        $pelatih = \App\Models\Pelatih::first();
        $sanggar = \App\Models\Sanggar::first();

        // 4. Create Fixed Schedules
        // Seni Tari: Sabtu & Minggu (10.00 – 13.00 WIB)
        $schedules = [
            [
                'id_program' => $seniTari->id_program,
                'id_pelatih' => $pelatih ? $pelatih->id_pelatih : null,
                'id_sanggar' => $sanggar ? $sanggar->id_sanggar : null,
                'hari' => 'Sabtu',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '13:00:00',
                'lokasi' => 'Sanggar Utama'
            ],
            [
                'id_program' => $seniTari->id_program,
                'id_pelatih' => $pelatih ? $pelatih->id_pelatih : null,
                'id_sanggar' => $sanggar ? $sanggar->id_sanggar : null,
                'hari' => 'Minggu',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '13:00:00',
                'lokasi' => 'Sanggar Utama'
            ],
            // Karawitan: Sabtu & Minggu (13.00 – 17.00 WIB)
            [
                'id_program' => $karawitan->id_program,
                'id_pelatih' => $pelatih ? $pelatih->id_pelatih : null,
                'id_sanggar' => $sanggar ? $sanggar->id_sanggar : null,
                'hari' => 'Sabtu',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '17:00:00',
                'lokasi' => 'Sanggar Utama'
            ],
            [
                'id_program' => $karawitan->id_program,
                'id_pelatih' => $pelatih ? $pelatih->id_pelatih : null,
                'id_sanggar' => $sanggar ? $sanggar->id_sanggar : null,
                'hari' => 'Minggu',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '17:00:00',
                'lokasi' => 'Sanggar Utama'
            ]
        ];

        foreach ($schedules as $schedule) {
            JadwalLatihan::create($schedule);
        }
    }
}
