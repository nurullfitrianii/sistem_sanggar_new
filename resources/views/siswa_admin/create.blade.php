@extends('layouts.admin')

@section('title', 'Tambah Siswa')

@section('content')
<h1 class="h4 mb-3">Tambah Siswa Baru</h1>

<form autocomplete="off" action="{{ route('siswa-admin.store') }}" method="POST" class="card border-0 shadow-sm p-4">
    @csrf
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username') }}"
               class="form-control @error('username') is-invalid @enderror" required>
        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Kata Sandi</label>
        <input type="password" autocomplete="new-password" name="password"
               class="form-control @error('password') is-invalid @enderror" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Status Kesiswaan</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-outline-success">Simpan</button>
        <a href="{{ route('siswa-admin.index') }}" class="btn btn-outline-primary">Batal</a>
    </div>
</form>
@endsection
