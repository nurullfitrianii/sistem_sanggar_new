<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramKelas;
use App\Models\JadwalLatihan;
use App\Models\Absensi;
use App\Models\LaporanKeuangan;
use App\Models\Galeri;
use App\Models\InformasiBerita;
use App\Models\Prestasi;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function ketua()
    {
        return view('dashboard.dashboard-ketua', [
            'totalSiswaAktif'    => User::where('role', 'Siswa')->where('status', 'Aktif')->count(),
            'totalProgram'       => ProgramKelas::count(),
            'totalPemasukan'     => LaporanKeuangan::sum('total_pemasukan'),
            'totalPrestasi'      => Prestasi::count(),
            'totalPelatih'       => \App\Models\Pelatih::count(),
            'totalSanggar'       => \App\Models\Sanggar::count(),
            'prestasiTerbaru'    => Prestasi::with('sanggar')->orderByDesc('tahun')->take(5)->get(),
        ]);
    }

    public function humas()
    {
        return view('dashboard.humas', [
            'totalProgram'  => ProgramKelas::count(),
            'totalGaleri'   => Galeri::count(),
            'totalBerita'   => InformasiBerita::count(),
            'beritaTerbaru' => InformasiBerita::orderByDesc('tanggal_publish')->take(5)->get(),
        ]);
    }

    public function bendahara()
    {
        return view('dashboard.bendahara', [
            'totalLaporan'     => LaporanKeuangan::count(),
            'totalPemasukan'   => LaporanKeuangan::sum('total_pemasukan'),
            'totalPengeluaran' => LaporanKeuangan::sum('total_pengeluaran'),
            'laporanTerbaru'   => LaporanKeuangan::orderByDesc('periode')->take(5)->get(),
        ]);
    }

    public function siswa()
    {
        $user = auth()->user();

        return view('dashboard.anggota', [
            'jadwalSaya'     => JadwalLatihan::with(['programKelas', 'pelatih', 'sanggar'])->orderBy('hari')->get(),
            'riwayatAbsensi' => Absensi::with('jadwalLatihan.programKelas')
                ->where('id_user', $user->id_user)
                ->orderByDesc('waktu_hadir')
                ->limit(10)
                ->get(),
            'riwayatPembayaran' => Pembayaran::where('id_user', $user->id_user)->orderByDesc('tanggal_bayar')->limit(5)->get(),
        ]);
    }
}
