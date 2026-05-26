<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // Validasi agar data yang masuk beneran dari Midtrans, bukan orang iseng
        if ($hashed == $request->signature_key) {

            // Ambil ID Pendaftaran dari Order ID (Tadi kita buat format: REG-ID-TIME)
            $orderParts = explode('-', $request->order_id);
            $id_pendaftaran = $orderParts[1];

            $pendaftaran = Pendaftaran::find($id_pendaftaran);

            if ($pendaftaran) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $pendaftaran->update(['status_pembayaran' => 'success']);

                    // Create Transaction for Financial Report
                    $keterangan = 'Pendaftaran Siswa: ' . $pendaftaran->nama_calon . ' (' . ($pendaftaran->programKelas->nama_program ?? 'Program') . ')';
                    
                    $trxExists = Transaction::where('nominal', $request->gross_amount)
                        ->where('keterangan', $keterangan)
                        ->whereDate('tanggal', now()->toDateString())
                        ->exists();

                    if (!$trxExists) {
                        Transaction::create([
                            'tanggal'    => now(),
                            'jenis'      => 'Masuk',
                            'nominal'    => $request->gross_amount,
                            'keterangan' => $keterangan,
                            'id_user'    => $pendaftaran->id_user,
                        ]);
                    }

                } elseif ($request->transaction_status == 'pending') {
                    $pendaftaran->update(['status_pembayaran' => 'pending']);
                } elseif ($request->transaction_status == 'deny' || $request->transaction_status == 'expire' || $request->transaction_status == 'cancel') {
                    $pendaftaran->update(['status_pembayaran' => 'failed']);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
