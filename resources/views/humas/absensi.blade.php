@extends('layouts.admin')

@section('title', 'Rekap Absensi')

@section('content')
<div class="row border-bottom border-light pb-3 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-dark mb-1">Rekap Absensi</h3>
            <p class="text-muted mb-0">Data kehadiran Siswa dari scan QR maupun absensi manual.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn d-flex align-items-center gap-2 btn-outline-primary"
                data-bs-toggle="modal" data-bs-target="#modalAbsensiManual"
                style="border-radius: 8px; padding: 10px 18px">
                Absensi Manual
            </button>
            <a href="{{ route('absensi.scan_view') }}" class="btn d-flex align-items-center gap-2 btn-outline-success"
               style="border-radius: 8px; padding: 10px 18px">
                Scan QR
            </a>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card p-3 h-100 section-card" style="border-left: 4px solid #10B981 !important;">
            <p class="text-muted small fw-bold mb-1 text-uppercase">Hadir</p>
            <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalHadir) }}</h3>
            <p class="text-muted small mb-0">Total keseluruhan</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 h-100 section-card" style="border-left: 4px solid #F59E0B !important;">
            <p class="text-muted small fw-bold mb-1 text-uppercase">Izin / Sakit</p>
            <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalIzin) }}</h3>
            <p class="text-muted small mb-0">Total keseluruhan</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 h-100 section-card" style="border-left: 4px solid #EF4444 !important;">
            <p class="text-muted small fw-bold mb-1 text-uppercase">Alfa</p>
            <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalAlfa) }}</h3>
            <p class="text-muted small mb-0">Total keseluruhan</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 h-100 section-card" style="border-left: 4px solid #3B82F6 !important;">
            <p class="text-muted small fw-bold mb-1 text-uppercase">Absen Hari Ini</p>
            <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalHariIni) }}</h3>
            <p class="text-muted small mb-0">{{ \Carbon\Carbon::today()->isoFormat('D MMMM Y') }}</p>
        </div>
    </div>
</div>

{{-- FILTER OTOMATIS (tanpa tombol submit) --}}
<div class="card mb-4 p-3 section-card">
    <form method="GET" action="{{ route('humas.absensi.index') }}" id="formFilter" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Tanggal</label>
            <input type="date" name="tanggal" id="filterTanggal"
                   value="{{ request('tanggal') }}" class="form-control auto-filter">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted">Program / Kelas</label>
            <select name="id_program" id="filterProgram" class="form-select auto-filter">
                <option value="">-- Semua Program --</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id_program }}"
                        {{ request('id_program') == $program->id_program ? 'selected' : '' }}>
                        {{ $program->nama_program }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Status</label>
            <select name="status" id="filterStatus" class="form-select auto-filter">
                <option value="">-- Semua Status --</option>
                <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="Izin"  {{ request('status') == 'Izin'  ? 'selected' : '' }}>Izin</option>
                <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="Alfa"  {{ request('status') == 'Alfa'  ? 'selected' : '' }}>Alfa</option>
            </select>
        </div>
    </form>
</div>

{{-- TABEL ABSENSI --}}
<div class="card p-0 overflow-hidden section-card">
    <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-bold mb-0 text-dark">Data Kehadiran Siswa</h6>
            <p class="text-muted small mb-0">
                Menampilkan {{ $absensi->firstItem() ?? 0 }}–{{ $absensi->lastItem() ?? 0 }}
                dari {{ $absensi->total() }} data
                @if(request('tanggal')) &mdash; {{ \Carbon\Carbon::parse(request('tanggal'))->isoFormat('D MMMM Y') }} @endif
            </p>
        </div>
    </div>

    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-4 mt-3 mb-0" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" >
                <thead>
                    <tr>
                        <th class="px-4 py-3 fw-bold text-muted" style="width: 40px;">#</th>
                        <th class="py-3 fw-bold text-muted">Nama Siswa</th>
                        <th class="py-3 fw-bold text-muted">Username</th>
                        <th class="py-3 fw-bold text-muted">Tanggal & Jam</th>
                        <th class="py-3 fw-bold text-muted">Program / Kelas</th>
                        <th class="py-3 fw-bold text-muted">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $index => $a)
                        @php
                            $waktuHadir = \Carbon\Carbon::parse($a->waktu_hadir)->timezone('Asia/Jakarta');
                            $jadwal     = $a->getJadwal();
                            $jamMulai   = $jadwal ? \Carbon\Carbon::parse($jadwal->jam_mulai) : null;
                            $isLate     = $jamMulai && strtolower($a->status) === 'hadir'
                                          && $waktuHadir->format('H:i:s') > $jamMulai->copy()->addMinutes(10)->format('H:i:s');
                            $namaCalon  = $a->user->pendaftaran->nama_calon ?? ($a->user->username ?? '-');
                        @endphp
                        <tr>
                            <td class="px-4 text-muted">{{ $absensi->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold">{{ $namaCalon }}</div>
                            </td>
                            <td class="text-muted">{{ $a->user->username ?? '-' }}</td>
                            <td>
                                <div class="fw-bold">{{ $waktuHadir->format('d M Y') }}</div>
                                <div class="small {{ $isLate ? 'text-danger fw-bold' : 'text-muted' }}">
                                    {{ $waktuHadir->format('H:i') }} WIB
                                    @if($isLate)
                                        <span class="badge bg-danger ms-1" style="font-size: 0.65rem;">Telat</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $jadwal->programKelas->nama_program ?? $a->user->pendaftaran->programKelas->nama_program ?? '-' }}</td>
                            <td>
                                @if(strtolower($a->status) === 'hadir')
                                    <span class="badge rounded-pill bg-success px-3 py-2">Hadir</span>
                                @elseif(strtolower($a->status) === 'izin')
                                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2">Izin</span>
                                @elseif(strtolower($a->status) === 'sakit')
                                    <span class="badge rounded-pill bg-info text-dark px-3 py-2">Sakit</span>
                                @elseif(strtolower($a->status) === 'alfa')
                                    <span class="badge rounded-pill bg-danger px-3 py-2">Alfa</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary px-3 py-2">{{ $a->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada data absensi
                                @if(request()->hasAny(['tanggal', 'status', 'id_program']))
                                    untuk filter yang dipilih.
                                    <br>
                                    <a href="{{ route('humas.absensi.index') }}" class="btn btn-sm mt-2 btn-outline-primary">
                                        Lihat semua data
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($absensi->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $absensi->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL ABSENSI MANUAL --}}
<div class="modal fade" id="modalAbsensiManual" tabindex="-1" aria-labelledby="modalAbsensiManualLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold" id="modalAbsensiManualLabel">Absensi Manual</h5>
                    <p class="text-muted small mb-0">Untuk Siswa yang lupa membawa QR Code.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('absensi.storeManual') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pt-3 pb-2">

                    {{-- Pilih Siswa --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Siswa</label>
                        <input type="hidden" name="id_user" id="inputIdUser" required>
                        <div class="position-relative">
                            <input type="text" id="searchSiswa" class="form-control"
                                placeholder="Ketik nama atau username siswa..."
                                autocomplete="off">
                            <div id="dropdownSiswa" class="position-absolute w-100 bg-white border rounded-3 shadow-sm d-none"
                                style="z-index: 9999; max-height: 220px; overflow-y: auto; top: 100%; left: 0;">
                            </div>
                        </div>
                        <div id="siswaSelected" class="mt-2 d-none">
                            <span class="badge bg-success px-3 py-2" id="siswaSelectedLabel"></span>
                            <button type="button" class="btn btn-sm text-danger p-0 ms-2 btn-outline-primary" id="clearSiswa">
                                Ganti
                            </button>
                        </div>
                    </div>

                    {{-- Pilih Jadwal --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Jadwal / Kelas</label>
                        <select name="id_jadwal" class="form-select" required>
                            <option value="" data-id-program="">-- Pilih Jadwal --</option>
                            @foreach($jadwalList as $jadwal)
                                <option value="{{ $jadwal->id_jadwal }}" data-id-program="{{ $jadwal->id_program }}">
                                    {{ $jadwal->programKelas->nama_program ?? '-' }}
                                    — {{ $jadwal->hari }},
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Status Kehadiran</label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach(['Hadir', 'Izin', 'Sakit', 'Alfa'] as $s)
                                @php
                                    $colors = ['Hadir' => 'success', 'Izin' => 'warning', 'Sakit' => 'info', 'Alfa' => 'danger'];
                                @endphp
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input" type="radio" name="status"
                                           id="status_{{ $s }}" value="{{ $s }}"
                                           {{ $s === 'Hadir' ? 'checked' : '' }}>
                                    <label class="form-check-label badge bg-{{ $colors[$s] }} px-3 py-2 fw-normal"
                                           for="status_{{ $s }}" style="cursor:pointer; font-size:0.85rem;">
                                        {{ $s }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-1">
                        <label class="form-label fw-bold small text-muted">Keterangan <span class="fw-normal text-muted">(opsional)</span></label>
                        <textarea name="keterangan" class="form-control" rows="2"
                                  placeholder="Contoh: Sakit demam, ada keperluan keluarga..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" data-bs-dismiss="modal" title="Batal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <button type="submit" class="btn px-4 btn-outline-success" style="border-radius: 8px">
                        Simpan Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card-outline { border: 1px solid #e5e7eb; border-radius: 10px; }
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
</style>

<script>
// Auto-filter logic
document.querySelectorAll('.auto-filter').forEach(element => {
    element.addEventListener('change', () => {
        document.getElementById('formFilter').submit();
    });
});

const siswaData = @json($siswaListJson);
const searchInput   = document.getElementById('searchSiswa');
const dropdown      = document.getElementById('dropdownSiswa');
const inputIdUser   = document.getElementById('inputIdUser');
const siswaSelected = document.getElementById('siswaSelected');
const selectedLabel = document.getElementById('siswaSelectedLabel');
const clearBtn      = document.getElementById('clearSiswa');

// Elements for schedule filtering
const selectJadwal = document.querySelector('select[name="id_jadwal"]');
const originalJadwalOptions = selectJadwal ? Array.from(selectJadwal.options) : [];

function filterJadwal(idProgram) {
    if (!selectJadwal || originalJadwalOptions.length === 0) return;
    
    selectJadwal.innerHTML = '';
    // Append default option
    selectJadwal.appendChild(originalJadwalOptions[0]);
    
    // Filter options matching program ID
    const filteredOptions = originalJadwalOptions.slice(1).filter(opt => {
        return opt.getAttribute('data-id-program') == idProgram;
    });
    
    filteredOptions.forEach(opt => selectJadwal.appendChild(opt));
    
    // Auto-select if there is exactly 1 option matching
    if (filteredOptions.length === 1) {
        selectJadwal.value = filteredOptions[0].value;
    } else {
        selectJadwal.value = '';
    }
}

function resetJadwal() {
    if (!selectJadwal || originalJadwalOptions.length === 0) return;
    selectJadwal.innerHTML = '';
    originalJadwalOptions.forEach(opt => selectJadwal.appendChild(opt));
    selectJadwal.value = '';
}

searchInput.addEventListener('input', function () {
    const keyword = this.value.toLowerCase().trim();
    dropdown.innerHTML = '';

    if (!keyword) {
        dropdown.classList.add('d-none');
        return;
    }

    const filtered = siswaData.filter(s =>
        s.nama.toLowerCase().includes(keyword) ||
        s.username.toLowerCase().includes(keyword)
    );

    if (filtered.length === 0) {
        dropdown.innerHTML = '<div class="px-3 py-2 text-muted small">Siswa tidak ditemukan</div>';
        dropdown.classList.remove('d-none');
        return;
    }

    filtered.forEach(s => {
        const item = document.createElement('div');
        item.className = 'px-3 py-2 cursor-pointer';
        item.style.cssText = 'cursor:pointer; font-size:0.875rem;';
        item.innerHTML = `<span class="fw-bold">${s.nama}</span> <span class="text-muted">(${s.username})</span>`;
        item.addEventListener('mouseenter', () => item.style.backgroundColor = '#FFF7ED');
        item.addEventListener('mouseleave', () => item.style.backgroundColor = '');
        item.addEventListener('click', () => {
            inputIdUser.value = s.id;
            searchInput.value = '';
            dropdown.classList.add('d-none');
            selectedLabel.textContent = `${s.nama} (${s.username})`;
            siswaSelected.classList.remove('d-none');
            searchInput.classList.add('d-none');
            
            // Filter schedule dynamically based on student's program
            if (s.id_program) {
                filterJadwal(s.id_program);
            } else {
                resetJadwal();
            }
        });
        dropdown.appendChild(item);
    });

    dropdown.classList.remove('d-none');
});

clearBtn.addEventListener('click', () => {
    inputIdUser.value = '';
    siswaSelected.classList.add('d-none');
    searchInput.classList.remove('d-none');
    searchInput.value = '';
    searchInput.focus();
    
    // Reset schedule select options
    resetJadwal();
});

document.addEventListener('click', function (e) {
    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('d-none');
    }
});
</script>
@endsection
