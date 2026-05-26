@extends('layouts.admin')

@section('title', 'Manajemen Keuangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 fw-bold text-dark">Manajemen Keuangan</h1>
        <p class="text-muted small mb-0">Kelola arus kas masuk dan keluar secara dual-entry.</p>
    </div>
    <a href="{{ route(($routePrefix ?? 'keuangan') . '.create') }}" class="btn shadow-sm rounded-3 px-4 btn-outline-success">
        Catat Transaksi
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">{{ session('success') }}</div>
@endif

<div class="card rounded-4 overflow-hidden section-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">Tanggal</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                        <tr class="transition-all">
                            <td class="ps-4">
                                <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</div>
                            </td>
                            <td>
                                @if($t->jenis === 'Masuk')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">Masuk</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 rounded-pill">Keluar</span>
                                @endif
                            </td>
                            <td class="{{ $t->jenis === 'Masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $t->jenis === 'Masuk' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                            </td>
                            <td>
                                <div class="text-muted small text-truncate" style="max-width: 250px;">{{ $t->keterangan }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route(($routePrefix ?? 'keuangan') . '.destroy', $t->id_transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn text-danger p-0 border-0 shadow-none btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <p class="mt-2 mb-0">Belum ada data transaksi keuangan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 bg-light border-top">
            {{ $transaksi->links() }}
        </div>
    </div>
</div>
@endsection

