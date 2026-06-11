<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard Sanggar')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white p-2 rounded-3 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('img/Logo.png') }}" height="38" alt="Logo">
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Sanggar Goong</h6>
                    <small style="color: var(--sanggar-secondary); font-weight: 500; font-size: 0.75rem;">Prasasti</small>
                </div>
            </div>
        </div>

        <ul class="nav flex-column nav-sidebar">
            @php $role = strtolower(auth()->user()->role ?? ''); @endphp

            @if($role === 'ketua')
                <li class="nav-item">
                    <a href="{{ route('dashboard.ketua') }}" class="nav-link {{ request()->routeIs('dashboard.ketua') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('siswa-admin.index') }}" class="nav-link {{ request()->routeIs('siswa-admin.*') ? 'active' : '' }}">
                        Kelola Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengguna.index') }}" class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                        Kelola Pengguna
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                        Jadwal Kelas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.absensi') }}" class="nav-link {{ request()->routeIs('laporan.absensi') ? 'active' : '' }}">
                        Laporan Kehadiran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('laporan.keuangan') }}" class="nav-link {{ request()->routeIs('laporan.keuangan') ? 'active' : '' }}">
                        Laporan Keuangan
                    </a>
                </li>

            @elseif($role === 'humas')
                <li class="nav-item">
                    <a href="{{ route('dashboard.humas') }}" class="nav-link {{ request()->routeIs('dashboard.humas') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pendaftaran.index') }}" class="nav-link {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}">
                        Pendaftaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('humas.absensi.index') }}" class="nav-link {{ request()->routeIs('humas.absensi.*') ? 'active' : '' }}">
                        Kehadiran
                    </a>
                </li>
                <li class="nav-item bg-white-20 my-2 mx-3" style="height: 1px; background: rgba(255,255,255,0.1);"></li>
                <li class="nav-item">
                    <a href="{{ route('program-admin.index') }}" class="nav-link {{ request()->routeIs('program-admin.*') ? 'active' : '' }}">
                        Kelola Program
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('berita-admin.index') }}" class="nav-link {{ request()->routeIs('berita-admin.*') ? 'active' : '' }}">
                        Kelola Berita
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('galeri-admin.index') }}" class="nav-link {{ request()->routeIs('galeri-admin.*') ? 'active' : '' }}">
                        Kelola Galeri
                    </a>
                </li>

            @elseif($role === 'bendahara')
    <li class="nav-item">
        <a href="{{ route('dashboard.bendahara') }}" class="nav-link {{ request()->routeIs('dashboard.bendahara') ? 'active' : '' }}">
            Dashboard
        </a>
    </li>
    {{-- MENU VERIFIKASI YANG BARU (HALAMAN KHUSUS) --}}
    <li class="nav-item">
        <a href="{{ route('bendahara.verifikasi_index') }}" class="nav-link {{ request()->routeIs('bendahara.verifikasi_index') ? 'active' : '' }} d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <span>Verifikasi Pembayaran</span>
            </div>
            {{-- Badge Notifikasi tetep dipasang di sini biar keren --}}
            @if(isset($totalPending) && $totalPending > 0)
                <span class="badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.4em 0.6em;">
                    {{ $totalPending }}
                </span>
            @endif
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('bendahara.iuran.index') }}" class="nav-link {{ request()->routeIs('bendahara.iuran.index') ? 'active' : '' }}">
            Data Iuran
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('keuangan-bendahara.index') }}" class="nav-link {{ request()->routeIs('keuangan-bendahara.*') ? 'active' : '' }}">
            Transaksi Keuangan
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('laporan.keuangan.bendahara') }}" class="nav-link {{ request()->routeIs('laporan.keuangan.bendahara') ? 'active' : '' }}">
            Laporan Keuangan
        </a>
    </li>

            @elseif($role === 'siswa')
                <li class="nav-item">
                    <a href="{{ route('dashboard.siswa') }}" class="nav-link {{ request()->routeIs('dashboard.siswa') ? 'active' : '' }}">
                        Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('jadwal.anggota') }}" class="nav-link">
                        Jadwal Kelas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('absensi.anggota') }}" class="nav-link">
                        Absensi
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <!-- Content -->
    <main id="content">
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn border-0 d-lg-none btn-outline-primary" id="sidebarCollapse">Aksi</button>
            </div>

            <div class="dropdown">
                <div class="user-profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-sm-block">
                        <h6 class="mb-0 fw-bold">{{ auth()->user()->username }}</h6>
                        <small class="text-muted">{{ auth()->user()->role }}</small>
                    </div>
                    <div class="user-avatar text-white">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3 mt-3">
                    <li><a class="dropdown-item py-2 px-3 rounded-2" href="{{ route('profile.index') }}">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item py-2 px-3 rounded-2 text-danger">Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <div class="container-fluid py-4 px-4">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{!! session("success") !!}',
                confirmButtonColor: '#A47251',
                confirmButtonText: 'Tutup'
            });
        });
    </script>
    @endif

    @if(session('error_403'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak!',
                text: '{!! session("error_403") !!}',
                confirmButtonColor: '#A47251',
                confirmButtonText: 'Kembali'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{!! session("error") !!}',
                confirmButtonColor: '#A47251',
                confirmButtonText: 'Tutup'
            });
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Terdapat beberapa isian yang tidak valid, mohon periksa kembali formulir Anda.',
                confirmButtonColor: '#DD9E59',
                confirmButtonText: 'Oke'
            });
        });
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Apply Litepicker to all date and month inputs
            const dateInputs = document.querySelectorAll('input[type="date"], input[type="month"]');
            dateInputs.forEach((el) => {
                const isMonth = el.getAttribute('type') === 'month';
                el.type = 'text'; // Fallback to text so the browser's default picker doesn't conflict
                new Litepicker({
                    element: el,
                    format: isMonth ? 'YYYY-MM' : 'YYYY-MM-DD',
                    singleMode: true,
                    dropdowns: {"minYear":2000,"maxYear":2030,"months":true,"years":true}
                });
            });
        });
    </script>
    <script>
        document.getElementById('sidebarCollapse')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        AOS.init({
            duration: 800,
            once: true
        });

        // Global Modal residual cleaner (Fixes "Overlay redup" bug on browser back/forward)
        window.addEventListener('pageshow', function (event) {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    </script>
    @stack('scripts')
</body>
</html>
