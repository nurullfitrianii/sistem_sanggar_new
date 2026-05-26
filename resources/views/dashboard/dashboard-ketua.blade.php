@extends('layouts.admin')

@section('title', 'Dashboard Ketua')

@section('content')
<div class="row border-bottom border-light pb-3 mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-1">Dashboard Ketua</h3>
        <p class="text-muted mb-0">Pantau seluruh data Sanggar Goong Prasasti secara real-time.</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-md-4 col-lg">
        <a href="{{ route('siswa-admin.index') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #3B82F6 !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Siswa Aktif</h6>
                <h2 class="fw-extrabold text-dark mb-0">{{ $totalSiswaAktif }}</h2>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg">
        <a href="{{ route('program-admin.index') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #8B5CF6 !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Program Kelas</h6>
                <h2 class="fw-extrabold text-dark mb-0">{{ $totalProgram }}</h2>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg">
        <a href="{{ route('siswa-admin.index') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #14B8A6 !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Pelatih</h6>
                <h2 class="fw-extrabold text-dark mb-0">{{ $totalPelatih }}</h2>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg">
        <a href="{{ route('laporan.keuangan') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #10B981 !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Pemasukan</h6>
                <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h5>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg">
        <a href="{{ route('siswa-admin.index') }}" class="text-decoration-none h-100">
            <div class="card p-3 h-100 hover-lift d-flex flex-column justify-content-center section-card" style="border-left: 4px solid #F59E0B !important;">
                <h6 class="text-muted small fw-bold uppercase mb-2">Prestasi</h6>
                <h2 class="fw-extrabold text-dark mb-0">{{ $totalPrestasi }}</h2>
            </div>
        </a>
    </div>
</div>

<div class="row mb-5 g-4">
    <div class="col-lg-7">
        <div class="card p-4 h-100 section-card" style="border-radius: 16px;">
            <div class="mb-4">
                <h6 class="fw-bold mb-0 text-dark">Tren Keuangan</h6>
                <p class="text-muted small mb-0">Perbandingan arus kas 6 bulan terakhir</p>
            </div>
            <div class="card-body p-0" style="position: relative; height: 300px; width: 100%;">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-4 h-100 section-card" style="border-radius: 16px;">
            <div class="mb-4">
                <h6 class="fw-bold mb-0 text-dark">Kehadiran Siswa</h6>
                <p class="text-muted small mb-0">Tren kehadiran 7 hari terakhir</p>
            </div>
            <div class="card-body p-0 d-flex align-items-center" style="position: relative; height: 300px; width: 100%;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <div class="card p-0 overflow-hidden section-card">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 flex-column d-flex align-items-start">
                <h6 class="fw-bold mb-0 text-dark">Prestasi Terbaru</h6>
                <p class="text-muted small mb-0 mt-1">Daftar penghargaan sanggar</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Event</th>
                                <th>Tahun</th>
                                <th>Tingkat</th>
                                <th class="pe-4">Sanggar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prestasiTerbaru as $prestasi)
                            <tr>
                                <td class="ps-4 text-dark">{{ $prestasi->nama_event }}</td>
                                <td>{{ $prestasi->tahun }}</td>
                                <td><span class="badge bg-soft-warning text-warning px-3 rounded-pill">{{ $prestasi->tingkat }}</span></td>
                                <td class="pe-4 text-muted">{{ $prestasi->sanggar->nama_sanggar ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada data prestasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <div class="card px-4 py-4 mb-4 section-card">
            <div class="d-flex align-items-start gap-4">
                <div class="bg-soft-primary rounded-circle d-flex p-3 justify-content-center align-items-center" style="width: 60px; height: 60px; background-color: #FEF3C7;">
                    <i class="bi bi-calendar3 fs-3" style="color: #92400E;"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-2">Manajemen Jadwal Latihan</h6>
                    <p class="text-muted small mb-3">Pantau dan kelola jadwal latihan rutin sanggar untuk memastikan aktivitas pelatih dan siswa berjalan secara efisien dan terstruktur.</p>
                    <a href="{{ route('jadwal.index') }}" class="btn px-4 py-2 btn-outline-warning" style="border-radius: 8px">Kelola Seluruh Jadwal</a>
                </div>
            </div>
        </div>
        
        <div class="card p-4 section-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold text-dark mb-0">Statistik Pendaftaran</h6>
                <span class="small fw-bold text-primary">70%</span>
            </div>
            <p class="text-muted small mb-3">Target pendaftaran tahun ini hampir tercapai.</p>
            <div class="progress" style="height: 8px; border-radius: 4px;">
                <div class="progress-bar bg-primary" style="width: 70%;"></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Financial Chart
    const ctxCash = document.getElementById('cashFlowChart').getContext('2d');
    const gradientPemasukan = ctxCash.createLinearGradient(0, 0, 0, 300);
    gradientPemasukan.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
    gradientPemasukan.addColorStop(1, 'rgba(16, 185, 129, 0)');

    const gradientPengeluaran = ctxCash.createLinearGradient(0, 0, 0, 300);
    gradientPengeluaran.addColorStop(0, 'rgba(239, 68, 68, 0.1)');
    gradientPengeluaran.addColorStop(1, 'rgba(239, 68, 68, 0)');

    new Chart(ctxCash, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Pemasukan',
                    data: {!! json_encode($chartData['pemasukan']) !!},
                    borderColor: '#10B981',
                    backgroundColor: gradientPemasukan,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10B981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Pengeluaran',
                    data: {!! json_encode($chartData['pengeluaran']) !!},
                    borderColor: '#EF4444',
                    backgroundColor: gradientPengeluaran,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#EF4444',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: true
                }
            },
            scales: { 
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f3f4f6', drawBorder: false },
                    ticks: { 
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                }
            }
        }
    });

    // Attendance Chart
    const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
    const gradientAttendance = ctxAttendance.createLinearGradient(0, 0, 0, 400);
    gradientAttendance.addColorStop(0, '#A47251');
    gradientAttendance.addColorStop(1, '#DD9E59');

    new Chart(ctxAttendance, {
        type: 'bar',
        data: {
            labels: {!! json_encode($attendanceTrend['labels']) !!},
            datasets: [{
                label: 'Siswa Hadir',
                data: {!! json_encode($attendanceTrend['data']) !!},
                backgroundColor: gradientAttendance,
                hoverBackgroundColor: '#8B5E3C',
                borderRadius: 10,
                barThickness: 28,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: { family: 'Plus Jakarta Sans', weight: '700' }
                }
            },
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        stepSize: 1, 
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } 
                    },
                    grid: { color: '#f3f4f6', drawBorder: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });
</script>
@endpush
@endsection
