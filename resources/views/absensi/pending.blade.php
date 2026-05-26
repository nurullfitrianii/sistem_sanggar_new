@extends('layouts.admin')

@section('title', 'Persetujuan Absensi')

@section('content')
<div class="row border-bottom border-light pb-3 mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-1">Persetujuan Absensi</h3>
        <p class="text-muted mb-0">Ketua dapat memberikan konfirmasi pada absensi yang berstatus "Pending"</p>
    </div>
</div>

<div class="card section-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Siswa</th>
                    <th>Jadwal / Kelas</th>
                    <th>Waktu Scan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $p)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $p->user->username }}</div>
                        <small class="text-muted">ID: {{ $p->id_user }}</small>
                    </td>
                    <td>
                        <div class="text-dark">{{ $p->jadwalLatihan->hari }}, {{ $p->jadwalLatihan->jam_mulai }}</div>
                        <span class="badge bg-soft-primary text-primary small">{{ $p->jadwalLatihan->programKelas->nama_program }}</span>
                    </td>
                    <td>
                        <div class="small text-muted">{{ \Carbon\Carbon::parse($p->waktu_hadir)->translatedFormat('d F Y') }}</div>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($p->waktu_hadir)->translatedFormat('H:i') }} WIB</div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <form action="{{ route('absensi.approve', $p->id_absensi) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm rounded-pill px-3 btn-outline-primary">Konfirmasi</button>
                            </form>
                            <form action="{{ route('absensi.reject', $p->id_absensi) }}" method="POST" onsubmit="return confirm('Tolak absensi ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm rounded-pill px-3 btn-outline-danger">Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">Tidak ada absensi yang menunggu persetujuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
