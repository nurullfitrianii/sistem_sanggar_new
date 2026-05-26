@extends('layouts.admin') {{-- INI BIAR HEADER BALIK --}}

@section('title', 'Laporan Kehadiran')

@section('content')
<div class="container-fluid">
    <div class="card section-card" style="border-radius: 20px;">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Laporan Kehadiran</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Absen</th>
                            <th>Nama Siswa</th>
                            <th>Program</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporan as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->waktu_hadir)->format('d/m/Y') }}</td>
                            <td class="text-primary">{{ \Carbon\Carbon::parse($item->waktu_hadir)->format('H:i') }} WIB</td>
                            <td class="">{{ $item->user->nama_lengkap ?? $item->user->username ?? '-' }}</td>
                            <td>{{ $item->jadwalLatihan->programKelas->nama_program ?? '-' }}</td>
                            <td><span class="badge bg-success">{{ $item->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
