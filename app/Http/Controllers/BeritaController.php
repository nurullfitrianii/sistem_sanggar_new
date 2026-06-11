<?php

namespace App\Http\Controllers;

use App\Models\InformasiBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function publicIndex()
    {
        $posts = InformasiBerita::whereIn('status', ['Published', 'published'])
            ->orderByDesc('tanggal_publish')
            ->paginate(6);

        return view('public.news', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = InformasiBerita::where('slug', $slug)
            ->whereIn('status', ['Published', 'published'])
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }

    // Admin (humas)
    public function index()
    {
        // Admin tetap bisa melihat semua berita termasuk yang kedaluwarsa
        $posts = InformasiBerita::orderByDesc('tanggal_publish')->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'           => ['required', 'string', 'max:150'],
            'isi'             => ['required', 'string'],
            'foto'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'tanggal_publish' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_publish'],
            'status'          => ['required', 'in:draft,published'],
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('berita', 'public');
        }

        $data['id_user'] = auth()->user()->id_user;
        $data['slug'] = str()->slug($data['judul']) . '-' . rand(1000, 9999);

        InformasiBerita::create($data);

        return redirect()->route('berita-admin.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function edit($id)
    {
        $post = InformasiBerita::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = InformasiBerita::findOrFail($id);
        $data = $request->validate([
            'judul'           => ['required', 'string', 'max:150'],
            'isi'             => ['required', 'string'],
            'foto'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'tanggal_publish' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_publish'],
            'status'          => ['required', 'in:draft,published'],
        ]);

        if ($request->hasFile('foto')) {
            if ($post->foto) {
                Storage::disk('public')->delete($post->foto);
            }
            $data['foto'] = $request->file('foto')->store('berita', 'public');
        }

        $data['slug'] = str()->slug($data['judul']) . '-' . rand(1000, 9999);

        $post->update($data);

        return redirect()->route('berita-admin.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function checkSlug(Request $request)
    {
        $slug = \Illuminate\Support\Str::slug($request->judul);
        return response()->json(['slug' => $slug]);
    }

    public function destroy($id)
    {
        $post = InformasiBerita::findOrFail($id);
        $post->delete();

        return redirect()->route('berita-admin.index')->with('success', 'Berita berhasil dihapus.');
    }
}

