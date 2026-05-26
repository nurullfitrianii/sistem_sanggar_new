@extends('layouts.admin')

@section('title', 'Laporan Absensi')

@section('content')
<h1 class="h4 mb-3">Laporan Absensi</h1>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
    </div>
    <div class="col-md-6 d-flex align-items-end gap-2">
        <button class="btn btn-outline-primary">Terapkan Filter</button>
        <a href="{{ route('laporan.absensi.pdf', request()->query()) }}" class="btn btn-outline-primary">
            Cetak PDF
        </a>
    </div>
</form>

<div class="card section-card">
    <div class="card-body">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jam Absen</th>
                    <th>Nama Siswa</th>
                    <th>Username</th>
                    <th>Program Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $a)
                    <tr>
                        @php
                            $waktuHadir = \Carbon\Carbon::parse($a->waktu_hadir);
                            $jamMulai = $a->jadwalLatihan ? \Carbon\Carbon::parse($a->jadwalLatihan->jam_mulai) : null;
                            $isLate = false;
                            
                            if ($jamMulai && strtolower($a->status) == 'hadir') {
                                // Add 15 minutes tolerance if needed, or strict. User asked for exact strict logic.
                                if ($waktuHadir->format('H:i:s') > $jamMulai->format('H:i:s')) {
                                    $isLate = true;
                                }
                            }
                        @endphp
                        <td>{{ $waktuHadir->format('d M Y') }}</td>
                        <td class="{{ $isLate ? 'text-danger' : '' }}">
                            {{ $waktuHadir->format('H:i') }} WIB
                        </td>
                        <td class="">{{ $a->user->nama_lengkap ?? $a->user->username ?? '-' }}</td>
                        <td>{{ $a->user->username ?? '-' }}</td>
                        <td>{{ $a->jadwalLatihan->programKelas->nama_program ?? '-' }}</td>
                        <td>
                            @if(strtolower($a->status) == 'hadir')
                                <span class="badge bg-success">Hadir</span>
                                @if($isLate)
                                    <span class="badge bg-danger ms-1">Telat</span>
                                @endif
                            @elseif(strtolower($a->status) == 'sakit' || strtolower($a->status) == 'izin')
                                <span class="badge bg-warning text-dark">{{ ucfirst($a->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($a->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $absensi->links() }}
        </div>
    </div>
</div>
@endsection

