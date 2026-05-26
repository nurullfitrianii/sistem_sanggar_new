@extends('layouts.admin')

@section('title', 'Tambah Galeri')

@section('content')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 fw-bold mb-0">Tambah Item Galeri</h1>
            <a href="{{ route('galeri-admin.index') }}" class="btn rounded-pill px-4 btn-outline-primary">
                Kembali
            </a>
        </div>

        <form action="{{ route('galeri-admin.store') }}" method="POST" enctype="multipart/form-data" class="card section-card p-5">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-bold">Judul Foto/Video</label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                       class="form-control form-control-lg @error('judul') is-invalid @enderror" 
                       placeholder="Misal: Penampilan Tari Jaipong 2024" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih File Gambar</label>
                        <input type="file" name="gambar"
                               class="form-control @error('gambar') is-invalid @enderror" accept="image/*" required>
                        <div class="form-text">Max 3MB. Format: JPG, PNG, JPEG.</div>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                               class="form-control @error('tanggal') is-invalid @enderror">
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3"
                          class="form-control @error('deskripsi') is-invalid @enderror"
                          placeholder="Ceritakan sedikit tentang kegiatan ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn px-5 btn-outline-primary">
                    Unggah Galeri
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@endsection

