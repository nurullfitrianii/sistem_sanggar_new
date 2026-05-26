<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramKelas;
use App\Models\LaporanKeuangan;
use App\Models\Prestasi;
use App\Models\Pelatih;
use App\Models\Sanggar;
use App\Models\Transaction;

class KetuaController extends Controller
{
    public function index()
    {
        // Grafik Keuangan (Trend 6 Bulan) - Samain sama Bendahara
        $chartData = [
            'labels' => [],
            'pemasukan' => [],
            'pengeluaran' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $chartData['labels'][] = $month->translatedFormat('M Y');
            $chartData['pemasukan'][] = Transaction::where('jenis', 'Masuk')
                ->whereMonth('tanggal', $month->month)->whereYear('tanggal', $month->year)->sum('nominal');
            $chartData['pengeluaran'][] = Transaction::where('jenis', 'Keluar')
                ->whereMonth('tanggal', $month->month)->whereYear('tanggal', $month->year)->sum('nominal');
        }

        // Grafik Kehadiran Siswa (Trend 7 Hari Terakhir)
        $attendanceTrend = [
            'labels' => [],
            'data' => []
        ];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $attendanceTrend['labels'][] = $date->translatedFormat('d M');
            $attendanceTrend['data'][] = \App\Models\Absensi::whereDate('waktu_hadir', $date->toDateString())->count();
        }

        return view('dashboard.dashboard-ketua', [
            'totalSiswaAktif'    => User::where('role', 'Siswa')->where('status', 'Aktif')->count(),
            'totalProgram'       => ProgramKelas::count(),
            'totalPemasukan'     => Transaction::where('jenis', 'Masuk')->sum('nominal'),
            'totalPrestasi'      => Prestasi::count(),
            'totalPelatih'       => Pelatih::count(),
            'totalSanggar'       => Sanggar::count(),
            'prestasiTerbaru'    => Prestasi::with('sanggar')->orderByDesc('tahun')->take(5)->get(),
            'chartData'          => $chartData,
            'attendanceTrend'    => $attendanceTrend,
        ]);
    }
}
