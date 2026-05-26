@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('content')
<h1 class="h4 mb-3">Tambah Program Kelas Baru</h1>

<form action="{{ route('kelas.store') }}" method="POST" class="card border-0 shadow-sm p-4">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nama Program</label>
        <input type="text" name="nama_program" value="{{ old('nama_program') }}"
               class="form-control @error('nama_program') is-invalid @enderror" required placeholder="Contoh: Tari Jaipong Dasar">
        @error('nama_program')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Seni Tari" {{ old('kategori') === 'Seni Tari' ? 'selected' : '' }}>Seni Tari</option>
            <option value="Seni Karawitan" {{ old('kategori') === 'Seni Karawitan' ? 'selected' : '' }}>Seni Karawitan</option>
            <option value="Seni Musik" {{ old('kategori') === 'Seni Musik' ? 'selected' : '' }}>Seni Musik</option>
        </select>
        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Biaya (Rp)</label>
        <input type="number" name="biaya" value="{{ old('biaya') }}"
               class="form-control @error('biaya') is-invalid @enderror" placeholder="Contoh: 150000">
        @error('biaya')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" rows="3"
                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-outline-success">Simpan</button>
        <a href="{{ route('kelas.index') }}" class="btn btn-outline-primary">Batal</a>
    </div>
</form>
@endsection

