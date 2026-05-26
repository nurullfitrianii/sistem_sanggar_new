<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\LaporanKeuanganExport; // Library Export yang baru
use Maatwebsite\Excel\Facades\Excel;    // Library Excel
use Barryvdh\DomPDF\Facade\Pdf;         // Library PDF
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Menampilkan Halaman Laporan Keuangan di Web
     */
    public function keuangan(Request $request)
    {
        $query = Transaction::query()->orderBy('tanggal', 'desc');

        // Filter berdasarkan periode jika ada
        if ($request->filled('periode')) {
            $query->where('tanggal', 'like', '%' . $request->periode . '%');
        }

        $transaksi = $query->get();

        // Hitung total untuk ringkasan di dashboard laporan
        $totalPemasukan   = $transaksi->where('jenis', 'Masuk')->sum('nominal');
        $totalPengeluaran = $transaksi->where('jenis', 'Keluar')->sum('nominal');

        return view('laporan.keuangan', [
            'transaksi' => $transaksi,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran
        ]);
    }

    /**
     * Fungsi Export ke Excel (.xlsx) - Versi Rapi
     */
    public function exportExcel(Request $request)
    {
        $periode = $request->query('periode');

        // Memanggil LaporanKeuanganExport dengan parameter periode
        return Excel::download(
            new LaporanKeuanganExport($periode),
            'Laporan_Keuangan_Sanggar_' . ($periode ?? 'Semua') . '.xlsx'
        );
    }

    /**
     * Laporan Absensi
     */
    public function absensi(Request $request)
    {
        $query = Absensi::with(['user', 'jadwalLatihan.programKelas'])->orderByDesc('waktu_hadir');

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_hadir', $request->tanggal);
        }

        $absensi = $query->paginate(50);

        return view('laporan.absensi', compact('absensi'));
    }

    /**
     * Laporan Anggota
     */
    public function anggota()
    {
        $anggotaAktif    = User::where('role', 'Siswa')->where('status', 'Aktif')->orderBy('username')->get();
        $anggotaNonaktif = User::where('role', 'Siswa')->where('status', 'Nonaktif')->orderBy('username')->get();

        return view('laporan.anggota', compact('anggotaAktif', 'anggotaNonaktif'));
    }

    /**
     * Export Laporan Absensi ke PDF
     */
    public function exportAbsensiPDF(Request $request)
    {
        $query = Absensi::with(['user', 'jadwalLatihan.programKelas'])->orderByDesc('waktu_hadir');

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_hadir', $request->tanggal);
        }

        $absensi = $query->get();

        $pdf = Pdf::loadView('laporan.absensi_pdf', compact('absensi'));
        return $pdf->download('Laporan_Absensi_' . date('Y-m-d') . '.pdf');
    }
}
