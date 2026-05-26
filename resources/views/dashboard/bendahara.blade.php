@extends('layouts.admin')

@section('title', 'Dashboard Bendahara')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center text-md-start">
        <h2 class="fw-bold text-dark">Dashboard Bendahara</h2>
        <p class="text-muted">Pantau kesehatan finansial dan arus kas Sanggar secara real-time.</p>
    </div>
</div>

{{-- Alert Success/Error --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
@endif

{{-- Statistik Card --}}
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card p-4 h-100 section-card" style="border-left: 5px solid #3B82F6 !important; border-radius: 16px;">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="text-muted mb-0 small fw-bold text-uppercase tracking-wider">Saldo Kas</h6>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #E0E7FF; width: 40px; height: 40px;">
                    <i class="bi bi-wallet2" style="color: #4F46E5;"></i>
                </div>
            </div>
            <h3 class="fw-bold text-dark mb-1">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</h3>
            <div class="text-muted small">Total dana tersedia</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 h-100 section-card" style="border-left: 5px solid #10B981 !important; border-radius: 16px;">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="text-muted mb-0 small fw-bold text-uppercase tracking-wider">Pemasukan</h6>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D1FAE5; width: 40px; height: 40px;">
                    <i class="bi bi-arrow-up-right" style="color: #059669;"></i>
                </div>
            </div>
            <h3 class="fw-bold text-dark mb-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            <div class="text-success small fw-bold">Akumulasi pemasukan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 h-100 section-card" style="border-left: 5px solid #EF4444 !important; border-radius: 16px;">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="text-muted mb-0 small fw-bold text-uppercase tracking-wider">Pengeluaran</h6>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #FEE2E2; width: 40px; height: 40px;">
                    <i class="bi bi-arrow-down-right" style="color: #DC2626;"></i>
                </div>
            </div>
            <h3 class="fw-bold text-dark mb-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            <div class="text-muted small">Total pengeluaran tercatat</div>
        </div>
    </div>
</div>

{{-- Grafik Tren --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="card p-4 section-card" style="border-radius: 16px;">
            <div class="mb-4">
                <h6 class="fw-bold mb-0 text-dark">Tren Keuangan</h6>
                <p class="text-muted small mb-0">Perbandingan arus kas 6 bulan terakhir</p>
            </div>
            <div class="card-body p-0" style="position: relative; height: 350px; width: 100%;">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Quick Verification Section --}}
<div class="row mb-5">
    <div class="col-12">
        <h5 class="fw-bold text-dark mb-3">Quick Verification</h5>

        <ul class="nav nav-pills mb-3 gap-2" id="verificationTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill" id="mingguan-tab" data-bs-toggle="tab" data-bs-target="#mingguan" type="button" role="tab">Iuran Mingguan ({{ $iuranMingguanPending->count() }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill" id="bulanan-tab" data-bs-toggle="tab" data-bs-target="#bulanan" type="button" role="tab">Iuran Bulanan ({{ $iuranBulananPending->count() }})</button>
            </li>
        </ul>

        <div class="tab-content" id="verificationTabsContent">
            {{-- Tab Mingguan --}}
            <div class="tab-pane fade show active" id="mingguan" role="tabpanel">
                <div class="card rounded-4 section-card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4 py-3 border-0">Siswa</th>
                                        <th class="py-3 border-0">Tipe</th>
                                        <th class="py-3 border-0">Jumlah</th>
                                        <th class="py-3 border-0">Tanggal</th>
                                        <th class="pe-4 py-3 border-0 text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($iuranMingguanPending as $iuran)
                                    <tr class="border-bottom">
                                        <td class="ps-4 text-dark">{{ $iuran->user->username ?? 'Unknown' }}</td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ ucfirst($iuran->tipe_iuran) }}</span></td>
                                        <td class="">Rp {{ number_format($iuran->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td class="text-muted small">{{ $iuran->created_at->format('d M Y') }}</td>
                                        <td class="pe-4 text-end">
                                            <span class="badge bg-success px-3 py-2">Valid</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted small">Belum ada iuran mingguan yang valid.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Bulanan --}}
            <div class="tab-pane fade" id="bulanan" role="tabpanel">
                <div class="card rounded-4 section-card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4 py-3 border-0">Siswa</th>
                                        <th class="py-3 border-0">Tipe</th>
                                        <th class="py-3 border-0">Jumlah</th>
                                        <th class="py-3 border-0">Tanggal Pembayaran</th>
                                        <th class="pe-4 py-3 border-0 text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($iuranBulananPending as $iuran)
                                    <tr class="border-bottom">
                                        <td class="ps-4 text-dark">{{ $iuran->user->username ?? 'Unknown' }}</td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ ucfirst($iuran->tipe_iuran) }}</span></td>
                                        <td class="">Rp {{ number_format($iuran->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td class="text-muted small">{{ $iuran->created_at->format('d M Y') }}</td>
                                        <td class="pe-4 text-end">
                                            <span class="badge bg-success px-3 py-2">Valid</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted small">Belum ada iuran bulanan yang valid.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Transaksi Terakhir --}}
<div class="row">
    <div class="col-md-12">
        <div class="card overflow-hidden section-card" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Aktivitas Keuangan Terbaru</h6>
                        <p class="text-muted small mb-0 mt-1">Menampilkan 5 transaksi terakhir</p>
                    </div>
                    <a href="{{ route('keuangan-bendahara.index') }}" class="btn btn-sm rounded-pill px-3 btn-outline-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-bold text-uppercase">Tanggal</th>
                                <th class="py-3 text-muted small fw-bold text-uppercase">Pembayar/User</th>
                                <th class="py-3 text-muted small fw-bold text-uppercase">Kategori</th>
                                <th class="py-3 text-muted small fw-bold text-uppercase">Keterangan</th>
                                <th class="pe-4 py-3 text-end text-muted small fw-bold text-uppercase">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanTerbaru as $trx)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y') }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $trx->nama_pembayar }}</div>
                                    <div class="small text-muted">{{ $trx->tipe_pembayar }}</div>
                                </td>
                                <td>
                                    @if($trx->jenis === 'Masuk')
                                        <span class="badge bg-soft-success text-success px-3 rounded-pill" style="background-color: rgba(25, 135, 84, 0.1);">Pemasukan</span>
                                    @else
                                        <span class="badge bg-soft-danger text-danger px-3 rounded-pill" style="background-color: rgba(220, 53, 69, 0.1);">Pengeluaran</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit($trx->keterangan, 50) }}
                                </td>
                                <td class="pe-4 text-end {{ $trx->jenis === 'Masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $trx->jenis === 'Masuk' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Pemasukan',
                    data: {!! json_encode($chartData['pemasukan']) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#10B981'
                },
                {
                    label: 'Pengeluaran',
                    data: {!! json_encode($chartData['pengeluaran']) !!},
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#EF4444'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'bottom' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endsection
