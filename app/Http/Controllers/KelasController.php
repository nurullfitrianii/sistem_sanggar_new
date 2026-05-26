<?php

namespace App\Http\Controllers;

use App\Models\ProgramKelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = ProgramKelas::orderBy('nama_program')->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_program' => ['required', 'string', 'max:100'],
            'kategori'     => ['required', 'string', 'max:50'],
            'deskripsi'    => ['nullable', 'string'],
            'biaya'        => ['nullable', 'numeric', 'min:0'],
            'status'       => ['required', 'in:Aktif,Nonaktif'],
        ]);

        ProgramKelas::create($data);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil disimpan.');
    }

    public function edit(ProgramKelas $kela)
    {
        // Parameter name matching resource route 'kelas'
        return view('kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, ProgramKelas $kela)
    {
        $data = $request->validate([
            'nama_program' => ['required', 'string', 'max:100'],
            'kategori'     => ['required', 'string', 'max:50'],
            'deskripsi'    => ['nullable', 'string'],
            'biaya'        => ['nullable', 'numeric', 'min:0'],
            'status'       => ['required', 'in:Aktif,Nonaktif'],
        ]);

        $kela->update($data);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(ProgramKelas $kela)
    {
        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}

