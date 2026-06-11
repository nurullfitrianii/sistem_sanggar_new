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
     * Export Laporan Keuangan ke PDF
     */
    public function exportKeuanganPDF(Request $request)
    {
        $query = Transaction::query()->orderBy('tanggal', 'desc');

        if ($request->filled('periode')) {
            $query->where('tanggal', 'like', '%' . $request->periode . '%');
        }

        $transaksi = $query->get();
        $totalPemasukan   = $transaksi->where('jenis', 'Masuk')->sum('nominal');
        $totalPengeluaran = $transaksi->where('jenis', 'Keluar')->sum('nominal');

        $pdf = Pdf::loadView('laporan.keuangan_pdf', compact('transaksi', 'totalPemasukan', 'totalPengeluaran'));
        return $pdf->download('Laporan_Keuangan_Sanggar_' . ($request->periode ?? 'Semua') . '.pdf');
    }

    /**
     * Laporan Absensi
     */
    public function absensi(Request $request)
    {
        $query = Absensi::with(['user.pendaftaran.programKelas', 'jadwalLatihan.programKelas'])->orderByDesc('waktu_hadir');

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_hadir', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('id_program')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('jadwalLatihan', function ($sub) use ($request) {
                    $sub->where('id_program', $request->id_program);
                })->orWhereHas('user.pendaftaran', function ($sub) use ($request) {
                    $sub->where('id_program', $request->id_program);
                });
            });
        }

        $absensi = $query->paginate(20)->withQueryString();
        $programs = \App\Models\ProgramKelas::orderBy('nama_program')->get();

        return view('laporan.absensi', compact('absensi', 'programs'));
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
        $query = Absensi::with(['user.pendaftaran.programKelas', 'jadwalLatihan.programKelas'])->orderByDesc('waktu_hadir');

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_hadir', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('id_program')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('jadwalLatihan', function ($sub) use ($request) {
                    $sub->where('id_program', $request->id_program);
                })->orWhereHas('user.pendaftaran', function ($sub) use ($request) {
                    $sub->where('id_program', $request->id_program);
                });
            });
        }

        $absensi = $query->get();

        $pdf = Pdf::loadView('laporan.absensi_pdf', compact('absensi'));
        return $pdf->download('Laporan_Absensi_Ketua_' . ($request->tanggal ?? 'Semua') . '.pdf');
    }

    /**
     * Export Laporan Absensi ke Excel
     */
    public function exportAbsensiExcel(Request $request)
    {
        $tanggal = $request->query('tanggal');
        $status = $request->query('status');
        $id_program = $request->query('id_program');
        return Excel::download(
            new \App\Exports\LaporanAbsensiExport($tanggal, $status, $id_program),
            'Laporan_Absensi_Ketua_' . ($tanggal ?? 'Semua') . '.xlsx'
        );
    }
}
