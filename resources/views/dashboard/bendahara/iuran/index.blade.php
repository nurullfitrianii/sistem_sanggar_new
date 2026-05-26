@extends('layouts.admin')

@section('title', 'Data Iuran Valid')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center text-md-start">
        <h2 class="fw-bold text-dark">Data Iuran Siswa</h2>
        <p class="text-muted">Rekapitulasi iuran mingguan dan bulanan yang telah tervalidasi.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card section-card" style="border-radius: 12px;">
            <div class="card-header bg-white pt-4 px-4">
                <ul class="nav nav-pills gap-2" id="dataIuranTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill fw-bold" id="mingguan-tab" data-bs-toggle="tab" data-bs-target="#mingguan" type="button" role="tab" style="background-color: #A47251; color: white;">
                            Iuran Mingguan <span class="badge bg-light text-dark ms-2">{{ $iuranMingguanValid->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold text-dark border bg-white" id="bulanan-tab" data-bs-toggle="tab" data-bs-target="#bulanan" type="button" role="tab">
                            Iuran Bulanan <span class="badge bg-light text-dark ms-2">{{ $iuranBulananMerged->count() }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body px-4 pb-4 pt-3">
                <div class="tab-content" id="dataIuranTabsContent">

                    {{-- Tab Mingguan --}}
                    <div class="tab-pane fade show active" id="mingguan" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4 py-3 border-0">Siswa</th>
                                        <th class="py-3 border-0">Metode</th>
                                        <th class="py-3 border-0">Tgl Bayar</th>
                                        <th class="pe-4 py-3 border-0 text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($iuranMingguanValid as $iuran)
                                    <tr class="border-bottom">
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $iuran->user->username ?? 'Unknown' }}</div>
                                            <div class="small text-muted">ID: {{ $iuran->user->id_user ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge {{ ($iuran->metode_pembayaran == 'transfer') ? 'bg-soft-info text-info border-info' : 'bg-soft-secondary text-secondary border-secondary' }} border border-opacity-25 px-3 py-2 rounded-pill">
                                                {{ strtoupper($iuran->metode_pembayaran ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            @if($iuran->tanggal_bayar)
                                                {{ \Carbon\Carbon::parse($iuran->tanggal_bayar)->format('d M Y') }}
                                            @else
                                                <span class="text-warning">-</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end text-success">
                                            Rp {{ number_format($iuran->jumlah_bayar ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada iuran mingguan yang tervalidasi.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab Bulanan --}}
                    <div class="tab-pane fade" id="bulanan" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4 py-3 border-0">Siswa</th>
                                        <th class="py-3 border-0">Metode</th>
                                        <th class="py-3 border-0">Tgl Bayar</th>
                                        <th class="pe-4 py-3 border-0 text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($iuranBulananMerged as $iuran)
                                    <tr class="border-bottom">
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $iuran->user->username ?? 'Unknown' }}</div>
                                            <div class="small text-muted">ID: {{ $iuran->user->id_user ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge {{ ($iuran->metode_pembayaran == 'transfer') ? 'bg-soft-info text-info border-info' : 'bg-soft-secondary text-secondary border-secondary' }} border border-opacity-25 px-3 py-2 rounded-pill">
                                                {{ strtoupper($iuran->metode_pembayaran ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            @if($iuran->tanggal_bayar)
                                                {{ \Carbon\Carbon::parse($iuran->tanggal_bayar)->format('d M Y') }}
                                            @else
                                                <span class="text-warning">-</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end text-success">
                                            Rp {{ number_format($iuran->jumlah_bayar ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada iuran bulanan yang tervalidasi.</td></tr>
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
@endsection
