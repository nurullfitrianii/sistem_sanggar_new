@extends('layouts.app')

@section('title', 'Galeri | Sanggar Budaya Nusantara')

@section('content')
<section class="py-5 mt-4">
    <div class="container">
        <p class="section-title mb-2">Galeri</p>
        <h1 class="h3 mb-4">Dokumentasi Kegiatan Sanggar</h1>
        <p class="text-muted mb-4">
            Seluruh foto pada halaman ini menggunakan tautan gambar bebas sebagai ilustrasi. Anda dapat menggantinya
            dengan dokumentasi resmi sanggar, seperti kegiatan latihan, pementasan, maupun program kolaborasi.
        </p>
        <div class="row g-3">
            @forelse($galleries as $galeri)
                <div class="col-6 col-md-3">
                    <figure class="mb-0">
                        <img src="{{ $galeri->gambar }}" class="img-fluid rounded-3"
                             alt="Dokumentasi {{ $galeri->judul }}">
                        <figcaption class="small text-muted mt-1">
                            {{ $galeri->judul }}
                        </figcaption>
                    </figure>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">
                        Belum terdapat data galeri. Tambahkan dokumentasi melalui panel admin humas.
                    </p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $galleries->links() }}
        </div>
    </div>
</section>
@endsection

