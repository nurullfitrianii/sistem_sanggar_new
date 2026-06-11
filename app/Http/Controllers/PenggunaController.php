<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::whereIn('role', ['Ketua', 'Humas', 'Bendahara'])
            ->orderBy('role')
            ->orderBy('username')
            ->paginate(10);

        return view('pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'    => ['nullable', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'role'     => ['required', Rule::in(['Ketua', 'Humas', 'Bendahara'])],
            'status'   => ['required', Rule::in(['Aktif', 'Nonaktif'])],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('pengguna.index')->with('success', 'User Staff berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($pengguna->id_user, 'id_user')],
            'email'    => ['nullable', 'email', 'max:191', Rule::unique('users')->ignore($pengguna->id_user, 'id_user')],
            'role'     => ['required', Rule::in(['Ketua', 'Humas', 'Bendahara'])],
            'status'   => ['required', Rule::in(['Aktif', 'Nonaktif'])],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['min:6']]);
            $data['password'] = Hash::make($request->password);
        }

        if ($pengguna->email !== $request->email) {
            $data['google_id'] = null;
        }

        $pengguna->update($data);

        return redirect()->route('pengguna.index')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        $pengguna->delete();

        return redirect()->route('pengguna.index')->with('success', 'Data staff berhasil dihapus.');
    }
}
