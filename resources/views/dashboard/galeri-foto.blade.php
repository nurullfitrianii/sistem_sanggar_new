<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Foto - Sanggar Goong Prasasti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-[#FAFAFA] antialiased">

    <section class="py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Koleksi Foto</h1>
                    <p class="text-gray-500 mt-2">Dokumentasi kegiatan dan pementasan Sanggar Goong Prasasti.</p>
                </div>
                <a href="/" class="bg-[#994D1C] text-white px-6 py-2.5 rounded-lg font-bold hover:bg-[#7a3d16] transition-all flex items-center gap-2">
                    ← Kembali ke Beranda
                </a>
            </div>

            <!-- Grid Foto -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Foto dari Database (Dynamic) -->
                @foreach($galleries as $galeri)
                    @if($galeri->gambar)
                        <div class="reveal group relative overflow-hidden rounded-2xl shadow-md hover:shadow-xl transition-all duration-500">
                            <img src="{{ asset('storage/' . $galeri->gambar) }}"
                                alt="{{ $galeri->judul }}"
                                class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <!-- Modern Hover Overlay -->
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5 text-white">
                                <h3 class="font-bold text-lg translate-y-2 group-hover:translate-y-0 transition-transform duration-300">{{ $galeri->judul }}</h3>
                                @if($galeri->deskripsi)
                                    <p class="text-sm text-gray-200 mt-1 line-clamp-2 translate-y-2 group-hover:translate-y-0 transition-transform duration-300 delay-75">{{ $galeri->deskripsi }}</p>
                                @endif
                                @if($galeri->tanggal)
                                    <span class="text-xs text-gray-300 mt-2 translate-y-2 group-hover:translate-y-0 transition-transform duration-300 delay-150">
                                        {{ \Carbon\Carbon::parse($galeri->tanggal)->translatedFormat('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Foto Default (Static) -->
                @php
                    $fotos = [
                        'tari2.jpg',
                        'tari3.jpg', // Pastikan file ini ada di folder img
                        'tari4.jpg', // Pastikan file ini ada di folder img
                        'vokal kawih.JPG', // Sesuaikan JPG besar jika di folder huruf besar
                        'IMG_5309.JPG',
                        'karawitan.JPG',
                        'suling.jpg',
                        'DSCF3336.JPG', // Pastikan file ini ada
                        'DSCF3305.JPG', // Menggunakan gambar yang ada di folder public/img yang belum digunakan
                        'IMG_5335.JPG',
                        'tari tradisional.JPG',
                        'pentas seni.JPG'
                    ];
                @endphp

                @foreach($fotos as $foto)
                    <div class="reveal group overflow-hidden rounded-2xl shadow-md hover:shadow-xl transition-all duration-500">
                        <img src="{{ asset('img/' . $foto) }}"
                            alt="Galeri Sanggar"
                            class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
