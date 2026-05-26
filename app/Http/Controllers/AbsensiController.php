<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalLatihan;
use App\Models\User;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function laporanKehadiran()
    {
        $laporan = Absensi::with(['user', 'jadwalLatihan.programKelas'])->orderByDesc('waktu_hadir')->get();
        return view('ketua.laporan', compact('laporan'));
    }

    public function scanView()
    {
        return view('absensi.scan');
    }

    /**
     * VERSI FINAL: Scan Otomatis Terintegrasi
     */
public function scan(Request $request)
{
    try {
        $scannedData = $request->id_user; // Data dari QR (bisa ID atau Username)

        $englishDay = date('l');
        $hariMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $hariMap[$englishDay];

        $todayDate = \Carbon\Carbon::today()->toDateString();
        $currentTime = now()->format('H:i:s');

        // 1. CARI DATA PENDAFTARAN (Data Integrity Check part 1)
        $pendaftaran = \App\Models\Pendaftaran::with('programKelas')
            ->where('username', $scannedData)
            ->first();

        // Jaga-jaga: Kalau QR isinya ID User (angka), kita cari dulu username-nya di tabel users
        if (!$pendaftaran) {
            $user = \App\Models\User::where('id_user', $scannedData)->orWhere('username', $scannedData)->first();
            if ($user) {
                $pendaftaran = \App\Models\Pendaftaran::with('programKelas')
                    ->where('username', $user->username)
                    ->first();
            }
        }

        if (!$pendaftaran) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan di data pendaftaran.']);
        }

        // 2. CARI JADWAL AKTIF (Flexible Lookup)
        $jadwalList = \App\Models\JadwalLatihan::where('id_program', $pendaftaran->id_program)->get();

        if ($jadwalList->isEmpty()) {
            // Auto-fallback: permanently resolve by creating the fixed schedule
            $namaProgram = strtolower($pendaftaran->programKelas->nama_program ?? '');
            $isSeniTari = strpos($namaProgram, 'tari') !== false;

            $jadwalAktif = \App\Models\JadwalLatihan::create([
                'id_program' => $pendaftaran->id_program,
                'hari' => in_array($hariIni, ['Sabtu', 'Minggu']) ? $hariIni : 'Sabtu', // Tetap fleksibel Sabtu/Minggu
                'jam_mulai' => $isSeniTari ? '10:00:00' : '13:00:00',
                'jam_selesai' => $isSeniTari ? '13:00:00' : '17:00:00',
                'lokasi' => 'Sanggar Utama'
            ]);
        } else {
            // Sort in PHP: Prioritize current day, then closest time.
            $jadwalAktif = $jadwalList->sortBy(function($jadwal) use ($hariIni, $currentTime) {
                // Priority 0 if it's today, 1 if other day
                $isToday = (strtolower($jadwal->hari) === strtolower($hariIni)) ? 0 : 1;

                // Time difference
                $timeDiff = abs(strtotime($jadwal->jam_mulai ?? '00:00:00') - strtotime($currentTime));

                return sprintf("%d_%010d", $isToday, $timeDiff);
            })->first();
        }

        // 3. CEK ABSENSI GANDA
        $userAsli = \App\Models\User::where('username', $pendaftaran->username)->first();
        if (!$userAsli) {
            return response()->json(['status' => 'error', 'message' => 'Akun User tidak ditemukan untuk username tersebut.']);
        }

        $exists = \App\Models\Absensi::where('id_jadwal', $jadwalAktif->id_jadwal)
                         ->where('id_user', $userAsli->id_user)
                         ->whereDate('waktu_hadir', $todayDate)
                         ->first();

        if ($exists) {
            return response()->json(['status' => 'warning', 'message' => 'Siswa sudah absen hari ini.']);
        }

        // 4. EKSEKUSI SIMPAN (Fix Attendance Saving)
        \App\Models\Absensi::create([
            'id_jadwal' => $jadwalAktif->id_jadwal,
            'id_user' => $userAsli->id_user,
            'status' => 'Hadir',
            'waktu_hadir' => now('Asia/Jakarta')
        ]);

        // User Feedback
        $studentName = $userAsli->nama_lengkap ?? $pendaftaran->nama_calon;
        $programName = $pendaftaran->programKelas->nama_program ?? 'Kelas';

        return response()->json([
            'status' => 'success',
            'message' => "Berhasil! {$studentName} hadir di {$programName}"
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Sistem Error: ' . $e->getMessage()]);
    }
}

    // --- Fungsi lainnya tetap sama namun pastikan relasi aman ---

    public function riwayatAnggota() {
    $user = auth()->user();
    $riwayatAbsensi = Absensi::with(['jadwalLatihan.programKelas'])
        ->where('id_user', $user->id_user)
        ->orderByDesc('waktu_hadir')
        ->paginate(10);

    return view('absensi.anggota', compact('riwayatAbsensi'));
}

    public function manualInput()
    {
        $jadwal = JadwalLatihan::with(['programKelas', 'pelatih'])->get();
        $siswa = User::where('role', 'Siswa')->get();
        $semuaAbsensi = Absensi::with(['user', 'jadwalLatihan.programKelas'])
                        ->orderByDesc('waktu_hadir')
                        ->paginate(20);
        return view('absensi.manual', compact('jadwal', 'siswa', 'semuaAbsensi'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'id_jadwal' => 'required|exists:jadwal_latihan,id_jadwal',
            'status' => 'required|in:Hadir,Sakit,Izin,Alfa',
            'keterangan' => 'nullable|string',
        ]);

        Absensi::create([
            'id_user' => $request->id_user,
            'id_jadwal' => $request->id_jadwal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'waktu_hadir' => now('Asia/Jakarta'),
        ]);

        return redirect()->back()->with('success', 'Absensi manual berhasil disimpan.');
    }

    public function updateManual(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Sakit,Izin,Alfa',
            'keterangan' => 'nullable|string',
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data absensi berhasil diubah.');
    }

    public function destroyManual($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();
        return redirect()->back()->with('success', 'Data absensi berhasil dihapus.');
    }

    public function pendingApproval()
    {
        $pending = Absensi::with(['user', 'jadwalLatihan.programKelas'])
            ->where('status', 'Pending')
            ->orderByDesc('waktu_hadir')
            ->get();
        return view('absensi.pending', compact('pending'));
    }

    public function approve($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->update(['status' => 'Hadir']);
        return redirect()->back()->with('success', 'Absensi berhasil dikonfirmasi.');
    }

    public function reject($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();
        return redirect()->back()->with('success', 'Absensi ditolak dan dihapus.');
    }
}
