@extends('layouts.admin')

@section('title', 'Profil Pengguna')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Pengaturan Profil</h2>
        <p class="text-muted">Kelola informasi akun dan kata sandi Anda.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="card section-card p-4 p-md-5">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-5">
                    <div class="col-md-4">
                        <h6 class="fw-bold text-dark uppercase small">Informasi Akun</h6>
                        <p class="text-muted small">Update nama pengguna Anda di sini.</p>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control @error('username') is-invalid @enderror" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Role</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->role }}" readonly>
                            <div class="form-text">Role akun tidak dapat diubah oleh pengguna.</div>
                        </div>
                    </div>
                </div>

                <hr class="my-5 opacity-10">

                <div class="row">
                    <div class="col-md-4">
                        <h6 class="fw-bold text-dark uppercase small">Ganti Password</h6>
                        <p class="text-muted small">Kosongkan jika Anda tidak ingin mengubah password.</p>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru...">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru...">
                        </div>
                    </div>
                </div>

                <div class="text-end mt-5">
                    <button type="submit" class="btn px-5 shadow-sm btn-outline-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
