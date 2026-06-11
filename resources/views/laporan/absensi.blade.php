@extends('layouts.admin')

@section('title', 'Laporan Kehadiran')

@section('content')
<div class="container-fluid">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Laporan Kehadiran</h4>
            <p class="text-muted small mb-0">Kelola dan unduh rekapitulasi kehadiran siswa sanggar.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-funnel-fill me-2"></i>Filter Laporan</h6>
            <form method="GET" action="{{ route('laporan.absensi') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control rounded-3">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Status Kehadiran</label>
                    <select name="status" class="form-select rounded-3">
                        <option value="">Semua Status</option>
                        <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Alfa" {{ request('status') == 'Alfa' ? 'selected' : '' }}>Alfa</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Program Kelas</label>
                    <select name="id_program" class="form-select rounded-3">
                        <option value="">Semua Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id_program }}" {{ request('id_program') == $program->id_program ? 'selected' : '' }}>
                                {{ $program->nama_program }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 d-flex align-items-center justify-content-center gap-2" style="background-color: var(--sanggar-primary); border-color: var(--sanggar-primary);">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('laporan.absensi') }}" class="btn btn-outline-secondary rounded-3" title="Reset Filter">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <h6 class="fw-bold mb-0 text-dark">Data Kehadiran Siswa</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('laporan.absensi.excel', request()->query()) }}" class="btn btn-success rounded-3 px-3 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-excel-fill"></i> Cetak Excel
                    </a>
                    <a href="{{ route('laporan.absensi.pdf', request()->query()) }}" class="btn btn-danger rounded-3 px-3 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">Tanggal</th>
                            <th class="py-3">Jam Absen</th>
                            <th class="py-3">Nama Lengkap</th>
                            <th class="py-3">Username</th>
                            <th class="py-3">Program Kelas</th>
                            <th class="py-3" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $a)
                            <tr>
                                @php
                                    $waktuHadir = \Carbon\Carbon::parse($a->waktu_hadir);
                                    $jadwal = $a->getJadwal();
                                    $jamMulai = $jadwal ? \Carbon\Carbon::parse($jadwal->jam_mulai) : null;
                                    $isLate = false;
                                    
                                    if ($jamMulai && strtolower($a->status) == 'hadir') {
                                        if ($waktuHadir->format('H:i:s') > $jamMulai->copy()->addMinutes(10)->format('H:i:s')) {
                                            $isLate = true;
                                        }
                                    }
                                @endphp
                                <td>{{ $waktuHadir->format('d/m/Y') }}</td>
                                <td class="{{ $isLate ? 'text-danger fw-bold' : 'text-primary' }}">
                                    {{ $waktuHadir->format('H:i') }} WIB
                                </td>
                                <td class="fw-semibold">{{ $a->user->nama_lengkap ?? $a->user->pendaftaran->nama_calon ?? $a->user->username ?? '-' }}</td>
                                <td class="text-muted">{{ $a->user->username ?? '-' }}</td>
                                <td>{{ $jadwal->programKelas->nama_program ?? $a->user->pendaftaran->programKelas->nama_program ?? '-' }}</td>
                                <td>
                                    @if(strtolower($a->status) == 'hadir')
                                        <span class="badge bg-success px-3 py-2 rounded-pill">Hadir</span>
                                        @if($isLate)
                                            <span class="badge bg-danger px-2 py-2 rounded-pill ms-1">Telat</span>
                                        @endif
                                    @elseif(strtolower($a->status) == 'sakit')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Sakit</span>
                                    @elseif(strtolower($a->status) == 'izin')
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Izin</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2 rounded-pill">{{ ucfirst($a->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data absensi yang sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($absensi->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $absensi->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
