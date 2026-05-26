@extends('layouts.app')

@section('title', 'Kontak | Sanggar Budaya Nusantara')

@section('content')
<section class="py-5 mt-4">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6">
                <p class="section-title mb-2">Kontak</p>
                <h1 class="h3 mb-3">Hubungi Pengelola Sanggar</h1>
                <p class="text-muted">
                    Formulir ini disediakan sebagai contoh kanal komunikasi resmi antara masyarakat dan pengelola sanggar.
                    Silakan isi dengan informasi singkat dan jelas. Seluruh data yang dimasukkan pada tahap pengembangan
                    bersifat dummy dan dapat dihapus sewaktu-waktu.
                </p>
                <ul class="list-unstyled small text-muted mt-3">
                    <li>Alamat: Jl. Contoh Raya No. 123, Kota Fiktif</li>
                    <li>Email: info@sanggar-budaya-nusantara.com</li>
                    <li>Telepon: (021) 0000 000</li>
                </ul>
            </div>
            <div class="col-lg-6">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('public.contact.submit') }}" class="card border-0 shadow-sm p-4">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               class="form-control @error('nama') is-invalid @enderror" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subjek Pesan</label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="form-control @error('subject') is-invalid @enderror">
                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Pesan</label>
                        <textarea name="pesan" rows="4"
                                  class="form-control @error('pesan') is-invalid @enderror"
                                  required>{{ old('pesan') }}</textarea>
                        @error('pesan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn w-100 btn-outline-primary">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

