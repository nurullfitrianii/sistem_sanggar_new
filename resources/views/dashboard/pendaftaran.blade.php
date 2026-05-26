<div id="pendaftaran" class="scroll-mt-20 bg-[#FAFAFA] py-12">
    <div class="text-center mb-12 px-6" data-aos="fade-up">
        <span class="text-[#A47251] font-bold uppercase tracking-wider text-xs">Bergabung</span>
        <h2 class="text-3xl font-bold text-gray-900 mt-2 mb-4">Pendaftaran Siswa Baru</h2>
        <div class="w-12 h-1 bg-[#A47251] mx-auto mb-3"></div>
        <p class="text-gray-500 text-sm">Lengkapi data di bawah ini untuk memulai perjalanan seni Anda bersama kami.</p>
    </div>

    <div class="px-6 max-w-6xl mx-auto">

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-300 text-red-800 px-6 py-4 rounded-xl text-sm font-medium">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 items-stretch" data-aos="fade-up">

            <div class="lg:w-3/5 w-full bg-white border border-gray-100 rounded-3xl p-10 shadow-lg relative overflow-hidden flex flex-col justify-center items-center text-center">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#A47251]"></div>

                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-50 rounded-full mb-4">
                        <svg class="w-10 h-10 text-[#A47251]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Siap untuk Mulai?</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">Daftar online sekarang untuk proses pendaftaran yang lebih cepat dan mudah.</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full mb-10">
                    <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 transition-all hover:bg-orange-50">
                        <span class="block text-[#A47251] font-black text-lg">01</span>
                        <span class="text-[9px] uppercase font-bold text-gray-400">Data Diri</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 transition-all hover:bg-orange-50">
                        <span class="block text-[#A47251] font-black text-lg">02</span>
                        <span class="text-[9px] uppercase font-bold text-gray-400">Pilih Kelas</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 transition-all hover:bg-orange-50">
                        <span class="block text-[#A47251] font-black text-lg">03</span>
                        <span class="text-[9px] uppercase font-bold text-gray-400">Pembayaran</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 transition-all hover:bg-orange-50">
                        <span class="block text-[#A47251] font-black text-lg">04</span>
                        <span class="text-[9px] uppercase font-bold text-gray-400">Upload & Selesai</span>
                    </div>
                </div>

                <a href="{{ route('pendaftaran.step1') }}" class="w-full bg-[#A47251] text-white font-black text-lg py-5 px-8 rounded-2xl hover:bg-[#8e6245] hover:shadow-2xl transition-all transform hover:-translate-y-1 active:scale-95 flex justify-center items-center gap-3">
                    Mulai Pendaftaran Online
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>


            <div class="lg:w-2/5 w-full flex flex-col gap-6">
                <div class="bg-gradient-to-br from-[#A47251] to-[#8e6245] text-white rounded-3xl p-8 shadow-lg flex-1 border-b-8 border-[#503422]">
                    <h4 class="font-bold text-lg mb-5 border-b border-orange-800/50 pb-3 uppercase tracking-tighter">Informasi Penting</h4>
                    <div class="space-y-5">
                        <div>
                            <p class="text-[10px] font-bold mb-2 text-orange-200 uppercase tracking-widest">Kesiswaan & Syarat:</p>
                            <ul class="text-sm space-y-2 opacity-90">
                                <li class="flex items-start gap-2"><span class="text-orange-300">●</span> Terbuka untuk Anak-anak s/d Dewasa</li>
                                <li class="flex items-start gap-2"><span class="text-orange-300">●</span> Siapkan Foto KTP/KIA/Akte</li>
                                <li class="flex items-start gap-2"><span class="text-orange-300">●</span> Transfer biaya pendaftaran sesuai kelas</li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold mb-2 text-orange-200 uppercase tracking-widest">Aktivasi Akun:</p>
                            <p class="text-xs leading-relaxed opacity-80">Akun Anda akan aktif setelah tim Humas memverifikasi bukti transfer dan dokumen yang diunggah.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-lg flex-1 flex flex-col justify-center">
                    <h4 class="font-bold text-gray-900 text-lg mb-6 uppercase text-sm tracking-tight border-l-4 border-[#A47251] pl-3">Bantuan Pendaftaran</h4>
                    <ul class="text-sm text-gray-600 space-y-6">
                        <li class="flex items-center gap-4 border-b border-gray-50 pb-4">
                            <span class="bg-orange-50 p-3 rounded-2xl text-[#A47251] shadow-sm">📞</span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">WhatsApp Admin</p>
                                <span class="font-black text-gray-800 text-base">0853-2180-5039</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="bg-orange-50 p-3 rounded-2xl text-[#A47251] shadow-sm mt-0.5">📍</span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Lokasi Sekretariat</p>
                                <span class="leading-relaxed text-sm text-gray-600 font-medium italic">Jl. Pramuka Gg. Tawes No. 144, Subang</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
