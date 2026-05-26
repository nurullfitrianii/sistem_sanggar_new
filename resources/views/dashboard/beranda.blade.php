<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanggar Seni Goong Prasasti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/beranda.css') }}">


</head>
<body class="bg-[#FAFAFA] text-gray-800 font-sans antialiased overflow-x-hidden">

    <nav class="flex justify-between items-center px-6 md:px-10 py-4 shadow-md border-b border-gray-100 sticky top-0 bg-white z-50 transition-all">
        <div class="flex items-center gap-4">
            <img src="{{ asset('img/Logo.png') }}" alt="Logo Sanggar" class="h-12 w-auto object-contain">
            <div class="flex flex-col">
                <span class="font-extrabold text-gray-900 leading-none text-xl tracking-wide">Sanggar</span>
                <span class="text-sm text-[#A47251] font-semibold leading-tight tracking-wider uppercase">Goong Prasasti</span>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-600">
            <a href="#beranda" class="nav-link text-yellow-700 bg-yellow-50 px-4 py-2 rounded-lg transition-all duration-300">Beranda</a>
            <a href="#profil" class="nav-link hover:text-[#A47251] px-4 py-2 rounded-lg transition-all duration-300">Profil</a>
            <a href="#kelas" class="nav-link hover:text-[#A47251] px-4 py-2 rounded-lg transition-all duration-300">Kelas</a>
            <a href="#berita" class="nav-link hover:text-[#A47251] px-4 py-2 rounded-lg transition-all duration-300">Berita</a>

            <div class="relative group">
            <button class="nav-link hover:text-[#A47251] px-4 py-2 rounded-lg transition-all duration-300 flex items-center gap-1">
                Galeri <span class="text-[10px]">▼</span>
            </button>
            <div class="dropdown-content absolute left-0 w-48 bg-white shadow-2xl rounded-xl border border-gray-100 py-3 z-[100] mt-1">
                <a href="/galeri-foto" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#A47251]">Koleksi Foto</a>
                <a href="/galeri-video" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#A47251]">Koleksi Video</a>
            </div>
        </div>

            <a href="#pendaftaran" class="nav-link hover:text-[#A47251] px-4 py-2 rounded-lg transition-all duration-300">Daftar</a>
        </div>

        <div class="flex items-center gap-5 text-sm font-medium">
            @guest
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#A47251] font-bold transition-colors border-b-2 border-transparent hover:border-[#A47251]">
                    Masuk
                </a>
            @else
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-red-600 font-bold transition-colors border-b-2 border-transparent hover:border-red-600">
                        Keluar
                    </button>
                </form>
            @endguest

            <a href="https://wa.me/6285321805039" target="_blank" class="bg-[#A47251] text-white px-6 py-2.5 rounded-lg hover:bg-[#8e6245] hover:shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                Hubungi Kami
            </a>
        </div>
    </nav>

  <header id="beranda"
    class="relative text-white py-32 px-6 flex items-center justify-center min-h-[85vh] bg-cover bg-center"
    style="background-image: url('{{ asset('img/tari bg.jpg') }}');">

    <div class="absolute inset-0 header-overlay"></div>

    <div class="relative z-10 max-w-5xl mx-auto text-center flex flex-col items-center">

        <span class="text-yellow-400 font-bold tracking-widest uppercase text-xs mb-4" data-aos="fade-down">
            Seni Karawitan & Tari Tradisional Sunda
        </span>

        <h2 class="hover-float text-4xl md:text-6xl lg:text-7xl font-extrabold leading-tight">
            Sanggar Seni Goong Prasasti
        </h2>

        <p class="text-lg md:text-xl text-gray-100 font-light max-w-2xl leading-relaxed italic" data-aos="fade-up">
            "Melestarikan, Mengembangkan, dan Memperkenalkan Budaya Lokal"
        </p>

        <div class="flex flex-wrap justify-center gap-4 mt-10" data-aos="fade-up">
            <a href="#pendaftaran" class="btn-animate bg-[#A47251] text-white px-8 py-3.5 rounded-xl font-bold transition-all hover:bg-[#DD9E59] hover:text-[#A47251] active:scale-95 shadow-lg">
                Bergabung Sekarang
            </a>
            <a href="#profil" class="btn-animate bg-transparent border-2 border-white text-white px-8 py-3.5 rounded-xl font-bold hover:bg-white hover:text-black transition-all active:scale-95">
                Pelajari Lebih Lanjut
            </a>
        </div>
    </div>
</header>

    <div class="reveal" data-aos="fade-up"> @include('dashboard.profil') </div>
    <div class="reveal" data-aos="fade-left" data-aos-delay="100"> @include('dashboard.kelas') </div>
    <div class="reveal" data-aos="fade-right" data-aos-delay="100"> @include('dashboard.galeri') </div>
    <div class="reveal" data-aos="zoom-in"> @include('dashboard.berita_section') </div>
    <div class="reveal" data-aos="fade-up"> @include('dashboard.pendaftaran') </div>

    <section class="py-20 px-6 md:px-10 bg-white border-t border-gray-100" data-aos="fade-up">
        <div class="max-w-7xl mx-auto text-center mb-12">
            <span class="text-[#A47251] font-bold uppercase tracking-wider text-xs">Kunjungi Kami</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2 mb-4">Lokasi Sanggar</h2>
            <div class="w-12 h-1 bg-[#A47251] mx-auto mb-4"></div>
            <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3267.075283618955!2d107.77650527499307!3d-6.541262493451598!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693b658b8dd217%3A0xe479f7e3402ea3ee!2sSanggar%20Seni%20Goong%20Prasasti!5e1!3m2!1sid!2sid!4v1776007775425!5m2!1sid!2sid%22%20width=%22600%22%20height=%22450%22%20style=%22border:0;%22%20allowfullscreen=%22%22%20loading=%22lazy%22%20referrerpolicy=%22no-referrer-when-downgrade"
                width="100%"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        </div>
    </section>

    <footer class="bg-gradient-to-br from-white via-gray-50 to-orange-50/40 text-gray-700 pt-20 pb-10 px-6 md:px-10 border-t-4 border-[#A47251] relative">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-gray-200 pb-12 mb-8">
            <div class="md:col-span-2 pr-10">
                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ asset('img/Logo.png') }}" alt="Logo Sanggar" class="h-14 w-auto object-contain">
                    <div>
                        <h4 class="font-extrabold text-gray-900 text-xl">Sanggar</h4>
                        <p class="text-sm text-[#A47251] font-bold uppercase">Goong Prasasti</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600">Wadah pembinaan dan pengembangan seni budaya Sunda.</p>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-6 uppercase text-sm">Tautan Cepat</h4>
                <ul class="text-sm space-y-3">
                    <li><a href="#beranda" class="hover:text-[#A47251]">Beranda</a></li>
                    <li><a href="#profil" class="hover:text-[#A47251]">Profil Kami</a></li>
                    <li><a href="#kelas" class="hover:text-[#A47251]">Program Kelas</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-6 uppercase text-sm">Kontak</h4>
                <ul class="text-sm space-y-4">
                    <li>📞 0853-2180-5039</li>
                    <li>📍 Subang, Jawa Barat</li>
                </ul>
            </div>
        </div>
        <div class="text-center text-sm text-gray-500"> © {{ date('Y') }} Sanggar Seni Goong Prasasti. </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
    // --- 1. LOGIKA POP-UP PENDAFTARAN SUKSES (DI LUAR DOM agar lebih cepat) ---
    @if(session('pendaftaran_sukses'))
        Swal.fire({
            title: '<span class="text-2xl font-bold text-gray-800">Berhasil!</span>',
            html: '<p class="text-gray-500 leading-relaxed px-4">Selamat! Pendaftaran Anda sudah kami terima. <br> Silakan bergabung ke grup WhatsApp untuk koordinasi latihan.</p>',
            icon: 'success',
            iconColor: '#A47251',
            showCancelButton: true,
            confirmButtonText: 'Gabung Grup WA',
            cancelButtonText: 'Tutup',
            background: '#ffffff',
            padding: '1.5rem',
            borderRadius: '30px',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[30px] shadow-2xl',
                confirmButton: 'bg-[#A47251] text-white px-10 py-3 rounded-full font-bold mx-2 hover:bg-[#8e6245] transition-all transform hover:scale-105 shadow-lg',
                cancelButton: 'bg-gray-100 text-gray-400 px-8 py-3 rounded-full font-bold mx-2 hover:bg-gray-200 transition-all'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'https://chat.whatsapp.com/HxiTzSjq6Z17GHPqcC0mso';
            }
        });
    @endif

    // Script interaksi lainnya
    document.addEventListener('DOMContentLoaded', function() {

        // --- 2. ANIMASI SCROLL REVEAL ---
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.05, rootMargin: "0px 0px -80px 0px" });

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        // Inisialisasi AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
        });

        // --- 3. EFEK INTERAKTIF TOMBOL ---
        document.querySelectorAll('a, button').forEach(btn => {
            btn.addEventListener('mousedown', () => btn.style.transform = 'scale(0.95)');
            btn.addEventListener('mouseup', () => btn.style.transform = 'scale(1.05) translateY(-2px)');
        });

        // --- 4. ZOOM GAMBAR KELAS ---
        document.querySelectorAll('#kelas img').forEach(img => {
            img.style.transition = 'all 0.5s ease-in-out';
            img.addEventListener('mouseenter', () => img.style.transform = 'scale(1.1)');
            img.addEventListener('mouseleave', () => img.style.transform = 'scale(1)');
        });

    });
    </script>
</body>
</html>
