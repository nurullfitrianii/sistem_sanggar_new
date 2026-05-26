@extends('layouts.admin')

@section('title', 'Laporan Siswa')

@section('content')
<h1 class="h4 mb-3">Laporan Siswa Aktif &amp; Nonaktif</h1>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card section-card">
            <div class="card-body">
                <h2 class="h6 mb-3">Siswa Aktif</h2>
                <ul class="list-unstyled small mb-0">
                    @forelse($siswaAktif as $a)
                        <li class="mb-1 fw-bold text-success">{{ $a->username }}</li>
                    @empty
                        <li class="text-muted small">Belum ada siswa aktif.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card section-card">
            <div class="card-body">
                <h2 class="h6 mb-3">Siswa Nonaktif</h2>
                <ul class="list-unstyled small mb-0">
                    @forelse($siswaNonaktif as $a)
                        <li class="mb-1 text-muted">{{ $a->username }}</li>
                    @empty
                        <li class="text-muted small">Belum ada siswa nonaktif.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

