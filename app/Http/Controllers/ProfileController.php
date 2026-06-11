<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'email' => ['required', 'email', 'max:191', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ]);

        $user->username = $request->username;

        // Jika email diubah, reset google_id agar bisa login dengan Google menggunakan akun baru
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->google_id = null;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
