<?php namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    protected function routePrefix(): string
    {
        return request()->routeIs('keuangan-bendahara.*') ? 'keuangan-bendahara' : 'keuangan';
    }

    public function index()
    {
        $transaksi = Transaction::with('user')->orderByDesc('tanggal')->paginate(15);
        $routePrefix = $this->routePrefix();
        return view('keuangan.index', compact('transaksi', 'routePrefix'));
    }

    public function create()
    {
        $routePrefix = $this->routePrefix();
        return view('keuangan.create', compact('routePrefix'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'     => ['required', 'date'],
            'jenis'       => ['required', 'in:Masuk,Keluar'],
            'nominal'     => ['required', 'numeric', 'min:0'],
            'keterangan'  => ['required', 'string'],
        ]);

        $data['id_user'] = auth()->user()->id_user;

        Transaction::create($data);

        // Jika pemasukan (Masuk), cari pendaftaran yang belum lunas (tunai/belum lunas)
        // lalu update status_pembayaran menjadi 'success' jika nama_calon atau username ada di keterangan
        if ($data['jenis'] === 'Masuk') {
            $pendingPendaftarans = \App\Models\Pendaftaran::whereIn('status_pembayaran', ['belum lunas', 'tunai'])->get();
            foreach ($pendingPendaftarans as $p) {
                $namaLower = strtolower($p->nama_calon);
                $usernameLower = strtolower($p->username);
                $ketLower = strtolower($data['keterangan']);

                // 1. Coba match langsung
                if (str_contains($ketLower, $namaLower) || str_contains($ketLower, $usernameLower)) {
                    $p->update(['status_pembayaran' => 'success']);
                    continue;
                }

                // 2. Coba match parsial (pecah nama) jika keterangan mengandung kata pendaftaran/daftar/biaya/masuk/siswa/sanggar
                $isPendaftaranRelated = false;
                $keywords = ['pendaftaran', 'daftar', 'biaya', 'masuk', 'siswa', 'sanggar', 'registrasi', 'kelas'];
                foreach ($keywords as $kw) {
                    if (str_contains($ketLower, $kw)) {
                        $isPendaftaranRelated = true;
                        break;
                    }
                }

                if ($isPendaftaranRelated) {
                    $words = explode(' ', $namaLower);
                    foreach ($words as $word) {
                        $trimmedWord = trim($word);
                        if (strlen($trimmedWord) >= 3 && str_contains($ketLower, $trimmedWord)) {
                            $p->update(['status_pembayaran' => 'success']);
                            break;
                        }
                    }
                }
            }
        }

        $routePrefix = $this->routePrefix();
        return redirect()->route($routePrefix . '.index')->with('success', 'Transaksi keuangan berhasil disimpan.');
    }

    public function destroy(Request $request)
    {
        $id = $request->route('keuangan') ?? $request->route('keuangan_bendahara');
        $transaksi = Transaction::findOrFail($id);
        $transaksi->delete();

        $routePrefix = $this->routePrefix();
        return redirect()->route($routePrefix . '.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}

