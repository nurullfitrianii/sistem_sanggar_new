@extends('layouts.admin')

@section('title', 'Rekap Jadwal Absensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Rekap Jadwal untuk Absensi</h1>
    <a href="{{ route('absensi.create') }}" class="btn btn-sm btn-outline-success">Input Absensi</a>
</div>

<div class="card section-card">
    <div class="card-body">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Pelatih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $item)
                    {{-- Ganti bagian ini di dalam @forelse --}}
                    <tr>
                        <td>{{ $item->programKelas->nama_program ?? '-' }}</td>
                        <td>{{ ucfirst($item->hari) }}</td>
                        <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                        <td>{{ $item->pelatih->nama_pelatih ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada jadwal yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

