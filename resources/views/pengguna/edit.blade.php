@extends('layouts.admin')

@section('title', 'Ubah Pengguna')

@section('content')
<h1 class="h4 mb-3">Ubah Data Staf</h1>

<form autocomplete="off" action="{{ route('pengguna.update', $pengguna->id_user) }}" method="POST" class="card border-0 shadow-sm p-4">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username', $pengguna->username) }}"
               class="form-control @error('username') is-invalid @enderror" required>
        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email (Opsional)</label>
        <input type="email" name="email" value="{{ old('email', $pengguna->email) }}"
               class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Kata Sandi (opsional)</label>
        <input type="password" autocomplete="new-password" name="password"
               class="form-control @error('password') is-invalid @enderror">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Kosongkan jika tidak ingin mengubah kata sandi.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Role Akses</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="Humas" {{ old('role', $pengguna->role) === 'Humas' ? 'selected' : '' }}>Humas</option>
            <option value="Bendahara" {{ old('role', $pengguna->role) === 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
            <option value="Ketua" {{ old('role', $pengguna->role) === 'Ketua' ? 'selected' : '' }}>Ketua</option>
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Status Kesiswaan</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="Aktif" {{ old('status', $pengguna->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Nonaktif" {{ old('status', $pengguna->status) === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-outline-success">Simpan Perubahan</button>
        <a href="{{ route('pengguna.index') }}" class="btn btn-outline-primary">Batal</a>
    </div>
</form>
@endsection
