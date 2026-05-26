<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramKelas;
use App\Models\JadwalLatihan;
use App\Models\Pelatih;
use App\Models\Sanggar;

class FixProgramSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelatih = Pelatih::first();
        $sanggar = Sanggar::first();

        $programs = ProgramKelas::where('status', 'Aktif')->get();

        foreach ($programs as $program) {
            // Hapus jadwal lama agar tidak duplikat
            JadwalLatihan::where('id_program', $program->id_program)->delete();

            $hariLatihan = ['Sabtu', 'Minggu'];
            
            if (str_contains(strtolower($program->kategori), 'tari')) {
                $jamMulai = '10:00:00';
                $jamSelesai = '13:00:00';
                $durasiText = '3 Jam';
            } else {
                // Default untuk Karawitan atau lainnya
                $jamMulai = '13:00:00';
                $jamSelesai = '17:00:00';
                $durasiText = '4 Jam';
            }

            // Update durasi di table program_kelas
            $program->update(['durasi' => $durasiText]);

            foreach ($hariLatihan as $hari) {
                JadwalLatihan::create([
                    'id_program' => $program->id_program,
                    'id_pelatih' => $pelatih ? $pelatih->id_pelatih : null,
                    'id_sanggar' => $sanggar ? $sanggar->id_sanggar : null,
                    'hari' => $hari,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'lokasi' => 'Sanggar Goong Prasasti'
                ]);
            }
        }
    }
}
