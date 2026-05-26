<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Menampilkan Halaman Login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses Login Manual
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek status akun agar hanya yang Aktif/Menunggu yang bisa masuk
            if (!in_array($user->status, ['Aktif', 'Menunggu'])) {
                Auth::logout();
                return back()->with('error', 'Akun Anda dinonaktifkan.');
            }

            $request->session()->regenerate();
            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->except('password'));
    }

    /**
     * Redirect ke Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email yang didapat dari Google
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // 1. Cek Status Akun
                if (!in_array($user->status, ['Aktif', 'Menunggu'])) {
                    return redirect('/login')->with('error', 'Akun Anda dinonaktifkan.');
                }

                // 2. Update google_id jika belum ada (untuk sinkronisasi akun)
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }

                // 3. Login-kan user ke sistem
                Auth::login($user);
                session()->regenerate();

                // 4. Redirect otomatis ke dashboard sesuai role di database
                return $this->redirectByRole($user);
            } else {
                return redirect('/login')->with('error', 'Email Google Anda belum terdaftar di sistem.');
            }

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login via Google. Pastikan internet stabil.');
        }
    }

    /**
     * Logika Redirect Berdasarkan Role
     */
    private function redirectByRole($user)
    {
        $role = strtolower($user->role);

        switch ($role) {
            case 'ketua':
                return redirect()->route('dashboard.ketua');
            case 'bendahara':
                return redirect()->route('dashboard.bendahara');
            case 'humas':
                return redirect()->route('dashboard.humas');
            case 'siswa':
                return redirect()->route('dashboard.siswa');
            default:
                return redirect('/');
        }
    }
    public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email']);

    // Mengirim link reset password bawaan Laravel
    $status = Password::sendResetLink(
        $request->only('email')
    );

    // Jika berhasil dikirim
    if ($status === Password::RESET_LINK_SENT) {
        return back()->with(['status' => __($status)]);
    }

    // Jika gagal (email tidak ditemukan dll)
    return back()->withErrors(['email' => __($status)]);
}
    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}