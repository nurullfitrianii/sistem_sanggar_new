<?php

namespace App\Http\Controllers;

use App\Models\JadwalLatihan;
use App\Models\Pelatih;
use App\Models\ProgramKelas;
use App\Models\Sanggar;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class JadwalController extends Controller
{
    public function index()
    {
        // Auto-fix: Pastikan semua program aktif punya jadwal Sabtu & Minggu
        $programs = ProgramKelas::where('status', 'Aktif')->get();
        $pelatihDefault = Pelatih::first();
        $sanggarDefault = Sanggar::first();

        foreach ($programs as $p) {
            $jadwals = JadwalLatihan::where('id_program', $p->id_program)->get();
            
            if ($jadwals->isEmpty()) {
                // Jika kosong sama sekali, buatkan draft Sabtu & Minggu
                foreach (['Sabtu', 'Minggu'] as $h) {
                    JadwalLatihan::create([
                        'id_program'  => $p->id_program,
                        'id_pelatih'  => $pelatihDefault->id_pelatih ?? 1,
                        'id_sanggar'  => $sanggarDefault->id_sanggar ?? 1,
                        'hari'         => $h,
                        'jam_mulai'    => '13:00:00',
                        'jam_selesai'  => '17:00:00',
                        'lokasi'       => 'Sanggar Utama',
                        'materi'       => 'Latihan Rutin'
                    ]);
                }
            } elseif ($jadwals->count() == 1) {
                // Jika cuma ada 1 hari, buatkan pasangannya
                $ref = $jadwals->first();
                $missingDay = (strtolower(trim($ref->hari)) === 'sabtu') ? 'Minggu' : 'Sabtu';
                
                JadwalLatihan::create([
                    'id_program'  => $ref->id_program,
                    'id_pelatih'  => $ref->id_pelatih,
                    'id_sanggar'  => $ref->id_sanggar,
                    'hari'         => $missingDay,
                    'jam_mulai'    => $ref->jam_mulai,
                    'jam_selesai'  => $ref->jam_selesai,
                    'lokasi'       => $ref->lokasi,
                    'materi'       => 'Latihan Rutin'
                ]);
            }
        }

        $jadwal = JadwalLatihan::with(['pelatih', 'programKelas', 'sanggar'])
            ->orderByRaw("FIELD(hari, 'Sabtu', 'Minggu')")
            ->get();
        
        $pelatih = Pelatih::all();
        $kelas = ProgramKelas::with(['pendaftaran' => function($query) {
            $query->whereIn('status', ['Disetujui', 'Aktif', 'Menunggu']);
        }, 'jadwalLatihan'])->where('status', 'Aktif')->get();
        
        return view('jadwal.index', compact('jadwal', 'pelatih', 'kelas'));
    }

    public function create()
    {
        $pelatih = Pelatih::all();
        $kelas = ProgramKelas::all();
        $sanggar = Sanggar::all();

        return view('jadwal.create', compact('pelatih', 'kelas', 'sanggar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pelatih'  => ['required', 'exists:pelatih,id_pelatih'],
            'id_program'  => ['required', 'exists:program_kelas,id_program'],
            'id_sanggar'  => ['required', 'exists:sanggar,id_sanggar'],
            'hari'        => ['required', 'string'],
            'jam_mulai'   => ['required'],
            'jam_selesai' => ['required'],
            'lokasi'      => ['nullable', 'string', 'max:100'],
            'materi'      => ['nullable', 'string'],
        ]);

        JadwalLatihan::create($data);
        return redirect()->route('jadwal.index')->with('success', 'Data jadwal berhasil disimpan.');
    }

    public function edit($id)
    {
        $jadwal = JadwalLatihan::findOrFail($id);
        $pelatih = Pelatih::all();
        $kelas = ProgramKelas::all(); // Disamakan dengan fungsi create
        $sanggar = Sanggar::all();

        return view('jadwal.edit', compact('jadwal', 'pelatih', 'kelas', 'sanggar'));
    }
    public function update(Request $request, $id)
    {
        $jadwal = JadwalLatihan::findOrFail($id);
        $data = $request->validate([
            'id_pelatih'  => ['nullable', 'exists:pelatih,id_pelatih'],
            'id_program'  => ['nullable', 'exists:program_kelas,id_program'],
            'id_sanggar'  => ['nullable', 'exists:sanggar,id_sanggar'],
            'hari'        => ['required', 'string'],
            'jam_mulai'   => ['required'],
            'jam_selesai' => ['required'],
            'lokasi'      => ['nullable', 'string', 'max:100'],
            'materi'      => ['nullable', 'string'],
        ]);

        $jadwal->update($data);

        return redirect()->route('jadwal.index')->with('success', 'Data jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalLatihan::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Data jadwal berhasil dihapus.');
    }

    public function jadwalAnggota()
    {
        $user = auth()->user();
        $pendaftaran = \App\Models\Pendaftaran::with('programKelas')->where('username', $user->username)->first();

        if (!$pendaftaran) {
            return redirect()->back()->with('error', 'Silahkan daftar program terlebih dahulu.');
        }

        // Ambil jadwal rutin (Sabtu & Minggu)
        $jadwalRutin = JadwalLatihan::with(['pelatih', 'programKelas'])
            ->where('id_program', $pendaftaran->id_program)
            ->get();

        // FIX: Jika hanya ada satu hari (misal Sabtu saja), kita pastikan Minggu juga ada 
        // agar sesuai kebijakan sanggar yang kegiatannya Sabtu & Minggu.
        if ($jadwalRutin->count() > 0 && $jadwalRutin->count() < 2) {
            $existingDay = strtolower(trim($jadwalRutin->first()->hari));
            $missingDay = ($existingDay === 'sabtu') ? 'Minggu' : 'Sabtu';
            
            // Cek apakah beneran gak ada di DB
            $existsInDb = JadwalLatihan::where('id_program', $pendaftaran->id_program)
                ->where('hari', $missingDay)
                ->exists();
                
            if (!$existsInDb) {
                $ref = $jadwalRutin->first();
                JadwalLatihan::create([
                    'id_program'  => $ref->id_program,
                    'id_pelatih'  => $ref->id_pelatih,
                    'id_sanggar'  => $ref->id_sanggar,
                    'hari'         => $missingDay,
                    'jam_mulai'    => $ref->jam_mulai,
                    'jam_selesai'  => $ref->jam_selesai,
                    'lokasi'       => $ref->lokasi,
                    'materi'       => 'Latihan Rutin'
                ]);
                // Refresh list
                $jadwalRutin = JadwalLatihan::with(['pelatih', 'programKelas'])
                    ->where('id_program', $pendaftaran->id_program)
                    ->get();
            }
        }

        // Generate tanggal untuk 1 semester ke depan (6 bulan)
        $listJadwal = [];
        $startDate = now();
        $endDate = now()->addMonths(6);

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeek; // 0 (Sun) to 6 (Sat)
            
            foreach ($jadwalRutin as $rutin) {
                $rutinDay = strtolower(trim($rutin->hari));
                $isMatch = false;
                
                if (($dayOfWeek == 6 && $rutinDay == 'sabtu') || ($dayOfWeek == 0 && $rutinDay == 'minggu')) {
                    $isMatch = true;
                }

                if ($isMatch) {
                    $item = clone $rutin;
                    $item->tanggal_sesi = $currentDate->copy();
                    $listJadwal[] = $item;
                }
            }
            $currentDate->addDay();
        }

        // Ambil daftar semua pelatih untuk bagian Participant di kanan
        $pelatih = \App\Models\Pelatih::all();
        $defaultTab = $pendaftaran->programKelas->nama_program;

        return view('jadwal.anggota', [
            'jadwal' => collect($listJadwal),
            'defaultTab' => $defaultTab,
            'pelatih' => $pelatih
        ]);
    }
}

