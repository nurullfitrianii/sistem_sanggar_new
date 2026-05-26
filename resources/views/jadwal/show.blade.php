@extends('layouts.admin')

@section('title', 'Detail Jadwal & QR Code')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card section-card">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0">QR Code Absensi</h4>
            </div>
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <h5 class="fw-bold">{{ $jadwal->programKelas->nama_program ?? 'Program' }}</h5>
                    <p class="text-muted mb-1">
                        <strong>Hari:</strong> {{ ucfirst($jadwal->hari) }}<br>
                        <strong>Pukul:</strong> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}<br>
                        <strong>Sanggar:</strong> {{ $jadwal->sanggar->nama_sanggar ?? '-' }}
                    </p>
                </div>
                
                <div class="p-3 bg-light d-inline-block border rounded shadow-sm">
                    {!! $qrCode !!}
                </div>
                
                <div class="mt-4">
                    <p class="text-muted small">Tunjukkan QR code ini kepada siswa untuk di-scan lewat kamera perangkat mereka.</p>
                </div>
            </div>
            <div class="card-footer text-center py-3">
                <a href="{{ route('jadwal.index') }}" class="btn btn-outline-primary">Kembali ke Daftar Jadwal</a>
            </div>
        </div>
    </div>
</div>
@endsection
