@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3 fw-bold text-dark">Laporan Keuangan</h1>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted">Cari Periode / Tanggal</label>
            <input type="month" name="periode" value="{{ request('periode') }}" class="form-control border-0 shadow-sm">
        </div>
        <div class="col-md-8 d-flex align-items-end justify-content-between">
            <button class="btn px-4 shadow-sm btn-outline-primary">
                Terapkan Filter
            </button>

            <div class="export-actions d-flex gap-2">
                @if(auth()->user()->role === 'Bendahara')
                    <a href="{{ route('laporan.keuangan.bendahara.export', request()->all()) }}" class="btn btn-success rounded-3 px-3 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-excel-fill"></i> Cetak Excel
                    </a>
                    <a href="{{ route('laporan.keuangan.bendahara.pdf', request()->all()) }}" class="btn btn-danger rounded-3 px-3 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                    </a>
                @else
                    <a href="{{ route('laporan.keuangan.export', request()->all()) }}" class="btn btn-success rounded-3 px-3 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-excel-fill"></i> Cetak Excel
                    </a>
                    <a href="{{ route('laporan.keuangan.pdf', request()->all()) }}" class="btn btn-danger rounded-3 px-3 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="card mb-4 rounded-4 section-card">
        <div class="card-body py-4">
            <div class="row align-items-center">
                <div class="col-md-4 text-center border-end">
                    <p class="text-muted small mb-1 text-uppercase fw-bold">Total Pemasukan</p>
                    <h3 class="mb-0 text-success fw-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                </div>
                <div class="col-md-4 text-center border-end">
                    <p class="text-muted small mb-1 text-uppercase fw-bold">Total Pengeluaran</p>
                    <h3 class="mb-0 text-danger fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
                <div class="col-md-4 text-center">
                    <p class="text-muted small mb-1 text-uppercase fw-bold">Saldo Bersih</p>
                    <h3 class="mb-0 {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'text-primary' : 'text-danger' }} fw-bold">
                        Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-4 overflow-hidden section-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th class="py-3">Jenis Transaksi</th>
                            <th class="py-3">Nominal</th>
                            <th class="pe-4 py-3 text-end">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $t)
                            <tr>
                                <td class="ps-4 fw-medium">{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d F Y') }}</td>
                                <td>
                                    @if($t->jenis === 'Masuk')
                                        <span class="badge bg-soft-success text-success px-3 rounded-pill border">Pemasukan</span>
                                    @else
                                        <span class="badge bg-soft-danger text-danger px-3 rounded-pill border">Pengeluaran</span>
                                    @endif
                                </td>
                                <td class="{{ $t->jenis === 'Masuk' ? 'text-success' : 'text-danger' }} fw-bold text-nowrap">
                                    {{ $t->jenis === 'Masuk' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                                </td>
                                <td class="pe-4 text-end small text-muted">{{ $t->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    Belum ada data transaksi keuangan untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); border-color: rgba(25, 135, 84, 0.2) !important; }
    .bg-soft-danger { background-color: rgba(220, 53, 69, 0.1); border-color: rgba(220, 53, 69, 0.2) !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .table thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #6c757d;
    }
</style>
@endsection
