@extends('layouts.admin')

@section('title', 'Ubah Kelas')

@section('content')
<h1 class="h4 mb-3">Ubah Data Program Kelas</h1>

<form action="{{ route('kelas.update', $kelas->id_program) }}" method="POST" class="card border-0 shadow-sm p-4">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nama Program</label>
        <input type="text" name="nama_program" value="{{ old('nama_program', $kelas->nama_program) }}"
               class="form-control @error('nama_program') is-invalid @enderror" required>
        @error('nama_program')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
            @foreach(['Seni Tari', 'Seni Karawitan', 'Seni Musik'] as $kat)
                <option value="{{ $kat }}" {{ old('kategori', $kelas->kategori) === $kat ? 'selected' : '' }}>
                    {{ $kat }}
                </option>
            @endforeach
        </select>
        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Biaya (Rp)</label>
        <input type="number" name="biaya" value="{{ old('biaya', $kelas->biaya) }}"
               class="form-control @error('biaya') is-invalid @enderror">
        @error('biaya')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" rows="3"
                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $kelas->deskripsi) }}</textarea>
        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="Aktif" {{ old('status', $kelas->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Nonaktif" {{ old('status', $kelas->status) === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-outline-success">Simpan Perubahan</button>
        <a href="{{ route('kelas.index') }}" class="btn btn-outline-primary">Batal</a>
    </div>
</form>
@endsection

