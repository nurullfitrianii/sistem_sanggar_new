<section id="berita" class="py-20 px-6 md:px-10 bg-[#FAFAFA]" data-aos="fade-up">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="text-[#A47251] font-bold uppercase tracking-wider text-xs">Berita & Artikel</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mt-2 mb-4 tracking-tight">Kabar Terbaru Sanggar</h2>
            <div class="w-16 h-1 bg-[#A47251] mx-auto mb-6"></div>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed">
                Informasi, kegiatan, dan prestasi terbaru dari Sanggar Goong Prasasti.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($berita as $news)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col h-full">
                    <div class="relative h-48 overflow-hidden rounded-t-2xl">
                        @if($news->foto)
                            <img src="{{ Storage::url($news->foto) }}" alt="{{ $news->judul }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-[#A47251] text-xs font-bold px-3 py-1.5 rounded-pill shadow-sm">
                            {{ \Carbon\Carbon::parse($news->created_at)->translatedFormat('d M Y') }}
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $news->judul }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($news->isi), 120) }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-gray-400">
                    <p>Belum ada kabar atau berita yang dipublikasikan saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
