<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanggarSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sanggar')->insert([
            'nama_sanggar' => 'Sanggar Goong Prasasti',
            'alamat'       => 'Jl. Raya Pramuka Gg. Tawes, RT.29/RW.09 Kel, Sukamelang, Kec, Kabupaten Subang, Jawa Barat 41251',
            'email'        => 'sanggargoongprasasti@gmail.com',
            'no_hp'        => '+62 898-7164-339',
            'visi'         => 'Menjadi sanggar seni yang aktif dalam melestarikan, mengembangkan, dan memperkenalkan seni budaya Sunda kepada masyarakat luas.',
            'misi'         => '1. Membina generasi muda dalam bidang seni tari dan karawitan. 2. Melestarikan kesenian tradisional Sunda agar tetap dikenal dan diminati. 3. Mengadakan pelatihan, pertunjukan, dan kegiatan seni budaya secara berkelanjutan. 4. Menjadi wadah kreativitas bagi para pelaku seni di lingkungan masyarakat.',
        ]);
    }
}
