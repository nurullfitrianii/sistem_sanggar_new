<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Video - Sanggar Goong Prasasti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-[#FAFAFA] antialiased">

    <section class="py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Koleksi Video</h1>
                    <p class="text-gray-500 mt-2">Cuplikan pementasan dan latihan karawitan & tari.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="https://www.youtube.com/@ujangkodim" target="_blank" class="bg-red-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-red-700 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.377.55a3.016 3.016 0 0 0-2.122 2.136C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.55 9.376.55 9.376.55s7.505 0 9.377-.55a3.016 3.016 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        Subscribe @UjangKodim
                    </a>
                    <a href="/" class="bg-[#994D1C] text-white px-6 py-2.5 rounded-lg font-bold hover:bg-[#7a3d16] transition-all flex items-center gap-2">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              @php
            $videos = [
            ['id' => 'LYplKed1MEQ', 'judul' => 'Seni Tari Jaipong - Sanggar Goong Prasasti'],
            ['id' => 'wmLcdfq-TSU', 'judul' => 'Pementasan Rampak Kendang Sunda'],
            ['id' => 'wjknyYcl4HY', 'judul' => 'Evaluasi Sanggar Goong Prasasti - Rampak Kendang'],
            ['id' => 'WARX2EP02co', 'judul' => 'Dokumentasi Penampilan Seni Budaya']
        ];
    @endphp

                @foreach($videos as $video)
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 group" data-aos="fade-up">
                    <div class="relative w-full aspect-video">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/{{ $video['id'] }}?si=PqGLHeCYeLp2QZai" 
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            referrerpolicy="strict-origin-when-cross-origin" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#994D1C] transition-colors duration-300">{{ $video['judul'] }}</h3>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
