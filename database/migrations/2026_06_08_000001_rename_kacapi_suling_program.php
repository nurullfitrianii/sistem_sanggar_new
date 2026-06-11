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
        DB::table('program_kelas')
            ->where('id_program', 6)
            ->update([
                'nama_program' => 'Kecapi, Suling',
                'slug'         => 'kecapi-suling',
                'deskripsi'    => 'Belajar memainkan kecapi dan suling tradisional Sunda secara mendalam'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
