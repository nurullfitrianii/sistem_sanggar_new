<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function bayarPendaftaran($id)
    {
        try {
            $pendaftaran = Pendaftaran::with('programKelas')->findOrFail($id);

            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'REG-' . $pendaftaran->id_pendaftaran . '-' . time(),
                    'gross_amount' => (int) $pendaftaran->programKelas->biaya,
                ],
                'callbacks' => [
                    'finish' => "http://127.0.0.1:8000/dashboard/siswa",
                    'error'  => "http://127.0.0.1:8000/dashboard/siswa",
                    'pending'=> "http://127.0.0.1:8000/dashboard/siswa",
                ],
                'customer_details' => [
                    'first_name' => $pendaftaran->nama_calon,
                    'email' => $pendaftaran->email,
                    'phone' => $pendaftaran->no_hp,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            return response()->json(['token' => $snapToken]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
