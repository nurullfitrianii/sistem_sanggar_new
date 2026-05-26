@extends('layouts.admin')

@section('title', 'Riwayat Absensi Saya')

@section('content')
<h1 class="h4 mb-3">Riwayat Absensi</h1>

<div class="card section-card">
    <div class="card-body">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jam Absen</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatAbsensi as $absen)
                    <tr>
                        @php
                            $waktuHadir = \Carbon\Carbon::parse($absen->waktu_hadir);
                            $jamMulai = $absen->jadwalLatihan ? \Carbon\Carbon::parse($absen->jadwalLatihan->jam_mulai) : null;
                            $isLate = false;
                            
                            if ($jamMulai && strtolower($absen->status) == 'hadir') {
                                if ($waktuHadir->format('H:i:s') > $jamMulai->format('H:i:s')) {
                                    $isLate = true;
                                }
                            }
                        @endphp
                        <td>{{ $waktuHadir->translatedFormat('d F Y') }}</td>
                        <td class="{{ $isLate ? 'text-danger' : '' }}">
                            {{ $waktuHadir->format('H:i') }} WIB
                        </td>
                        <td>{{ $absen->jadwalLatihan->programKelas->nama_program ?? '-' }}</td>
                        <td>
                            @if(strtolower($absen->status) == 'hadir')
                                <span class="badge bg-success">Hadir</span>
                                @if($isLate)
                                    <span class="badge bg-danger ms-1">Telat</span>
                                @endif
                            @elseif(strtolower($absen->status) == 'sakit' || strtolower($absen->status) == 'izin')
                                <span class="badge bg-warning text-dark">{{ ucfirst($absen->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($absen->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $absen->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $riwayatAbsensi->links() }}
        </div>
    </div>
</div>
@endsection

