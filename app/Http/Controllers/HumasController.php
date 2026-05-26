<?php

namespace App\Http\Controllers;

use App\Models\ProgramKelas;
use App\Models\Galeri;
use App\Models\InformasiBerita;
use App\Models\Absensi;
use App\Models\User;
use App\Models\JadwalLatihan;
use Illuminate\Http\Request;

class HumasController extends Controller
{
    public function index()
    {
        return view('dashboard.humas', [
            'totalProgram'  => ProgramKelas::count(),
            'totalGaleri'   => Galeri::count(),
            'totalBerita'   => InformasiBerita::count(),
            'beritaTerbaru' => InformasiBerita::orderByDesc('tanggal_publish')->take(5)->get(),
        ]);
    }

    public function absensiIndex(Request $request)
    {
        $query = Absensi::with([
                'user.pendaftaran',
                'jadwalLatihan.programKelas'
            ])
            ->orderByDesc('waktu_hadir');

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_hadir', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('id_program')) {
            $query->whereHas('jadwalLatihan', function ($q) use ($request) {
                $q->where('id_program', $request->id_program);
            });
        }

        $absensi      = $query->paginate(20)->withQueryString();
        $programs     = ProgramKelas::orderBy('nama_program')->get();
        $totalHadir   = Absensi::where('status', 'Hadir')->count();
        $totalAlfa    = Absensi::where('status', 'Alfa')->count();
        $totalIzin    = Absensi::whereIn('status', ['Izin', 'Sakit'])->count();
        $totalHariIni = Absensi::whereDate('waktu_hadir', today())->count();

        $siswaList  = User::with('pendaftaran')
                        ->where('role', 'Siswa')
                        ->orderBy('username')
                        ->get();

        // Mapping siswaList ke array sederhana untuk dipakai di JavaScript
        $siswaListJson = $siswaList->map(fn($s) => [
            'id'       => $s->id_user,
            'nama'     => $s->pendaftaran->nama_calon ?? $s->username,
            'username' => $s->username,
        ])->values();

        $jadwalList = JadwalLatihan::with('programKelas')
                        ->orderBy('hari')
                        ->get();

        return view('humas.absensi', compact(
            'absensi', 'programs', 'totalHadir', 'totalAlfa', 'totalIzin', 'totalHariIni',
            'siswaList', 'siswaListJson', 'jadwalList'
        ));
    }
}
