@extends('layouts.app')

@section('title', 'Beranda | Sanggar Budaya Nusantara')

@section('content')
<section class="hero">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <p class="hero-tagline text-warning mb-3">Sanggar Seni Tradisional • Sejak 2010</p>
                <h1 class="display-5 fw-semibold mb-3">
                    Menghidupkan Kembali Warisan <span class="text-warning">Budaya Nusantara</span>
                </h1>
                <p class="lead mb-4">
                    Sanggar Budaya Nusantara merupakan wadah pembinaan seni tari, musik tradisional, dan vokal
                    yang dikelola secara profesional dengan kurikulum terstruktur dan pelatih berpengalaman.
                    Seluruh informasi dalam laman ini bersifat ilustratif dan dapat disesuaikan dengan kebutuhan lembaga Anda.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('public.programs') }}" class="btn px-4 py-2 btn-outline-primary">
                        Lihat Program Latihan
                    </a>
                    <a href="{{ route('public.galleries') }}" class="btn px-4 py-2 btn-outline-primary">
                        Dokumentasi Kegiatan
                    </a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="card bg-opacity-50 text-light section-card">
                    <img src="https://images.unsplash.com/photo-1518834633479-74c184cb92b3?auto=format&fit=crop&w=900&q=80"
                         class="card-img-top" alt="Ilustrasi latihan tari tradisional">
                    <div class="card-body">
                        <h5 class="card-title">Latihan Terarah, Atmosfer Kekeluargaan</h5>
                        <p class="card-text small">
                            Kegiatan latihan dilakukan secara berkala dengan pendekatan edukatif dan disiplin
                            agar peserta dapat mengembangkan kemampuan teknis sekaligus karakter seni yang beretika.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <p class="section-title mb-2">Program Unggulan</p>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h3 mb-0">Kelas Seni yang Terstruktur</h2>
            <a href="{{ route('public.programs') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
        </div>
        <div class="row g-4">
            @forelse($programs as $program)
                <div class="col-md-4">
                    <article class="program-card p-4 bg-white h-100">
                        <div class="program-icon mb-3">
                            <span class="fw-bold">{{ strtoupper(mb_substr($program->judul, 0, 1)) }}</span>
                        </div>
                        <h3 class="h5">{{ $program->judul }}</h3>
                        <p class="small text-muted">
                            {{ \Illuminate\Support\Str::limit($program->deskripsi, 160) }}
                        </p>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted small">
                        Data program pada halaman ini masih berupa contoh dan dapat Anda tambahkan melalui panel admin.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <p class="section-title mb-2">Galeri Kegiatan</p>
        <h2 class="h3 mb-4">Dokumentasi Latihan dan Pementasan</h2>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <img src="https://images.unsplash.com/photo-1514890547357-a9ee288728e0?auto=format&fit=crop&w=600&q=80"
                     class="img-fluid rounded-3" alt="Ilustrasi pementasan tari tradisional">
            </div>
            <div class="col-6 col-md-3">
                <img src="https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=600&q=80"
                     class="img-fluid rounded-3" alt="Ilustrasi latihan musik tradisional">
            </div>
            <div class="col-6 col-md-3">
                <img src="https://images.unsplash.com/photo-1474898856510-884a2c0be546?auto=format&fit=crop&w=600&q=80"
                     class="img-fluid rounded-3" alt="Ilustrasi penampilan kelompok tari">
            </div>
            <div class="col-6 col-md-3">
                <img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab42?auto=format&fit=crop&w=600&q=80"
                     class="img-fluid rounded-3" alt="Ilustrasi ruangan sanggar seni">
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <p class="section-title mb-2">Berita &amp; Informasi</p>
        <h2 class="h3 mb-4">Kabari Kegiatan Sanggar</h2>
        <p class="small text-muted mb-4">
            Seluruh informasi pada bagian ini merupakan contoh dan dapat diganti dengan berita resmi sanggar,
            seperti jadwal pementasan, kegiatan pengabdian masyarakat, maupun publikasi kerja sama.
        </p>
    </div>
</section>
@endsection

