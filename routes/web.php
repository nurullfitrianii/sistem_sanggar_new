<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\SiswaAdminController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\KetuaController;
use App\Http\Controllers\HumasController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PaymentController;
use App\Mail\PendaftaranDisetujui;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [ProgramController::class, 'home'])->name('home');

Route::get('/galeri-foto', function () {
    $galleries = \App\Models\Galeri::orderByDesc('tanggal')->get();
    return view('dashboard.galeri-foto', compact('galleries'));
})->name('galeri-foto');

Route::get('/galeri-video', function () {
    return view('dashboard.galeri-video');
})->name('galeri-video');

Route::get('/tentang', function () {
    return view('about');
})->name('about');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

// Route untuk AJAX mengambil token Midtrans (Step 3)
Route::get('/payment/token/{id}', [PaymentController::class, 'bayarPendaftaran'])->name('payment.token');

// Route Callback Midtrans - Disarankan diletakkan di luar prefix agar lebih "clean"
Route::post('/midtrans-callback', [App\Http\Controllers\MidtransController::class, 'callback'])->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| Test Email (hapus setelah selesai testing)
|--------------------------------------------------------------------------
*/
Route::get('/test-email', function () {
    Mail::to('n.fitriani4444@gmail.com')->send(new PendaftaranDisetujui(
        namaCalon: 'Budi',
        username: 'budi123',
        password: '08123456789',
        namaProgram: 'Tari Sunda'
    ));
    return 'Email terkirim!';
});

/**
 * REGISTRATION FLOW
 */
Route::prefix('daftar')->group(function () {
    Route::get('/', [PendaftaranController::class, 'showStep1'])->name('pendaftaran.step1');
    Route::post('/step-1', [PendaftaranController::class, 'postStep1'])->name('pendaftaran.postStep1');

    Route::get('/step-2', [PendaftaranController::class, 'showStep2'])->name('pendaftaran.step2');
    Route::post('/step-2', [PendaftaranController::class, 'postStep2'])->name('pendaftaran.postStep2');

    Route::get('/step-3', [PendaftaranController::class, 'showStep3'])->name('pendaftaran.step3');

    // Menangani klik tombol "Selesaikan & Daftar" di Step 3 (AJAX)
    Route::post('/final', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

    // Fallback jika user ingin bayar ulang lewat halaman khusus
    Route::get('/pembayaran/{id}', [PendaftaranController::class, 'pembayaran'])->name('pendaftaran.pembayaran');
});



/**
 * NEW MULTI-STEP REGISTRATION FLOW
 * Route ini menangani seluruh alur pendaftaran 1-4
 */
Route::prefix('daftar')->group(function () {
    Route::get('/', [PendaftaranController::class, 'showStep1'])->name('pendaftaran.step1');
    Route::post('/step-1', [PendaftaranController::class, 'postStep1'])->name('pendaftaran.postStep1');

    Route::get('/step-2', [PendaftaranController::class, 'showStep2'])->name('pendaftaran.step2');
    Route::post('/step-2', [PendaftaranController::class, 'postStep2'])->name('pendaftaran.postStep2');

    Route::get('/step-3', [PendaftaranController::class, 'showStep3'])->name('pendaftaran.step3');

    // Route store ini yang menangani klik tombol "Selesaikan & Daftar" di Step 3
    Route::post('/final', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

    // Route untuk menampilkan halaman yang ada tombol "Bayar Sekarang" Midtrans
    Route::get('/pembayaran/{id}', [PendaftaranController::class, 'pembayaran'])->name('pendaftaran.pembayaran');
    Route::post('/pembayaran/{id}/bukti', [PendaftaranController::class, 'uploadBuktiPendaftaran'])->name('pendaftaran.uploadBukti');

    Route::post('/midtrans/callback', [App\Http\Controllers\MidtransController::class, 'callback']);
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
Route::post('/siswa/iuran/store', [SiswaController::class, 'storeIuran'])->name('siswa.iuran.store');
Route::post('/midtrans/callback', [SiswaController::class, 'callback']);

/*
|--------------------------------------------------------------------------
| Dashboard per Role
|--------------------------------------------------------------------------
*/

// KETUA
Route::middleware(['auth', 'role:Ketua'])->group(function () {
    Route::get('/dashboard/ketua', [KetuaController::class, 'index'])->name('dashboard.ketua');
    Route::resource('kelas', KelasController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::get('absensi', [AbsensiController::class, 'laporanKehadiran'])->name('absensi.index');
    Route::resource('keuangan', KeuanganController::class);
    Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('/laporan/keuangan/export', [LaporanController::class, 'exportExcel'])->name('laporan.keuangan.export');
    Route::get('/laporan/keuangan/pdf', [LaporanController::class, 'exportKeuanganPDF'])->name('laporan.keuangan.pdf');
    Route::get('/laporan/absensi', [LaporanController::class, 'absensi'])->name('laporan.absensi');
    Route::get('/laporan/absensi/pdf', [LaporanController::class, 'exportAbsensiPDF'])->name('laporan.absensi.pdf');
    Route::get('/laporan/absensi/excel', [LaporanController::class, 'exportAbsensiExcel'])->name('laporan.absensi.excel');
    Route::get('/laporan/anggota', [LaporanController::class, 'anggota'])->name('laporan.anggota');

    // Approval Absensi oleh Ketua
    Route::get('/absensi/pending', [AbsensiController::class, 'pendingApproval'])->name('absensi.pending');
    Route::post('/absensi/{id}/approve', [AbsensiController::class, 'approve'])->name('absensi.approve');
    Route::post('/absensi/{id}/reject', [AbsensiController::class, 'reject'])->name('absensi.reject');
});

// HUMAS
Route::middleware(['auth', 'role:Humas'])->group(function () {
    Route::get('/dashboard/humas', [HumasController::class, 'index'])->name('dashboard.humas');
    Route::get('/daftar/checkSlug', [BeritaController::class, 'checkSlug'])->name('berita.checkSlug');
    Route::resource('galeri-admin', GaleriController::class)->except(['show']);
    Route::resource('berita-admin', BeritaController::class)->except(['show']);

    // Verifikasi Pendaftaran oleh Humas
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran/{id}/approve', [PendaftaranController::class, 'approve'])->name('pendaftaran.approve');
    Route::post('/pendaftaran/{id}/reject', [PendaftaranController::class, 'reject'])->name('pendaftaran.reject');

    // Absensi Manual oleh Humas
    Route::get('/absensi/manual', [AbsensiController::class, 'manualInput'])->name('absensi.manual');
    Route::post('/absensi/manual', [AbsensiController::class, 'storeManual'])->name('absensi.storeManual');
    Route::put('/absensi/manual/{id}', [AbsensiController::class, 'updateManual'])->name('absensi.updateManual');
    Route::delete('/absensi/manual/{id}', [AbsensiController::class, 'destroyManual'])->name('absensi.destroyManual');

    // Halaman Rekap Absensi untuk Humas
    Route::get('/humas/absensi', [HumasController::class, 'absensiIndex'])->name('humas.absensi.index');
    Route::get('/humas/absensi/excel', [HumasController::class, 'exportAbsensiExcel'])->name('humas.absensi.excel');
});

// BENDAHARA
Route::middleware(['auth', 'role:Bendahara'])->group(function () {
    Route::get('/dashboard/bendahara', [BendaharaController::class, 'index'])->name('dashboard.bendahara');
    Route::resource('keuangan-bendahara', KeuanganController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('/laporan/keuangan-bendahara', [LaporanController::class, 'keuangan'])->name('laporan.keuangan.bendahara');
    Route::get('/laporan/keuangan-bendahara/export', [LaporanController::class, 'exportExcel'])->name('laporan.keuangan.bendahara.export');
    Route::get('/laporan/keuangan-bendahara/pdf', [LaporanController::class, 'exportKeuanganPDF'])->name('laporan.keuangan.bendahara.pdf');
    Route::post('/bendahara/verifikasi/{type}/{id}/{aksi}', [BendaharaController::class, 'verifikasi'])->name('bendahara.verifikasi');
    Route::get('/bendahara/verifikasi', [BendaharaController::class, 'verifikasiIndex'])->name('bendahara.verifikasi_index');

    // Rute Iuran Bendahara
    Route::get('/bendahara/iuran', [BendaharaController::class, 'indexIuran'])->name('bendahara.iuran.index');
    Route::post('/bendahara/iuran/{id}/status', [BendaharaController::class, 'updateStatusIuran'])->name('bendahara.iuran.status');
});

// SISWA
Route::middleware(['auth', 'role:Siswa'])->group(function () {
    Route::get('/siswa/download-qr', [SiswaController::class, 'downloadQR'])->name('siswa.downloadqr');
    Route::get('/dashboard/siswa', [SiswaController::class, 'index'])->name('dashboard.siswa');
    Route::get('/jadwal-saya', [JadwalController::class, 'jadwalAnggota'])->name('jadwal.anggota');
    Route::get('/absensi-saya', [AbsensiController::class, 'riwayatAnggota'])->name('absensi.anggota');
});

/*
|--------------------------------------------------------------------------
| Shared Routes (Multi-Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/scan-qr', [AbsensiController::class, 'scanView'])->name('absensi.scan_view');
    Route::post('/scan-qr', [AbsensiController::class, 'scan'])->name('absensi.scan');

    Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:Ketua,Humas'])->group(function () {
    Route::resource('siswa-admin', SiswaAdminController::class)->parameters(['siswa-admin' => 'siswa_admin']);
});

Route::middleware(['auth', 'role:Ketua'])->group(function () {
    Route::resource('pengguna', PenggunaController::class);
});

Route::middleware(['auth', 'role:Ketua,Humas'])->group(function () {
    Route::resource('program-admin', ProgramController::class)->except(['show']);
});
Route::middleware(['auth', 'role:Siswa'])->group(function () {
    // ... route dashboard siswa lainnya ...
    Route::post('/iuran/bayar/{id}', [SiswaController::class, 'bayarIuran'])->name('iuran.bayar');
});
