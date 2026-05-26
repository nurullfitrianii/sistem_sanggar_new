<div id="kelas" class="scroll-mt-20 py-10 bg-white">
    <div class="text-center mb-16 px-6">
        <span class="text-[#A47251] font-bold uppercase tracking-wider text-sm">Program Kami</span>
        <h2 class="text-4xl font-bold text-gray-900 mt-2 mb-4">Kelas yang Tersedia</h2>
        <div class="w-16 h-1 bg-[#A47251] mx-auto mb-4"></div>
        <p class="text-gray-600">Pilih kelas yang sesuai dengan minat dan kemampuan Anda.</p>
    </div>

    <div class="px-6 md:px-10 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">

        @forelse($programKelas as $item)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col">

            <div class="h-56 overflow-hidden relative">
                @php
                    $imagePath = $item->foto;
                    if ($imagePath && str_contains($imagePath, 'programs/')) {
                        $imagePath = str_replace('programs/', '', $imagePath);
                    }
                    $finalImage = $item->foto && file_exists(public_path('img/' . $imagePath)) 
                        ? asset('img/' . $imagePath) 
                        : ($item->foto ? asset('storage/' . $item->foto) : asset('img/default-program.jpg'));
                @endphp
                <img src="{{ $finalImage }}" alt="{{ $item->nama_program }}" class="w-full h-full object-cover">
            </div>

            <div class="p-6 md:p-8 flex flex-col flex-grow">

                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $item->nama_program }}</h3>
                    <span class="bg-orange-100 text-[#A47251] text-xs px-2.5 py-1 rounded-md font-semibold whitespace-nowrap">
                        👥 {{ $item->pendaftaran->where('status', 'Aktif')->count() }} Siswa
                    </span>
                </div>

                <p class="text-gray-600 text-sm leading-relaxed mb-5 flex-grow">
                    {{ $item->deskripsi }}
                </p>

                <div class="mb-5">
                    <p class="text-xs text-gray-500 mb-2">Kategori Kelas:</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="border border-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">{{ $item->kategori }}</span>
                    </div>
                </div>

                <div class="mb-5 space-y-2">
                    <p class="text-xs text-gray-500 mb-1">Jadwal Latihan:</p>
                    @php
                        $jadwals = $item->jadwalLatihan;
                        if ($jadwals->isEmpty()) {
                            // Fallback jika jadwal belum diisi di database
                            $isTari = str_contains(strtolower($item->kategori), 'tari');
                            $defaultJam = $isTari ? '10.00 - 13.00' : '13.00 - 17.00';
                            $hariDefault = ['Sabtu', 'Minggu'];
                        }
                    @endphp

                    @forelse($jadwals as $jdwl)
                    <div class="border border-gray-200 rounded-lg p-3 flex justify-between items-center bg-gray-50/30">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <span class="text-orange-600">📅</span> {{ $jdwl->hari }} {{ \Carbon\Carbon::parse($jdwl->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($jdwl->jam_selesai)->format('H.i') }} WIB
                        </div>
                        <span class="bg-blue-50 text-blue-600 text-xs px-3 py-1 rounded-md font-medium">Reguler</span>
                    </div>
                    @empty
                        @foreach($hariDefault ?? [] as $hari)
                        <div class="border border-gray-200 rounded-lg p-3 flex justify-between items-center bg-gray-50/30 mb-2">
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <span class="text-orange-600">📅</span> {{ $hari }} {{ $defaultJam }} WIB
                            </div>
                            <span class="bg-blue-50 text-blue-600 text-xs px-3 py-1 rounded-md font-medium">Reguler</span>
                        </div>
                        @endforeach
                    @endforelse
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 text-lg">👨‍🏫</span>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Pelatih</p>
                            <p class="text-sm font-medium text-gray-800">
                                @php
                                    $pelatih = $item->jadwalLatihan->pluck('pelatih.nama_pelatih')->unique()->filter()->implode(', ');
                                @endphp
                                {{ $pelatih ?: 'Tim Pelatih' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 text-lg">⏱️</span>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Durasi</p>
                            <p class="text-sm font-medium text-gray-800">
                                @if($item->jadwalLatihan->isNotEmpty())
                                    @php
                                        $hari = $item->jadwalLatihan->pluck('hari')->unique()->implode(' & ');
                                        $jam = \Carbon\Carbon::parse($item->jadwalLatihan->first()->jam_mulai)->format('H.i') . ' - ' . \Carbon\Carbon::parse($item->jadwalLatihan->first()->jam_selesai)->format('H.i');
                                    @endphp
                                    {{ $hari }} ({{ $jam }})
                                @elseif(isset($defaultJam))
                                    Sabtu & Minggu ({{ $defaultJam }})
                                @else
                                    {{ $item->durasi ?: '-' }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-4 border-t border-gray-100">
                    <div class="bg-yellow-50/60 border border-yellow-200 rounded-xl p-4 flex justify-between items-end mb-4">
                        <div>
                            <span class="text-orange-800/70 text-xs font-semibold block mb-1">Biaya Iuran</span>
                            <span class="text-[#A47251] font-extrabold text-xl">Rp {{ number_format($item->biaya, 0, ',', '.') }}<span class="text-sm font-medium text-gray-500">/bulan</span></span>
                        </div>
                        <span class="text-[#A47251] text-xs font-semibold">{{ $item->jumlah_sesi ?: '-' }}</span>
                    </div>

                    <a href="#pendaftaran" class="block text-center bg-[#A47251] text-white font-bold py-3.5 px-8 rounded-lg hover:bg-[#8e6245] transition-colors shadow-md w-full">
                        Daftar Kelas Ini
                    </a>
                </div>

            </div>
        </div>
        @empty
        <div class="col-span-2 text-center py-20 text-gray-500">
            <p>Belum ada program kelas yang tersedia saat ini.</p>
        </div>
        @endforelse

    </div>
</div>

