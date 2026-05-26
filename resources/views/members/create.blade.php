@extends('layouts.admin')

@section('title', 'Tambah Siswa')

@section('content')
<h1 class="h4 mb-3">Tambah Administrator Baru</h1>

<form action="{{ route('members.store') }}" method="POST" class="card border-0 shadow-sm p-4">
    @csrf
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username') }}"
               class="form-control @error('username') is-invalid @enderror" required>
        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Kata Sandi</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Role Akses</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="" disabled selected>Pilih Role</option>
            <option value="Humas" {{ old('role') === 'Humas' ? 'selected' : '' }}>Humas</option>
            <option value="Bendahara" {{ old('role') === 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
            <option value="Ketua" {{ old('role') === 'Ketua' ? 'selected' : '' }}>Ketua</option>
        </select>
        <small class="text-muted mt-1">Siswa dilarang ditambahkan manual. Siswa harus register melalui form Landing Page.</small>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
        <a href="{{ route('members.index') }}" class="btn btn-outline-primary">Batal</a>
    </div>
</form>
@endsection

