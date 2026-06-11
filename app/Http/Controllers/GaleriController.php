<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function publicIndex()
    {
        $galleries = Galeri::orderByDesc('tanggal')->paginate(12);
        return view('public.gallery', compact('galleries'));
    }

    // Admin (humas)
    public function index()
    {
        $galleries = Galeri::orderByDesc('tanggal')->paginate(15);
        return view('galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('galleries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'gambar'    => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'tanggal'   => ['nullable', 'date'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('gambar')) {
            $fotoPath = $request->file('gambar')->store('galeri', 'public');
        }

        Galeri::create([
            'judul'      => $data['judul'],
            'foto'       => $fotoPath,
            'keterangan' => $data['deskripsi'],
            'tanggal'    => $data['tanggal'] ?? date('Y-m-d'),
        ]);

        return redirect()->route('galeri-admin.index')->with('success', 'Galeri berhasil disimpan.');
    }

    public function edit(Galeri $galeri_admin)
    {
        return view('galleries.edit', ['galeri' => $galeri_admin]);
    }

    public function update(Request $request, Galeri $galeri_admin)
    {
        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'tanggal'   => ['nullable', 'date'],
        ]);

        $updateData = [
            'judul'      => $data['judul'],
            'keterangan' => $data['deskripsi'],
            'tanggal'    => $data['tanggal'] ?? date('Y-m-d'),
        ];

        if ($request->hasFile('gambar')) {
            if ($galeri_admin->foto) {
                Storage::disk('public')->delete($galeri_admin->foto);
            }
            $updateData['foto'] = $request->file('gambar')->store('galeri', 'public');
        }

        $galeri_admin->update($updateData);

        return redirect()->route('galeri-admin.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri_admin)
    {
        if ($galeri_admin->foto) {
            Storage::disk('public')->delete($galeri_admin->foto);
        }
        $galeri_admin->delete();

        return redirect()->route('galeri-admin.index')->with('success', 'Galeri berhasil dihapus.');
    }
}

