<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SiswaAdminController extends Controller
{
    public function index()
    {
        $siswa = User::where('role', 'Siswa')
            ->orderBy('username')
            ->paginate(10);

        return view('siswa_admin.index', compact('siswa'));
    }

    public function create()
    {
        return view('siswa_admin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'min:6'],
            'status'   => ['required', Rule::in(['Aktif', 'Nonaktif'])],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'Siswa';

        User::create($data);

        return redirect()->route('siswa-admin.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(User $siswa_admin)
    {
        return view('siswa_admin.edit', compact('siswa_admin'));
    }

    public function update(Request $request, User $siswa_admin)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($siswa_admin->id_user, 'id_user')],
            'status'   => ['required', Rule::in(['Aktif', 'Nonaktif'])],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['min:6']]);
            $data['password'] = Hash::make($request->password);
        }

        $siswa_admin->update($data);

        return redirect()->route('siswa-admin.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(User $siswa_admin)
{
    // Hapus semua data pembayaran milik siswa ini dulu
    \App\Models\Pembayaran::where('id_user', $siswa_admin->id_user)->delete();

    // Baru hapus user-nya
    $siswa_admin->delete();

    return redirect()->route('siswa-admin.index')->with('success', 'Data siswa dan riwayat pembayaran berhasil dihapus.');
}
    public function bayarIuran($id)
{
    $pembayaran = \App\Models\Pembayaran::findOrFail($id);
    $user = auth()->user();

    // Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => 'IUR-' . $pembayaran->id_pembayaran . '-' . time(),
            'gross_amount' => (int) $pembayaran->jumlah,
        ],
        'customer_details' => [
            'first_name' => $user->nama_lengkap,
            'email' => $user->email,
        ],
        'callbacks' => [
            'finish' => route('dashboard.siswa'),
        ]
    ];

    $snapToken = \Midtrans\Snap::getSnapToken($params);

    return response()->json(['token' => $snapToken]);
}
}
