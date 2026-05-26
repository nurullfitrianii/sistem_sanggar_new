<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Sanggar Goong Prasasti')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Informasi Manajemen Sanggar Goong Prasasti.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .navbar-brand span {
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .hero {
            position: relative;
            min-height: 80vh;
            display: flex;
            align-items: center;
            color: #fff;
            background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.75)),
                url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1600&q=80')
                center/cover no-repeat;
        }
        .hero-tagline {
            text-transform: uppercase;
            letter-spacing: .14em;
            font-size: .8rem;
        }
        .section-title {
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #b45309;
            font-size: .85rem;
        }
        .program-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(15,23,42,.12);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .program-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 60px rgba(15,23,42,.18);
        }
        .program-icon {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            background: rgba(180,83,9,.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #b45309;
        }
        .footer {
            background: #020617;
            color: rgba(248,250,252,.7);
        }
        .badge-soft {
            background: rgba(248,250,252,.08);
            color: #f97316;
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-opacity-75 fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <span class="me-2">Sanggar</span>
            <small class="text-warning d-none d-md-inline">Budaya Nusantara</small>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="mainNav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Beranda</a></li>
                <li class="nav-item"><a href="{{ route('about') }}" class="nav-link">Tentang</a></li>

                {{-- Link yang belum ada rutenya diarahkan ke anchor (#) agar tidak error --}}
                <li class="nav-item"><a href="{{ route('home') }}#programs" class="nav-link">Program</a></li>
                <li class="nav-item"><a href="{{ route('galeri-foto') }}" class="nav-link">Galeri</a></li>
                <li class="nav-item"><a href="{{ route('home') }}#news" class="nav-link">Berita</a></li>
                <li class="nav-item"><a href="{{ route('home') }}#contact" class="nav-link">Kontak</a></li>

                @auth
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{-- Pakai username karena tabel users tidak ada kolom name --}}
                            {{ auth()->user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted">Peran: {{ ucfirst(auth()->user()->role) }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Masuk Sistem</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="mt-5 pt-4">
    @yield('content')
</main>

<footer class="footer mt-5 py-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
                <h5 class="mb-3 text-white">Sanggar Budaya Nusantara</h5>
                <p class="mb-0 small">
                    Lembaga pelestari seni tradisional yang berkomitmen menyelenggarakan pendidikan
                    nonformal di bidang tari, musik, dan vokal secara profesional dan berkelanjutan.
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="mb-3 text-white">Alamat & Kontak</h6>
                <p class="small mb-1">Jl. Contoh Raya No. 123, Kota Fiktif, Indonesia</p>
                <p class="small mb-1">Email: info@sanggar-budaya-nusantara.com</p>
                <p class="small mb-0">Telepon: (021) 0000 000</p>
            </div>
            <div class="col-md-4">
                <h6 class="mb-3 text-white">Jam Operasional</h6>
                <p class="small mb-1">Senin – Jumat: 16.00 – 21.00 WIB</p>
                <p class="small mb-1">Sabtu – Minggu: 09.00 – 18.00 WIB</p>
                <span class="badge rounded-pill badge-soft mt-2">Sanggar Seni &amp; Manajemen Modern</span>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="d-flex justify-content-between flex-column flex-md-row small">
            <span>© {{ date('Y') }} Sanggar Budaya Nusantara. Seluruh hak cipta dilindungi.</span>
            <span>Didesain dengan nuansa budaya tradisional modern.</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
@stack('scripts')
</body>
</html>
