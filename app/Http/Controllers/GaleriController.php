<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;

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
            'gambar'    => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'tanggal'   => ['nullable', 'date'],
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('galeri', 'public');
        }

        Galeri::create($data);

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
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'tanggal'   => ['nullable', 'date'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($galeri_admin->gambar) {
                Storage::disk('public')->delete($galeri_admin->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('galeri', 'public');
        }

        $galeri_admin->update($data);

        return redirect()->route('galeri-admin.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri_admin)
    {
        $galeri_admin->delete();

        return redirect()->route('galeri-admin.index')->with('success', 'Galeri berhasil dihapus.');
    }
}

