@extends('layouts.admin')

@section('title', 'Ubah Berita')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 fw-bold mb-0">Edit Berita</h1>
            <a href="{{ route('berita-admin.index') }}" class="btn rounded-pill px-4 btn-outline-primary">
                Kembali
            </a>
        </div>

        <form action="{{ route('berita-admin.update', $post) }}" method="POST" enctype="multipart/form-data" class="card section-card p-5">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul Berita</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $post->judul) }}"
                               class="form-control form-control-lg @error('judul') is-invalid @enderror" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
                               class="form-control form-control-lg @error('slug') is-invalid @enderror" required readonly>
                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tanggal Tayang</label>
                        <input type="date" name="tanggal_publish" value="{{ old('tanggal_publish', $post->tanggal_publish) }}"
                               class="form-control form-control-lg @error('tanggal_publish') is-invalid @enderror" required>
                        @error('tanggal_publish')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tanggal Berakhir (Opsional)</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $post->tanggal_selesai) }}"
                               class="form-control form-control-lg @error('tanggal_selesai') is-invalid @enderror">
                        <div class="form-text">Jika diisi, berita akan disembunyikan otomatis setelah tanggal ini.</div>
                        @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ganti Cover Gambar</label>
                        @if($post->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $post->foto) }}" alt="Preview" class="rounded-3 shadow-sm" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                        @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Isi Berita</label>
                <input id="isi" type="hidden" name="isi" value="{{ old('isi', $post->isi) }}">
                <trix-editor input="isi" class="trix-content border-0 bg-light p-3 rounded-3" style="min-height: 400px;"></trix-editor>
                @error('isi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn px-5 py-3 btn-outline-success">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    trix-toolbar [data-trix-button-group="file-tools"] { display: none !important; }
    .trix-content { font-size: 1.1rem; line-height: 1.8; }
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script>
    const judul = document.querySelector('#judul');
    const slug = document.querySelector('#slug');
    judul.addEventListener('change', function() {
        fetch('/daftar/checkSlug?judul=' + judul.value)
            .then(response => response.json())
            .then(data => slug.value = data.slug)
    });
</script>
@endpush
@endsection
