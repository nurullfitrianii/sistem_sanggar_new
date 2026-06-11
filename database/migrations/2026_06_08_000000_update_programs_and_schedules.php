<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 1. Rename ID 3: 'Karawitan - Gamelan & Degung' -> 'Karawitan (Kendang, Gamelan)'
        DB::table('program_kelas')->where('id_program', 3)->update([
            'nama_program' => 'Karawitan (Kendang, Gamelan)',
            'slug'         => 'karawitan-kendang-gamelan',
            'deskripsi'    => 'Belajar memainkan kendang dan gamelan Sunda secara terpadu',
            'biaya'        => 150000.00,
            'durasi'       => '2 Jam',
            'status'       => 'Aktif'
        ]);

        // 2. Migrate students (pendaftaran) from ID 4 (Karawitan - Kendang) to ID 3
        DB::table('pendaftaran')->where('id_program', 4)->update([
            'id_program' => 3
        ]);

        // 3. Migrate schedules (jadwal_latihan) from ID 4 to ID 3
        DB::table('jadwal_latihan')->where('id_program', 4)->update([
            'id_program' => 3
        ]);

        // 4. Delete ID 4 (Karawitan - Kendang) from program_kelas
        DB::table('program_kelas')->where('id_program', 4)->delete();

        // 5. Rename ID 6: 'Karawitan - Kacapi' -> 'Kacapi Suling'
        DB::table('program_kelas')->where('id_program', 6)->update([
            'nama_program' => 'Kacapi Suling',
            'slug'         => 'kacapi-suling',
            'deskripsi'    => 'Belajar memainkan kacapi dan suling tradisional Sunda',
            'biaya'        => 150000.00,
            'durasi'       => '2 Jam',
            'status'       => 'Aktif'
        ]);

        // 6. Set pricing for all active class programs to 150.000
        DB::table('program_kelas')->where('status', 'Aktif')->update([
            'biaya' => 150000.00
        ]);

        // 7. Update existing Karawitan schedules from 13:00 - 17:00 to 13:00 - 15:00
        // Karawitan category is 'Seni Karawitan'. Let's find program IDs under 'Seni Karawitan'
        $karawitanIds = DB::table('program_kelas')
            ->where('kategori', 'Seni Karawitan')
            ->pluck('id_program')
            ->toArray();

        if (!empty($karawitanIds)) {
            DB::table('jadwal_latihan')
                ->whereIn('id_program', $karawitanIds)
                ->where('jam_selesai', '17:00:00')
                ->update([
                    'jam_selesai' => '15:00:00'
                ]);
        }

        // Enable foreign key checks back
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert is not strictly needed for this auto-migration context, but we keep it empty or simple
    }
};
