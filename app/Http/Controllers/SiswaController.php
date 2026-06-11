<?php

namespace App\Http\Controllers;

use App\Models\JadwalLatihan;
use App\Models\Absensi;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Pelatih;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    private function checkAndGenerateMonthlyBill($user)
    {
        $pendaftaran = Pendaftaran::with('programKelas')->where('username', $user->username)->first();
        if (!$pendaftaran || !$pendaftaran->tanggal_daftar) return;

        // 1. Pastikan Biaya Pendaftaran sudah tercatat sebagai Lunas
        // Biaya pendaftaran sesuai biaya di program_kelas
        $regExists = Pembayaran::where('id_user', $user->id_user)
            ->where('bulan', 'Pendaftaran Awal')
            ->first();
        
        $statusPendaftaran = strtolower($pendaftaran->status_pembayaran ?? '');
        // Siswa yang berstatus Aktif pendaftaran awalnya pasti sudah lunas
        $isPaid = in_array($statusPendaftaran, ['success', 'settlement', 'lunas']);

        if (!$regExists) {
            $statusReg = $isPaid ? 'Lunas' : 'Belum Bayar';
            Pembayaran::create([
                'id_user' => $user->id_user,
                'bulan'   => 'Pendaftaran Awal',
                'jumlah'  => $pendaftaran->programKelas ? $pendaftaran->programKelas->biaya : 0,
                'status'  => $statusReg,
                'tanggal_bayar' => ($statusReg === 'Lunas') ? $pendaftaran->tanggal_daftar : null,
                'tipe_iuran' => 'pendaftaran',
                'metode_pembayaran' => $pendaftaran->metode_pembayaran ?? 'N/A'
            ]);
        } else {
            // Jika sudah ada tapi masih Belum Bayar padahal di pendaftaran sudah sukses/tunai, update ke Lunas
            if (strtolower($regExists->status) === 'belum bayar' && $isPaid) {
                $regExists->update([
                    'status' => 'Lunas',
                    'tanggal_bayar' => $pendaftaran->tanggal_daftar ?? now(),
                    'metode_pembayaran' => $pendaftaran->metode_pembayaran ?? $regExists->metode_pembayaran
                ]);
            }
        }

        // 2. Cek Iuran Bulanan (Rata 100.000)
        $startDate = Carbon::parse($pendaftaran->tanggal_daftar);
        $now = Carbon::now();
        $currentMonthName = $now->translatedFormat('F Y');

        $current = $startDate->copy()->startOfMonth();
        while ($current->lte($now->copy()->startOfMonth())) {
            $monthName = $current->translatedFormat('F Y');

            $pembayaran = Pembayaran::where('id_user', $user->id_user)
                ->where('bulan', $monthName)
                ->first();

            if (!$pembayaran) {
                Pembayaran::create([
                    'id_user' => $user->id_user,
                    'bulan'   => $monthName,
                    'jumlah'  => 100000, // Iuran Bulanan rata 100rb
                    'status'  => 'Belum Bayar',
                    'tipe_iuran' => 'bulanan'
                ]);
            } else {
                // Perbaikan: Jika bulan ini tidak sengaja ter-set Lunas oleh logic sebelumnya, kembalikan ke Belum Bayar
                // (Hanya jika tidak ada metode pembayaran yang tercatat / asalnya dari auto-update tadi)
                if ($monthName === $currentMonthName && $pembayaran->status === 'Lunas' && empty($pembayaran->metode_pembayaran)) {
                    $pembayaran->update([
                        'status' => 'Belum Bayar',
                        'tanggal_bayar' => null
                    ]);
                }

                // Perbaiki jika nominal salah (khusus yang belum bayar)
                if (strtolower($pembayaran->status) == 'belum bayar' && $pembayaran->jumlah != 100000) {
                    $pembayaran->update(['jumlah' => 100000]);
                }
            }
            $current->addMonth();
        }
    }

    public function index()
    {
        $user = auth()->user();
        $pendaftaran = Pendaftaran::with('programKelas')->where('username', $user->username)->first();

        if ($user->status === 'Menunggu') {
            return view('dashboard.anggota', [
                'pendaftaran'  => $pendaftaran,
                'statusTracking' => true
            ]);
        }

        $this->checkAndGenerateMonthlyBill($user);

        return view('dashboard.anggota', [
            'pendaftaran'       => $pendaftaran,
            'pelatih'           => Pelatih::all(),
            'defaultTab'        => ($pendaftaran && $pendaftaran->programKelas) ? $pendaftaran->programKelas->nama_program : 'Umum',
            'jadwalSaya'        => JadwalLatihan::with(['programKelas', 'pelatih', 'sanggar'])
                                    ->where('id_program', $pendaftaran ? $pendaftaran->id_program : null)
                                    ->whereIn('hari', ['Sabtu', 'Minggu'])
                                    ->orderByRaw("FIELD(hari, 'Sabtu', 'Minggu')")
                                    ->get(),
            'riwayatAbsensi'    => Absensi::with('jadwalLatihan.programKelas')
                                    ->where('id_user', $user->id_user)
                                    ->orderByDesc('waktu_hadir')
                                    ->limit(10)
                                    ->get(),
            'riwayatPembayaran' => Pembayaran::where('id_user', $user->id_user)
                                    ->orderByDesc('id_pembayaran')
                                    ->get(),
        ]);
    }

    /**
     * FIX: Tunai → Menunggu Verifikasi (bendahara yang validasi, baru masuk transactions)
     *      Transfer → Menunggu Verifikasi, Midtrans callback yang set Lunas + insert transactions
     */
    public function bayarIuran(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $user = auth()->user();

        $buktiBayarPath = $pembayaran->bukti_bayar;
        if ($request->hasFile('bukti_bayar')) {
            $request->validate([
                'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf,webp|max:2048',
            ]);
            $buktiBayarPath = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        // Jika Tunai → tunggu verifikasi bendahara
        if ($request->metode_pembayaran === 'tunai') {
            $pembayaran->update([
                'tipe_iuran'        => $request->tipe_iuran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah'            => $request->jumlah_bayar,
                'status'            => 'Menunggu Verifikasi',
                'bukti_bayar'       => $buktiBayarPath,
            ]);

            return response()->json([
                'status'  => 'success',
                'metode'  => 'tunai',
                'message' => 'Berhasil! Silakan setor tunai ke pengurus.'
            ]);
        }

        // Jika Transfer via QRIS → tunggu verifikasi bendahara
        if ($request->metode_pembayaran === 'transfer') {
            $pembayaran->update([
                'tipe_iuran'        => $request->tipe_iuran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah'            => $request->jumlah_bayar,
                'status'            => 'Menunggu Verifikasi',
                'bukti_bayar'       => $buktiBayarPath,
            ]);

            return response()->json([
                'status'  => 'success',
                'metode'  => 'transfer',
                'message' => 'Bukti pembayaran transfer/QRIS berhasil diunggah. Menunggu verifikasi dari bendahara.'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Metode pembayaran tidak dikenal.'
        ], 400);
    }

    /**
     * FIX: Saat Midtrans settlement → set Lunas + tanggal_bayar + insert ke transactions
     */
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $orderParts    = explode('-', $request->order_id);
                $id_pembayaran = $orderParts[1] ?? null;

                if (!$id_pembayaran) return;

                $pembayaran = Pembayaran::find($id_pembayaran);
                if ($pembayaran) {
                    $pembayaran->update([
                        'status'        => 'Lunas',
                        'tanggal_bayar' => now(),
                    ]);

                    // Insert ke transactions agar masuk Laporan Keuangan
                    $keterangan = 'Iuran ' . ucfirst($pembayaran->tipe_iuran ?? 'Bulanan') . ': '
                                . ($pembayaran->user->username ?? 'Siswa')
                                . ' (' . $pembayaran->bulan . ')';

                    $trxExists = Transaction::where('nominal', $pembayaran->jumlah)
                        ->where('keterangan', $keterangan)
                        ->exists();

                    if (!$trxExists) {
                        Transaction::create([
                            'tanggal'    => now(),
                            'jenis'      => 'Masuk',
                            'nominal'    => $pembayaran->jumlah,
                            'keterangan' => $keterangan,
                            'id_user'    => $pembayaran->id_user,
                        ]);
                    }
                }
            }
        }
    }

    public function downloadQR()
    {
        $user = auth()->user();
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                     ->size(500)
                     ->margin(2)
                     ->generate($user->id_user);

        return response($qrCode)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'attachment; filename="QR_SGP_' . $user->username . '.svg"');
    }
}
