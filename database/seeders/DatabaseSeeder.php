<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ProgramKelas;
use App\Models\Sanggar;
use App\Models\Pelatih;
use App\Models\JadwalLatihan;
use App\Models\Pendaftaran;
use App\Models\Transaction;
use App\Models\TransaksiIuran;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();
        DB::table('program_kelas')->truncate();
        DB::table('sanggar')->truncate();
        DB::table('pelatih')->truncate();
        DB::table('jadwal_latihan')->truncate();
        DB::table('pendaftaran')->truncate();
        DB::table('transactions')->truncate();
        DB::table('transaksi_iurans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // 1. SANGGAR
        $sanggarId = DB::table('sanggar')->insertGetId([
            'nama_sanggar' => 'Sanggar Goong Prasasti',
            'alamat'       => 'Sumedang, Jawa Barat',
            'email'        => 'goongprasasti@gmail.com',
            'no_hp'        => '08123456789',
            'visi'         => 'Melestarikan budaya Sunda melalui seni pertunjukan.',
            'misi'         => 'Mendidik generasi muda dalam seni tari dan karawitan.'
        ]);

        // 2. PROGRAM KELAS
        ProgramKelas::insert([
            [
                'id_program'   => 1,
                'nama_program' => 'Tari Tradisional Jaipong',
                'slug'         => 'tari-tradisional-jaipong',
                'kategori'     => 'Seni Tari',
                'deskripsi'    => 'Kelas tari jaipong untuk pemula hingga mahir',
                'biaya'        => 150000.00,
                'durasi'       => null,
                'jumlah_sesi'  => null,
                'status'       => 'Aktif',
            ],
            [
                'id_program'   => 2,
                'nama_program' => 'Tari Klasik Sunda',
                'slug'         => 'tari-klasik-sunda',
                'kategori'     => 'Seni Tari',
                'deskripsi'    => 'Kelas tari klasik sunda untuk semua usia',
                'biaya'        => 150000.00,
                'durasi'       => null,
                'jumlah_sesi'  => null,
                'status'       => 'Aktif',
            ],
            [
                'id_program'   => 3,
                'nama_program' => 'Karawitan (Kendang, Gamelan)',
                'slug'         => 'karawitan-kendang-gamelan',
                'kategori'     => 'Seni Karawitan',
                'deskripsi'    => 'Belajar memainkan kendang dan gamelan Sunda secara terpadu',
                'biaya'        => 150000.00,
                'durasi'       => '2 Jam',
                'jumlah_sesi'  => null,
                'status'       => 'Aktif',
            ],
            [
                'id_program'   => 5,
                'nama_program' => 'Karawitan - Vokal Kawih',
                'slug'         => 'karawitan-vokal-kawih',
                'kategori'     => 'Seni Karawitan',
                'deskripsi'    => 'Belajar vokal seni kawih Sunda',
                'biaya'        => 150000.00,
                'durasi'       => null,
                'jumlah_sesi'  => null,
                'status'       => 'Aktif',
            ],
            [
                'id_program'   => 6,
                'nama_program' => 'Kerawitan (Kecapi, Suling)',
                'slug'         => 'kerawitan-kecapi-suling',
                'kategori'     => 'Seni Karawitan',
                'deskripsi'    => 'Belajar memainkan kecapi dan suling tradisional Sunda',
                'biaya'        => 150000.00,
                'durasi'       => '2 Jam',
                'jumlah_sesi'  => null,
                'status'       => 'Aktif',
            ],
        ]);

        // 3. PELATIH
        $dimasId = DB::table('pelatih')->insertGetId([
            'id_sanggar'   => $sanggarId,
            'nama_pelatih' => 'Dimas Patria',
            'bidang'       => 'Seni Tari',
            'no_hp'        => '089111222001'
        ]);
        $lutfiId = DB::table('pelatih')->insertGetId([
            'id_sanggar'   => $sanggarId,
            'nama_pelatih' => 'Lutfi Jamaludin',
            'bidang'       => 'Seni Tari',
            'no_hp'        => '089111222002'
        ]);
        $melsaId = DB::table('pelatih')->insertGetId([
            'id_sanggar'   => $sanggarId,
            'nama_pelatih' => 'Melsa Afrianti',
            'bidang'       => 'Seni Tari',
            'no_hp'        => '089111222003'
        ]);
        $auliaId = DB::table('pelatih')->insertGetId([
            'id_sanggar'   => $sanggarId,
            'nama_pelatih' => 'Aulia Rizki Wibowo',
            'bidang'       => 'Seni Tari',
            'no_hp'        => '089111222004'
        ]);
        $habiId = DB::table('pelatih')->insertGetId([
            'id_sanggar'   => $sanggarId,
            'nama_pelatih' => 'Habiburrahman Daniah',
            'bidang'       => 'Karawitan',
            'no_hp'        => '089111222005'
        ]);

        // 4. USERS
        User::insert([
            [
                'username' => 'admin_ketua',
                'password' => Hash::make('password123'),
                'role'     => 'Ketua',
                'status'   => 'Aktif',
            ],
            [
                'username' => 'admin_humas',
                'password' => Hash::make('password123'),
                'role'     => 'Humas',
                'status'   => 'Aktif',
            ],
            [
                'username' => 'admin_bendahara',
                'password' => Hash::make('password123'),
                'role'     => 'Bendahara',
                'status'   => 'Aktif',
            ],
            [
                'username' => 'siswa_test',
                'password' => Hash::make('password123'),
                'role'     => 'Siswa',
                'status'   => 'Aktif',
            ],
        ]);

        // 5. PENDAFTARAN (untuk siswa_test)
        Pendaftaran::create([
            'username'     => 'siswa_test',
            'nama_calon'   => 'Siswa Test',
            'id_program'   => 1,
            'no_hp'        => '08123456789',
            'alamat'       => 'Sumedang',
            'status'       => 'Diterima',
            'tanggal_daftar' => now()->subMonths(2)
        ]);

        // 6. JADWAL LATIHAN
        $pelatihTari = [$dimasId, $lutfiId, $melsaId, $auliaId];
        $programs = ProgramKelas::all();
        foreach ($programs as $p) {
            $isTari = str_contains(strtolower($p->kategori ?? ''), 'tari') || str_contains(strtolower($p->nama_program ?? ''), 'tari');
            $jamMulai = $isTari ? '10:00:00' : '13:00:00';
            $jamSelesai = $isTari ? '13:00:00' : '15:00:00';
            $lokasi = $isTari ? 'Studio A' : 'Pendopo';
            
            // Assign pelatih based on program type
            $pelatihId = $isTari ? $pelatihTari[array_rand($pelatihTari)] : $habiId;

            foreach (['Sabtu', 'Minggu'] as $h) {
                JadwalLatihan::create([
                    'id_program'  => $p->id_program,
                    'id_pelatih'  => $pelatihId,
                    'id_sanggar'  => $sanggarId,
                    'hari'         => $h,
                    'jam_mulai'    => $jamMulai,
                    'jam_selesai'  => $jamSelesai,
                    'lokasi'       => $lokasi,
                    'materi'       => 'Latihan Rutin'
                ]);
            }
        }

        // 7. TRANSACTIONS
        Transaction::insert([
            [
                'tanggal'    => now()->subDays(5),
                'jenis'      => 'Masuk',
                'nominal'    => 500000,
                'keterangan' => 'Donasi Sponsor ABC',
                'id_user'    => 1
            ],
            [
                'tanggal'    => now()->subDays(2),
                'jenis'      => 'Keluar',
                'nominal'    => 200000,
                'keterangan' => 'Pembelian Alat Musik',
                'id_user'    => 1
            ]
        ]);

        // 8. TRANSAKSI IURAN DUMMY
        $iuranDummy = [];
        
        // 5 data iuran bulanan
        for ($i = 1; $i <= 5; $i++) {
            $iuranDummy[] = [
                'user_id' => 4, // ID siswa_test
                'tipe_iuran' => 'bulanan',
                'metode_pembayaran' => 'transfer',
                'jumlah_bayar' => 150000,
                'bukti_bayar' => 'dummy_bukti.jpg',
                'status' => 'valid',
                'created_at' => now()->subDays($i * 30),
                'updated_at' => now()->subDays($i * 30),
            ];
        }

        // 5 data iuran mingguan
        for ($i = 1; $i <= 5; $i++) {
            $iuranDummy[] = [
                'user_id' => 4, // ID siswa_test
                'tipe_iuran' => 'mingguan',
                'metode_pembayaran' => 'tunai',
                'jumlah_bayar' => 30000,
                'bukti_bayar' => null,
                'status' => 'valid',
                'created_at' => now()->subDays($i * 7),
                'updated_at' => now()->subDays($i * 7),
            ];
        }

        TransaksiIuran::insert($iuranDummy);
    }
}
