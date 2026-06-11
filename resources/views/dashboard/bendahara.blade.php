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
    <div class="col-lg-3 col-md-6">
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
    <div class="col-lg-3 col-md-6">
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
    <div class="col-lg-3 col-md-6">
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
    <div class="col-lg-3 col-md-6">
        <div class="card p-4 h-100 section-card" style="border-left: 5px solid #F59E0B !important; border-radius: 16px;">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="text-muted mb-0 small fw-bold text-uppercase tracking-wider">Total Tunggakan</h6>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #FEF3C7; width: 40px; height: 40px;">
                    <i class="bi bi-exclamation-triangle" style="color: #D97706;"></i>
                </div>
            </div>
            <h3 class="fw-bold text-dark mb-1">Rp {{ number_format($totalNominalTunggakan, 0, ',', '.') }}</h3>
            <div class="text-danger small fw-bold">
                <i class="bi bi-people-fill me-1"></i>{{ count($tunggakanList) }} Siswa Menunggak
            </div>
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

{{-- Section Data Tunggakan --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="card section-card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white pt-4 px-4 border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        Data Tunggakan Iuran Siswa
                        <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem; padding: 0.35em 0.65em;">
                            {{ count($tunggakanList) }} Siswa
                        </span>
                    </h6>
                    <p class="text-muted small mb-0">Daftar otomatis siswa aktif dengan iuran bulanan/mingguan yang belum lunas pada bulan berjalan dan sebelumnya.</p>
                </div>
                <div class="position-relative" style="max-width: 300px;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="tunggakanSearchInput" class="form-control rounded-pill ps-5 border-gray-200" placeholder="Cari nama atau kelas...">
                </div>
            </div>
            
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tunggakanTable">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0 ps-3">Siswa</th>
                                <th class="border-0">Program Kelas</th>
                                <th class="border-0">Rincian Bulan</th>
                                <th class="border-0">Total Tunggakan</th>
                                <th class="border-0 text-end pe-3">Aksi Penagihan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tunggakanList as $item)
                            <tr class="border-bottom">
                                <td class="ps-3 py-3">
                                    <div class="fw-bold text-dark">{{ $item->nama_lengkap }}</div>
                                    <div class="small text-muted">Username: {{ $item->username }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-xs">
                                        {{ $item->program }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($item->unpaid_months as $bulan)
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2 py-1 rounded-3 small">
                                                {{ $bulan['bulan'] }} 
                                                <span class="fw-normal">({{ $bulan['status'] }})</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-extrabold text-dark text-nowrap">Rp {{ number_format($item->total_arrears, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    @if($item->no_hp)
                                        @php
                                            $unpaidMonthsNames = collect($item->unpaid_months)
                                                ->pluck('bulan')
                                                ->map(function($b) {
                                                    $en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                                    $id = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                    return str_replace($en, $id, $b);
                                                })
                                                ->implode(', ');
                                            $message = "Assalamu'alaikum wr. wb.\n\nKami dari Sanggar Seni Goong Prasasti ingin menginformasikan bahwa saat ini masih terdapat tunggakan iuran latihan atas nama *" . $item->nama_lengkap . "* untuk bulan *" . $unpaidMonthsNames . "* sebesar *Rp " . number_format($item->total_arrears, 0, ',', '.') . "*.\n\nMohon untuk segera melakukan pembayaran. Pembayaran dapat dilakukan melalui menu pembayaran di profil siswa atau langsung transfer ke rekening sanggar. Apabila ada kendala atau jika pembayaran telah dilakukan, mohon hubungi kami untuk konfirmasi.\n\nTerima kasih atas perhatian dan kerja samanya.\n\nAdmin Sanggar Seni Goong Prasasti";
                                            
                                            $phone = preg_replace('/[^0-9]/', '', $item->no_hp);
                                            if (strpos($phone, '0') === 0) {
                                                $phone = '62' . substr($phone, 1);
                                            }
                                            $waLink = "https://wa.me/" . $phone . "?text=" . rawurlencode($message);
                                        @endphp
                                        <a href="{{ $waLink }}" target="_blank" class="btn btn-sm rounded-pill px-3 btn-outline-success d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-whatsapp"></i> Tagih via WA
                                        </a>
                                    @else
                                        <span class="text-muted small italic">No. HP tidak terdaftar</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted rounded-4 bg-light border-0">
                                    <div class="mb-3"><i class="bi bi-check-circle-fill text-success fs-1"></i></div>
                                    <h5 class="fw-bold mb-1">Semua Tagihan Lunas!</h5>
                                    <p class="mb-0 small text-muted">Hebat! Tidak ada siswa yang menunggak iuran bulan ini.</p>
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

<style>
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .border-gray-200 { border-color: #E5E7EB !important; }
    .btn-outline-success {
        border-color: #10B981 !important;
        color: #10B981 !important;
    }
    .btn-outline-success:hover {
        background-color: #10B981 !important;
        color: white !important;
    }
</style>

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

    // Real-time search filter for Data Tunggakan
    document.getElementById('tunggakanSearchInput').addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tunggakanTable tbody tr');
        
        rows.forEach(row => {
            // Skip the row if it's the "empty" message row (which has colspan="5")
            if (row.querySelector('td[colspan]')) return;
            
            const name = row.cells[0].textContent.toLowerCase();
            const program = row.cells[1].textContent.toLowerCase();
            
            if (name.includes(query) || program.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection
