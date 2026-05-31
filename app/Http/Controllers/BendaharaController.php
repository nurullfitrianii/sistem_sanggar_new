<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Pembayaran;
use App\Models\TransaksiIuran;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\PembayaranBerhasil;
use Illuminate\Support\Facades\Mail;

class BendaharaController extends Controller
{
    public function index()
    {
        // AUTO-SYNC: Pastikan pendaftaran yang sudah Aktif dan Lunas tercatat di Transaksi
        $pendaftaranAktif = Pendaftaran::with('programKelas')
            ->where('status', 'Aktif')
            ->where('status_pembayaran', 'success')
            ->get();
        foreach ($pendaftaranAktif as $p) {
            $keterangan = 'Pendaftaran Siswa: ' . $p->nama_calon . ' (' . ($p->programKelas->nama_program ?? 'Program') . ')';
            $nominal = $p->programKelas->biaya ?? 0;

            $trxExists = Transaction::where('keterangan', $keterangan)
                ->where('nominal', $nominal)
                ->exists();

            if (!$trxExists) {
                // Cari id_user dari tabel users jika di pendaftaran kosong
                $id_user = $p->id_user;
                if (!$id_user) {
                    $userFound = \App\Models\User::where('email', $p->email)->first();
                    $id_user = $userFound ? $userFound->id_user : 1; // Fallback ke Admin (ID 1) jika tidak ketemu
                }

                Transaction::create([
                    'tanggal'    => $p->tanggal_daftar ?? now(),
                    'jenis'      => 'Masuk',
                    'nominal'    => $nominal,
                    'keterangan' => $keterangan,
                    'id_user'    => $id_user,
                ]);
            }
        }

        // AUTO-SYNC: Pastikan tagihan iuran bulanan untuk semua siswa aktif disinkronkan ke database
        $this->syncMonthlyBills();

        // Ambil data tunggakan iuran
        $siswaList = \App\Models\User::where('role', 'Siswa')
            ->where('status', 'Aktif')
            ->with(['pendaftaran.programKelas', 'pembayaran' => function($q) {
                $q->whereNotIn(\Illuminate\Support\Facades\DB::raw('LOWER(status)'), ['lunas', 'success', 'settlement']);
            }])
            ->get();

        $tunggakanList = [];
        $totalNominalTunggakan = 0;
        foreach ($siswaList as $siswa) {
            $unpaidPayments = $siswa->pembayaran;
            if ($unpaidPayments->isEmpty()) {
                continue;
            }

            $unpaidMonths = [];
            $totalSiswaTunggakan = 0;
            foreach ($unpaidPayments as $payment) {
                $unpaidMonths[] = [
                    'bulan' => $payment->bulan,
                    'jumlah' => $payment->jumlah,
                    'status' => $payment->status
                ];
                $totalSiswaTunggakan += $payment->jumlah;
            }

            $tunggakanList[] = (object)[
                'id_user'        => $siswa->id_user,
                'username'       => $siswa->username,
                'nama_lengkap'   => $siswa->pendaftaran->nama_calon ?? $siswa->username,
                'no_hp'          => $siswa->pendaftaran->no_hp ?? null,
                'program'        => $siswa->pendaftaran->programKelas->nama_program ?? 'Umum',
                'unpaid_months'  => $unpaidMonths,
                'total_arrears'  => $totalSiswaTunggakan,
            ];

            $totalNominalTunggakan += $totalSiswaTunggakan;
        }

        usort($tunggakanList, function($a, $b) {
            return $b->total_arrears <=> $a->total_arrears;
        });

        $chartData = [
            'labels' => [],
            'pemasukan' => [],
            'pengeluaran' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartData['labels'][] = $month->translatedFormat('M Y');
            $chartData['pemasukan'][] = Transaction::where('jenis', 'Masuk')
                ->whereMonth('tanggal', $month->month)->whereYear('tanggal', $month->year)->sum('nominal');
            $chartData['pengeluaran'][] = Transaction::where('jenis', 'Keluar')
                ->whereMonth('tanggal', $month->month)->whereYear('tanggal', $month->year)->sum('nominal');
        }

        $laporanTerbaru = Transaction::orderByDesc('tanggal')->take(5)->get();

        foreach ($laporanTerbaru as $trx) {
            if (preg_match('/^(?:Iuran \w+|Pembayaran \w+|Pendaftaran Siswa):\s*([^(]+)/i', $trx->keterangan, $matches)) {
                $trx->nama_pembayar = trim($matches[1]);
                $trx->tipe_pembayar = 'Siswa';
            } else {
                $trx->nama_pembayar = $trx->user->username ?? 'Sistem';
                $trx->tipe_pembayar = $trx->user->role ?? 'Manual';
            }
        }

        $pembayaranPending = Pembayaran::with('user')
            ->where('status', 'Menunggu Verifikasi')
            ->orderBy('id_pembayaran', 'desc')
            ->get();

        $iuranMingguanPending = TransaksiIuran::with('user')
            ->where('status', 'valid')
            ->where('tipe_iuran', 'mingguan')
            ->orderBy('created_at', 'desc')
            ->get();

        // Gabungkan iuran bulanan dari TransaksiIuran + Pembayaran (sinkron dengan Data Iuran)
        $iuranBulananTransaksi = TransaksiIuran::with('user')
            ->where('status', 'valid')
            ->where('tipe_iuran', 'bulanan')
            ->orderBy('created_at', 'desc')
            ->get();

        $iuranBulananPembayaran = Pembayaran::with('user')
            ->where('status', 'Lunas')
            ->whereNotNull('tanggal_bayar')
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        $iuranBulananPending = collect()
            ->merge($iuranBulananTransaksi->map(fn($i) => (object)[
                'user'         => $i->user,
                'tipe_iuran'   => $i->tipe_iuran,
                'jumlah_bayar' => $i->jumlah_bayar,
                'created_at'   => $i->created_at,
            ]))
            ->merge($iuranBulananPembayaran->map(fn($p) => (object)[
                'user'         => $p->user,
                'tipe_iuran'   => 'bulanan',
                'jumlah_bayar' => $p->jumlah,
                'created_at'   => $p->tanggal_bayar,
            ]))
            ->sortByDesc('created_at')
            ->values();

        return view('dashboard.bendahara', [
            'totalLaporan'         => Transaction::count(),
            'totalPemasukan'       => Transaction::where('jenis', 'Masuk')->sum('nominal'),
            'totalPengeluaran'     => Transaction::where('jenis', 'Keluar')->sum('nominal'),
            'laporanTerbaru'       => $laporanTerbaru,
            'chartData'            => $chartData,
            'pembayaranPending'    => $pembayaranPending,
            'iuranMingguanPending' => $iuranMingguanPending,
            'iuranBulananPending'  => $iuranBulananPending,
            'totalPending'         => $pembayaranPending->count() + TransaksiIuran::where('status', 'pending')->count(),
            'tunggakanList'         => $tunggakanList,
            'totalNominalTunggakan' => $totalNominalTunggakan,
        ]);
    }

    public function verifikasiIndex()
    {
        $unifiedList = collect();

        $pembayaranPending = Pembayaran::with('user')
            ->where('status', 'Menunggu Verifikasi')
            ->get();

        foreach ($pembayaranPending as $p) {
            $unifiedList->push((object)[
                'id_item'            => $p->id_pembayaran,
                'type'               => 'pembayaran',
                'kategori'           => ($p->tipe_iuran == 'pendaftaran' ? 'Pendaftaran' : 'Iuran ' . ucfirst($p->tipe_iuran ?? 'Bulanan')),
                'user'               => $p->user,
                'jumlah'             => $p->jumlah,
                'metode'             => $p->metode_pembayaran ?? 'transfer',
                'bukti_bayar'        => $p->bukti_bayar,
                'tanggal'            => $p->created_at ?? now(),
                'keterangan_periode' => $p->bulan
            ]);
        }

        // TAMBAHAN: Ambil dari tabel Pendaftaran (untuk yang baru daftar: pending transfer)
        $pendaftaranBaru = Pendaftaran::with('programKelas')
            ->where('status_pembayaran', 'pending')
            ->where('status', 'Menunggu')
            ->get();

        foreach ($pendaftaranBaru as $pt) {
            $unifiedList->push((object)[
                'id_item'            => $pt->id_pendaftaran,
                'type'               => 'pendaftaran_awal',
                'kategori'           => 'Pendaftaran Awal',
                'user'               => (object)['username' => $pt->nama_calon, 'id_user' => $pt->id_user ?? '-'],
                'jumlah'             => $pt->programKelas->biaya ?? 0,
                'metode'             => $pt->metode_pembayaran ?? 'N/A',
                'bukti_bayar'        => null,
                'tanggal'            => $pt->tanggal_daftar ?? now(),
                'keterangan_periode' => 'Pendaftaran'
            ]);
        }

        $iuranPending = TransaksiIuran::with('user')
            ->where('status', 'pending')
            ->get();

        foreach ($iuranPending as $i) {
            $unifiedList->push((object)[
                'id_item'            => $i->id,
                'type'               => 'iuran',
                'kategori'           => 'Iuran ' . ucfirst($i->tipe_iuran),
                'user'               => $i->user,
                'jumlah'             => $i->jumlah_bayar,
                'metode'             => $i->metode_pembayaran,
                'bukti_bayar'        => $i->bukti_bayar,
                'tanggal'            => $i->created_at,
                'keterangan_periode' => '-'
            ]);
        }

        $unifiedList = $unifiedList->sortByDesc('tanggal')->values();

        return view('dashboard.bendahara_verifikasi', [
            'unifiedList'  => $unifiedList,
            'totalPending' => $unifiedList->count(),
        ]);
    }

    public function verifikasi(Request $request, $type, $id, $aksi)
    {
        if ($type === 'pendaftaran_awal') {
            $pendaftaran = Pendaftaran::with('programKelas')->findOrFail($id);
            if ($aksi == 'terima') {
                $pendaftaran->update([
                    'status' => 'Aktif',
                    'status_pembayaran' => 'success'
                ]);

                // Aktifkan User terkait
                if ($pendaftaran->email) {
                    \App\Models\User::where('email', $pendaftaran->email)->update(['status' => 'Aktif']);
                }

                // Record Transaction
                $keterangan = 'Pendaftaran Siswa: ' . $pendaftaran->nama_calon . ' (' . ($pendaftaran->programKelas->nama_program ?? 'Program') . ')';
                $nominal = $pendaftaran->programKelas->biaya ?? 0;

                $trxExists = Transaction::where('nominal', $nominal)
                    ->where('keterangan', $keterangan)
                    ->exists();

                if (!$trxExists) {
                    Transaction::create([
                        'tanggal'    => now(),
                        'jenis'      => 'Masuk',
                        'nominal'    => $nominal,
                        'keterangan' => $keterangan,
                        'id_user'    => $pendaftaran->id_user,
                    ]);
                }

                return redirect()->back()->with('success', 'Pembayaran tunai pendaftaran berhasil diverifikasi.');
            } else {
                $pendaftaran->update(['status_pembayaran' => 'failed']);
                return redirect()->back()->with('error', 'Pembayaran pendaftaran ditolak.');
            }
        }

        // Default type: pembayaran (model Pembayaran)
        $pembayaran = Pembayaran::with('user')->findOrFail($id);

        if ($aksi == 'terima') {
            $pembayaran->update([
                'status'        => 'Lunas',
                'tanggal_bayar' => now()
            ]);

            $tipe = ucfirst($pembayaran->tipe_iuran ?? 'Bulanan');
            $keterangan = "Pembayaran $tipe: " . ($pembayaran->user->username ?? 'Siswa') . ' (' . $pembayaran->bulan . ')';
            
            $trxExists = Transaction::where('nominal', $pembayaran->jumlah)
                ->where('keterangan', $keterangan)
                ->exists();

            if (!$trxExists) {
                Transaction::create([
                    'tanggal'    => now(),
                    'jenis'      => 'Masuk',
                    'nominal'    => $pembayaran->jumlah,
                    'keterangan' => $keterangan,
                    'id_user'    => $pembayaran->user->id_user ?? null,
                ]);
            }

            $user = $pembayaran->user;
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new PembayaranBerhasil(
                        namaCalon  : $user->username,
                        namaProgram: 'Sanggar Goong Prasasti',
                        jumlah     : 'Rp ' . number_format($pembayaran->jumlah, 0, ',', '.'),
                        tanggal    : now()->format('d M Y')
                    ));
                } catch (\Exception $e) {
                    \Log::error('Gagal kirim email pembayaran: ' . $e->getMessage());
                }
            }

            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi dan email notifikasi telah dikirim.');
        } else {
            $pembayaran->update(['status' => 'Ditolak']);
            return redirect()->back()->with('error', 'Pembayaran telah ditolak.');
        }
    }

    /**
     * Iuran Mingguan dari transaksi_iurans (bukan tabel pembayaran)
     * Iuran Bulanan gabungan dari transaksi_iurans + pembayaran yang Lunas
     */
    public function indexIuran()
    {
        $iuranMingguanValid = TransaksiIuran::with('user')
            ->where('status', 'valid')
            ->where('tipe_iuran', 'mingguan')
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        $iuranBulananTransaksi = collect(
            TransaksiIuran::with('user')
                ->where('status', 'valid')
                ->where('tipe_iuran', 'bulanan')
                ->orderBy('tanggal_bayar', 'desc')
                ->get()
                ->map(fn($i) => (object)[
                    'user'              => $i->user,
                    'metode_pembayaran' => $i->metode_pembayaran,
                    'tanggal_bayar'     => $i->tanggal_bayar,
                    'jumlah_bayar'      => $i->jumlah_bayar,
                ])
                ->all()
        );

        $iuranBulananPembayaran = collect(
            Pembayaran::with('user')
                ->where('status', 'Lunas')
                ->whereNotNull('tanggal_bayar')
                ->orderBy('tanggal_bayar', 'desc')
                ->get()
                ->map(fn($p) => (object)[
                    'user'              => $p->user,
                    'metode_pembayaran' => $p->metode_pembayaran ?? 'N/A',
                    'tanggal_bayar'     => $p->tanggal_bayar,
                    'jumlah_bayar'      => $p->jumlah,
                ])
                ->all()
        );

        $iuranBulananMerged = $iuranBulananTransaksi
            ->merge($iuranBulananPembayaran)
            ->sortByDesc('tanggal_bayar')
            ->values();

        return view('bendahara.iuran.index', compact('iuranMingguanValid', 'iuranBulananMerged'));
    }

    /**
     * Set tanggal_bayar otomatis + insert ke transactions + kirim email saat validasi iuran
     */
    public function updateStatusIuran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:valid,ditolak'
        ]);

        $iuran = TransaksiIuran::with('user')->findOrFail($id);

        if ($request->status === 'valid') {
            $iuran->update([
                'status'        => 'valid',
                'tanggal_bayar' => now(),
            ]);

            $keteranganTrx = 'Iuran ' . ucfirst($iuran->tipe_iuran) . ': ' . ($iuran->user->username ?? 'Siswa');
            $trxExists = Transaction::where('nominal', $iuran->jumlah_bayar)
                ->where('keterangan', $keteranganTrx)
                ->whereDate('tanggal', now()->toDateString())
                ->exists();

            if (!$trxExists) {
                Transaction::create([
                    'tanggal'    => now(),
                    'jenis'      => 'Masuk',
                    'nominal'    => $iuran->jumlah_bayar,
                    'keterangan' => $keteranganTrx,
                    'id_user'    => $iuran->user->id_user,
                ]);
            }

            $user = $iuran->user;
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new PembayaranBerhasil(
                        namaCalon  : $user->username,
                        namaProgram: 'Sanggar Goong Prasasti',
                        jumlah     : 'Rp ' . number_format($iuran->jumlah_bayar, 0, ',', '.'),
                        tanggal    : now()->format('d M Y')
                    ));
                } catch (\Exception $e) {
                    \Log::error('Gagal kirim email iuran: ' . $e->getMessage());
                }
            }

            return redirect()->back()->with('success', 'Iuran divalidasi, tersinkronisasi dengan Laporan Keuangan, dan email notifikasi telah dikirim.');
        } else {
            $iuran->update(['status' => 'ditolak']);
            return redirect()->back()->with('error', 'Iuran ditolak.');
        }
    }

    private function syncMonthlyBills()
    {
        $siswaList = \App\Models\User::where('role', 'Siswa')->where('status', 'Aktif')->get();
        $now = \Carbon\Carbon::now();
        $currentMonthName = $now->translatedFormat('F Y');

        foreach ($siswaList as $siswa) {
            $pendaftaran = \App\Models\Pendaftaran::with('programKelas')->where('username', $siswa->username)->first();
            if (!$pendaftaran || !$pendaftaran->tanggal_daftar) {
                continue;
            }

            // 1. Pendaftaran Awal
            $regExists = Pembayaran::where('id_user', $siswa->id_user)
                ->where('bulan', 'Pendaftaran Awal')
                ->first();
            
            $statusPendaftaran = strtolower($pendaftaran->status_pembayaran ?? '');
            // Siswa yang berstatus Aktif pendaftaran awalnya pasti sudah lunas
            $isPaid = in_array($statusPendaftaran, ['success', 'settlement', 'lunas']) || $siswa->status === 'Aktif';

            if (!$regExists) {
                $statusReg = $isPaid ? 'Lunas' : 'Belum Bayar';
                Pembayaran::create([
                    'id_user' => $siswa->id_user,
                    'bulan'   => 'Pendaftaran Awal',
                    'jumlah'  => $pendaftaran->programKelas ? $pendaftaran->programKelas->biaya : 0,
                    'status'  => $statusReg,
                    'tanggal_bayar' => ($statusReg === 'Lunas') ? $pendaftaran->tanggal_daftar : null,
                    'tipe_iuran' => 'pendaftaran',
                    'metode_pembayaran' => $pendaftaran->metode_pembayaran ?? 'N/A'
                ]);
            } else {
                if (strtolower($regExists->status) === 'belum bayar' && $isPaid) {
                    $regExists->update([
                        'status' => 'Lunas',
                        'tanggal_bayar' => $pendaftaran->tanggal_daftar ?? now(),
                        'metode_pembayaran' => $pendaftaran->metode_pembayaran ?? $regExists->metode_pembayaran
                    ]);
                }
            }

            // 2. Iuran Bulanan
            $startDate = \Carbon\Carbon::parse($pendaftaran->tanggal_daftar);
            $current = $startDate->copy()->startOfMonth();
            while ($current->lte($now->copy()->startOfMonth())) {
                $monthName = $current->translatedFormat('F Y');

                $pembayaran = Pembayaran::where('id_user', $siswa->id_user)
                    ->where('bulan', $monthName)
                    ->first();

                if (!$pembayaran) {
                    Pembayaran::create([
                        'id_user' => $siswa->id_user,
                        'bulan'   => $monthName,
                        'jumlah'  => 100000,
                        'status'  => 'Belum Bayar',
                        'tipe_iuran' => 'bulanan'
                    ]);
                } else {
                    if ($monthName === $currentMonthName && $pembayaran->status === 'Lunas' && empty($pembayaran->metode_pembayaran)) {
                        $pembayaran->update([
                            'status' => 'Belum Bayar',
                            'tanggal_bayar' => null
                        ]);
                    }

                    if (strtolower($pembayaran->status) == 'belum bayar' && $pembayaran->jumlah != 100000) {
                        $pembayaran->update(['jumlah' => 100000]);
                    }
                }
                $current->addMonth();
            }
        }
    }
}
