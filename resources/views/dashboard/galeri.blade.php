<div id="galeri" class="scroll-mt-20 py-16 bg-white">
    <div class="text-center mb-12" data-aos="fade-up">
        <span class="text-[#A47251] font-bold uppercase tracking-wider text-xs">Portofolio Kami</span>
        <h2 class="text-3xl font-bold text-gray-900 mt-2 mb-4">Galeri & Dokumentasi</h2>
        <div class="w-12 h-1 bg-[#A47251] mx-auto mb-4"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-20" data-aos="fade-up">
            <div class="flex flex-row overflow-hidden rounded-2xl shadow-2xl mb-8 group">
                <img src="{{ asset('img/DSCF3336.JPG') }}"
                    class="w-1/4 h-80 object-cover hover:scale-110 transition-transform duration-700 cursor-pointer">

                <img src="{{ asset('img/tari2.jpg') }}"
                    class="w-1/4 h-80 object-cover hover:scale-110 transition-transform duration-700 cursor-pointer border-l border-white">

                <img src="{{ asset('img/tari3.jpg') }}"
                    class="w-1/4 h-80 object-cover hover:scale-110 transition-transform duration-700 cursor-pointer border-l border-white">

                <img src="{{ asset('img/IMG_5336.JPG') }}"
                    class="w-1/4 h-80 object-cover hover:scale-110 transition-transform duration-700 cursor-pointer border-l border-white">
            </div>
            <div class="text-center">
                <a href="/galeri-foto" class="inline-block bg-[#A47251] text-white px-10 py-3.5 rounded-xl font-bold hover:bg-[#8e6245] hover:shadow-xl transition-all transform hover:scale-105 active:scale-95 shadow-md">
                    LIHAT SEMUA FOTO
                </a>
            </div>
        </div>


        <div data-aos="fade-up" class="max-w-4xl mx-auto mb-10">
            <!-- Interactive Video Wrapper -->
            <div class="relative group">
                <!-- Ambient Glow Effect behind card -->
                <div class="absolute -inset-4 bg-gradient-to-r from-[#A47251] to-[#DD9E59] rounded-3xl blur-2xl opacity-15 group-hover:opacity-25 transition-all duration-700"></div>

                <!-- Main Card Container -->
                <div class="relative bg-white p-3 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.06)] border border-gray-100/80 transition-all duration-500 hover:shadow-[0_20px_60px_rgba(164,114,81,0.12)]">
                    <!-- Video Display Area -->
                    <div id="featured-video-player" class="relative w-full overflow-hidden rounded-2xl aspect-video bg-stone-900 shadow-inner cursor-pointer" onclick="playFeaturedVideo(this, 'wjknyYcl4HY')">
                        <!-- Cover Thumbnail Image -->
                        <img src="https://img.youtube.com/vi/wjknyYcl4HY/maxresdefault.jpg" alt="Video Thumbnail" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-102 opacity-95 group-hover:opacity-90">
                        
                        <!-- Dark Overlay on hover -->
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors duration-500"></div>
                        
                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-20 h-20 bg-[#A47251] text-white rounded-full flex items-center justify-center shadow-2xl transform transition-all duration-500 group-hover:scale-110 group-hover:bg-[#8e6245] relative">
                                <!-- Ripple Animation rings -->
                                <div class="absolute inset-0 rounded-full bg-[#A47251] animate-ping opacity-25"></div>
                                <svg class="w-8 h-8 fill-current translate-x-0.5" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Putar Video Label -->
                        <span class="absolute bottom-4 right-4 bg-black/70 backdrop-blur-sm text-white text-xs font-semibold px-3.5 py-2 rounded-full flex items-center gap-1.5 shadow-md">
                            <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                            Putar Video
                        </span>
                    </div>

                    <!-- Video Metadata & Description -->
                    <div class="p-6 text-left flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mt-1">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="bg-[#A47251]/10 text-[#A47251] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Video Sorotan</span>
                                <span class="text-xs text-gray-400 font-medium">• Dokumentasi Kegiatan</span>
                            </div>
                            <h3 class="text-2xl font-extrabold text-gray-900 group-hover:text-[#A47251] transition-colors duration-300">Evaluasi Sanggar Goong Prasasti — Rampak Kendang</h3>
                            <p class="text-gray-500 text-sm max-w-2xl leading-relaxed">
                                Saksikan pertunjukan Rampak Kendang yang energik dan harmonis oleh para siswa bertalenta di Sanggar Seni Goong Prasasti.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script to load iframe dynamically -->
            <script>
                function playFeaturedVideo(container, videoId) {
                    container.onclick = null; // Remove click action
                    container.innerHTML = `
                        <iframe
                            class="absolute top-0 left-0 w-full h-full rounded-2xl"
                            src="https://www.youtube.com/embed/` + videoId + `?autoplay=1&rel=0"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen>
                        </iframe>
                    `;
                }
            </script>

            <div class="text-center mt-10">
                <a href="/galeri-video" class="inline-block bg-[#A47251] text-white px-10 py-3.5 rounded-xl font-bold hover:bg-[#8e6245] hover:shadow-xl transition-all transform hover:scale-105 active:scale-95 shadow-md">
                    LIHAT SEMUA VIDEO
                </a>
            </div>
        </div>

    </div>
</div>
