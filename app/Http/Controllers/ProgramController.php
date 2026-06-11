<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Galeri;
use App\Models\Pendaftaran;
use App\Models\ProgramKelas;
use App\Models\InformasiBerita;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function home()
    {
        // Ambil data program kelas dari DB dengan eager loading untuk digunakan di tampilan kelas
        $programKelas = ProgramKelas::with(['pendaftaran', 'jadwalLatihan.pelatih'])
            ->where('status', 'Aktif')
            ->orderBy('kategori')
            ->get();

        // Galeri terbaru
        $galeri = Galeri::orderBy('id_galeri', 'desc')->take(6)->get();

        // Berita terbaru
        $berita = InformasiBerita::whereIn('status', ['Published', 'published'])
            ->orderBy('tanggal_publish', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $response = response()->view('dashboard.beranda', compact('galeri', 'programKelas', 'berita'));
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        
        return $response;
    }

    public function storePendaftaran(Request $request)
    {
        $request->validate([
            'nama_calon' => 'required|string|max:100',
            'no_hp'      => 'required|string|max:20',
            'alamat'     => 'required|string',
            'id_program' => 'required|exists:program_kelas,id_program',
        ]);

        Pendaftaran::create([
            'id_program'     => $request->id_program,
            'nama_calon'     => $request->nama_calon,
            'tanggal_daftar' => now()->toDateString(),
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'status'         => 'Menunggu',
        ]);

        return redirect('/#pendaftaran')->with('success', 'Pendaftaran berhasil dikirim! Kami akan segera menghubungi Anda.');
    }

    // ==========================================
    // Halaman Publik Lainnya
    // ==========================================
    public function about()
    {
        return view('public.about');
    }

    public function publicIndex()
    {
        $programs = Program::where('is_active', true)->orderBy('judul')->paginate(9);
        return view('public.programs', compact('programs'));
    }

    // ==========================================
    // Admin resource (humas)
    // ==========================================
    public function index()
    {
        $programs = Program::orderBy('nama_program')->paginate(10);
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_program' => ['required', 'string', 'max:100'],
            'kategori'     => ['required', 'string', 'max:50'],
            'slug'         => ['required', 'string', 'max:160', 'unique:program_kelas,slug'],
            'deskripsi'    => ['required', 'string'],
            'biaya'        => ['required', 'numeric', 'min:0'],
            'durasi'       => ['nullable', 'string', 'max:50'],
            'jumlah_sesi'  => ['nullable', 'string', 'max:50'],
            'foto'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'status'       => ['required', 'in:Aktif,Nonaktif'],
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('programs', 'public');
        }

        Program::create($data);

        return redirect()->route('program-admin.index')->with('success', 'Program berhasil disimpan.');
    }

    public function edit(Program $program_admin)
    {
        return view('programs.edit', ['program' => $program_admin]);
    }

    public function update(Request $request, Program $program_admin)
    {
        $data = $request->validate([
            'nama_program' => ['required', 'string', 'max:100'],
            'kategori'     => ['required', 'string', 'max:50'],
            'slug'         => ['required', 'string', 'max:160', 'unique:program_kelas,slug,' . $program_admin->id_program . ',id_program'],
            'deskripsi'    => ['required', 'string'],
            'biaya'        => ['required', 'numeric', 'min:0'],
            'durasi'       => ['nullable', 'string', 'max:50'],
            'jumlah_sesi'  => ['nullable', 'string', 'max:50'],
            'foto'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'status'       => ['required', 'in:Aktif,Nonaktif'],
        ]);

        if ($request->hasFile('foto')) {
            if ($program_admin->foto && \Storage::disk('public')->exists($program_admin->foto)) {
                \Storage::disk('public')->delete($program_admin->foto);
            }
            $data['foto'] = $request->file('foto')->store('programs', 'public');
        }

        $program_admin->update($data);

        return redirect()->route('program-admin.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program_admin)
    {
        // 1. Ambil semua jadwal latihan untuk program ini
        $jadwalList = $program_admin->jadwalLatihan;

        foreach ($jadwalList as $jadwal) {
            // Hapus data absensi terkait jadwal
            $jadwal->absensi()->delete();
            // Hapus jadwal
            $jadwal->delete();
        }

        // 2. Set id_program menjadi null di tabel pendaftaran
        \App\Models\Pendaftaran::where('id_program', $program_admin->id_program)->update(['id_program' => null]);

        // 3. Hapus foto dari storage jika ada
        if ($program_admin->foto && \Storage::disk('public')->exists($program_admin->foto)) {
            \Storage::disk('public')->delete($program_admin->foto);
        }

        // 5. Hapus program kelas
        $program_admin->delete();

        return redirect()->route('program-admin.index')->with('success', 'Program berhasil dihapus.');
    }
}
