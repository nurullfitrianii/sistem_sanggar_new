@extends('layouts.admin')

@section('title', 'Dashboard Humas')

@section('content')
<div class="row border-bottom border-light pb-3 mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-1">Dashboard Humas</h3>
        <p class="text-muted mb-0">Kelola berita, galeri, dan pendaftaran siswa baru.</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('program-admin.index') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #3B82F6 !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Total Program</h6>
                <h2 class="fw-extrabold text-dark mb-0">{{ $totalProgram }}</h2>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('galeri-admin.index') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #8B5CF6 !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Media Galeri</h6>
                <h2 class="fw-extrabold text-dark mb-0">{{ $totalGaleri }}</h2>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('pendaftaran.index') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #14B8A6 !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Pendaf. Baru</h6>
                <h2 class="fw-extrabold text-dark mb-0">{{ \App\Models\Pendaftaran::where('status', 'Menunggu')->count() }}</h2>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('berita-admin.index') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #10B981 !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Total Berita</h6>
                <h2 class="fw-extrabold text-dark mb-0">{{ $totalBerita }}</h2>
            </div>
        </a>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
@endpush


<div class="row mb-5">
    <div class="col-12">
        <div class="card p-0 overflow-hidden section-card">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 flex-column d-flex align-items-start">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">Berita Terkini</h6>
                    <a href="{{ route('berita-admin.create') }}" class="btn btn-sm btn-outline-success" style="border-radius: 6px">Tulis Berita</a>
                </div>
                <p class="text-muted small mb-0 mt-1">Daftar publikasi sanggar</p>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($beritaTerbaru as $berita)
                    <div class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded-3 p-2 text-center" style="min-width: 60px;">
                                <div class="small fw-bold text-uppercase">{{ \Carbon\Carbon::parse($berita->tanggal_publish)->format('M') }}</div>
                                <div class="h5 mb-0 fw-bold text-primary">{{ \Carbon\Carbon::parse($berita->tanggal_publish)->format('d') }}</div>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $berita->judul }}</h6>
                                <p class="mb-0 text-muted small">Dipublish oleh: Admin</p>
                            </div>
                        </div>
                        <a href="{{ route('berita-admin.edit', $berita->id_informasi) }}" class="btn btn-sm rounded-pill px-3 btn-outline-warning">Edit</a>
                    </div>
                    @empty
                    <div class="p-5 text-center text-muted">Belum ada rilis berita terbaru.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
