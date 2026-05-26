@extends('layouts.admin')

@section('title', 'Ubah Galeri')

@section('content')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 fw-bold mb-0">Ubah Item Galeri</h1>
            <a href="{{ route('galeri-admin.index') }}" class="btn rounded-pill px-4 btn-outline-primary">
                Kembali
            </a>
        </div>

        <form action="{{ route('galeri-admin.update', $galeri) }}" method="POST" enctype="multipart/form-data" class="card section-card p-5">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="form-label fw-bold">Judul Foto/Video</label>
                <input type="text" name="judul" value="{{ old('judul', $galeri->judul) }}"
                       class="form-control form-control-lg @error('judul') is-invalid @enderror" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ubah File Gambar</label>
                        @if($galeri->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $galeri->gambar) }}" alt="Preview" class="rounded-3 shadow-sm w-100 object-cover" style="height: 120px;">
                            </div>
                        @endif
                        <input type="file" name="gambar"
                               class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $galeri->tanggal) }}"
                               class="form-control @error('tanggal') is-invalid @enderror">
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3"
                          class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn px-5 btn-outline-success">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@endsection

