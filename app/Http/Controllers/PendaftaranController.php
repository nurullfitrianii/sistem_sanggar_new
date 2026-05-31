<?php

namespace App\Http\Controllers;

use App\Mail\PendaftaranDisetujui;
use App\Mail\PendaftaranDitolak;
use App\Models\Pendaftaran;
use App\Models\ProgramKelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::with('programKelas')
            ->orderBy('id_pendaftaran', 'desc')
            ->paginate(10);
        return view('pendaftaran.index', compact('pendaftarans'));
    }

    public function showStep1() {
        $sanggar = \App\Models\Sanggar::first();
        $now = now();

        if ($sanggar) {
            if ($sanggar->pendaftaran_dibuka && $now->lt($sanggar->pendaftaran_dibuka)) {
                return view('pendaftaran.form-multi-step', [
                    'step' => 0, 
                    'message' => 'Pendaftaran akan dibuka pada tanggal ' . \Carbon\Carbon::parse($sanggar->pendaftaran_dibuka)->translatedFormat('d F Y')
                ]);
            }
            if ($sanggar->pendaftaran_ditutup && $now->gt($sanggar->pendaftaran_ditutup)) {
                return view('pendaftaran.form-multi-step', [
                    'step' => 0, 
                    'message' => 'Pendaftaran sudah ditutup.'
                ]);
            }
        }

        Session::forget('registration_data');
        return view('pendaftaran.form-multi-step', ['step' => 1]);
    }

    public function postStep1(Request $request) {
        $data = $request->validate([
            'nama_calon'    => 'required|string|max:100',
            'no_hp'         => 'required|string|max:20',
            'alamat'        => 'required|string',
            'email'         => 'required|email|max:255',
            'tanggal_lahir' => 'required|date',
            'dokumen'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('dokumen')) {
            $data['dokumen'] = $request->file('dokumen')->store('pendaftaran', 'public');
        } elseif (Session::has('registration_data.dokumen')) {
            $data['dokumen'] = Session::get('registration_data.dokumen');
        } else {
            $data['dokumen'] = null;
        }

        Session::put('registration_data', $data);
        return redirect()->route('pendaftaran.step2');
    }

    public function showStep2() {
        if (!Session::has('registration_data')) return redirect()->route('pendaftaran.step1');
        $programKelas = ProgramKelas::where('status', 'Aktif')->get();
        return view('pendaftaran.form-multi-step', ['step' => 2, 'programKelas' => $programKelas]);
    }

    public function postStep2(Request $request) {
        $request->validate(['id_program' => 'required|exists:program_kelas,id_program']);
        $data = Session::get('registration_data');
        $data['id_program'] = $request->id_program;
        Session::put('registration_data', $data);
        return redirect()->route('pendaftaran.step3');
    }

    public function showStep3() {
        if (!Session::has('registration_data')) return redirect()->route('pendaftaran.step1');
        $data = Session::get('registration_data');
        $program = ProgramKelas::find($data['id_program']);
        return view('pendaftaran.form-multi-step', ['step' => 3, 'program' => $program]);
    }

    public function store(Request $request)
    {
        $sessionData = \Illuminate\Support\Facades\Session::get('registration_data');
        $metode = $request->input('metode_pembayaran');

        if (!$sessionData) {
            return response()->json(['success' => false, 'message' => 'Sesi habis'], 400);
        }

        $username = strtolower(str_replace(' ', '', $sessionData['nama_calon']));
        $password = $sessionData['no_hp'];

        DB::beginTransaction();
        try {
            $user = User::updateOrCreate(
                ['email' => $sessionData['email']],
                [
                    'username' => $username,
                    'password' => Hash::make($password),
                    'role'     => 'Siswa',
                    'status'   => 'Menunggu',
                ]
            );

            $pendaftaran = Pendaftaran::create([
                'id_user'           => $user->id_user,
                'id_program'        => $sessionData['id_program'],
                'username'          => $username,
                'email'             => $sessionData['email'],
                'nama_calon'        => $sessionData['nama_calon'],
                'tanggal_lahir'     => $sessionData['tanggal_lahir'],
                'tanggal_daftar'    => now()->toDateString(),
                'no_hp'             => $sessionData['no_hp'],
                'alamat'            => $sessionData['alamat'],
                'dokumen'           => $sessionData['dokumen'] ?? null,
                'metode_pembayaran' => $metode,
                'status'            => 'Menunggu',
                'status_pembayaran' => ($metode == 'transfer') ? 'pending' : 'belum lunas'
            ]);

            DB::commit();
            \Illuminate\Support\Facades\Session::forget('registration_data');

            return response()->json([
                'success'        => true,
                'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                'metode'         => $metode,
                'username'       => $username,
                'nama_calon'     => $sessionData['nama_calon'],
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pembayaran($id)
    {
        $pendaftaran = Pendaftaran::with('programKelas')->findOrFail($id);
        return view('pendaftaran.pembayaran', compact('pendaftaran'));
    }

    /**
     * APPROVE: kirim email notifikasi ke siswa berisi info login
     */
   public function approve($id)
{
    $pendaftaran = Pendaftaran::with('programKelas')->findOrFail($id);

    DB::beginTransaction();
    try {
        DB::table('pendaftaran')
            ->where('id_pendaftaran', $id)
            ->update([
                'status' => 'Aktif'
            ]);

        // Ambil user berdasarkan email (karena pendaftaran tidak punya id_user)
        $user = DB::table('users')
            ->where('email', $pendaftaran->email)
            ->first();

        if ($user) {
            DB::table('users')
                ->where('id_user', $user->id_user)
                ->update(['status' => 'Aktif']);
        }

        DB::commit();

        // Kirim email notifikasi
        if ($pendaftaran->email) {
            try {
                Mail::to($pendaftaran->email)->send(new PendaftaranDisetujui(
                    namaCalon:   $pendaftaran->nama_calon,
                    username:    $pendaftaran->username,
                    password:    $pendaftaran->no_hp,
                    namaProgram: $pendaftaran->programKelas->nama_program ?? 'Sanggar'
                ));
            } catch (\Exception $mailErr) {
                \Log::warning('Gagal kirim email approve: ' . $mailErr->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Pendaftaran ' . $pendaftaran->nama_calon . ' disetujui! Email notifikasi telah dikirim.');

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Ada masalah: ' . $e->getMessage());
    }
}

    /**
     * REJECT: kirim email notifikasi penolakan
     */
    public function reject($id)
    {
        $pendaftaran = Pendaftaran::with('programKelas')->findOrFail($id);

        $pendaftaran->update(['status' => 'Ditolak']);

        DB::table('users')
            ->where('email', $pendaftaran->email)
            ->update(['status' => 'Nonaktif']);

        // Kirim email notifikasi ditolak
        if ($pendaftaran->email) {
            try {
                Mail::to($pendaftaran->email)->send(new PendaftaranDitolak(
                    namaCalon:   $pendaftaran->nama_calon,
                    namaProgram: $pendaftaran->programKelas->nama_program ?? 'Sanggar'
                ));
            } catch (\Exception $mailErr) {
                \Log::warning('Gagal kirim email reject: ' . $mailErr->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Pendaftaran ' . $pendaftaran->nama_calon . ' ditolak. Email notifikasi telah dikirim.');
    }
}
