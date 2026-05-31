@extends('layouts.admin')

@section('title', 'Catat Transaksi Keuangan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route(($routePrefix ?? 'keuangan') . '.index') }}" class="btn rounded-pill px-3 py-2 shadow-sm btn-outline-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h1 class="h3 mb-0 fw-bold">Catat Transaksi Baru</h1>
        </div>

        <div class="card rounded-4 p-4 section-card">
            <form action="{{ route(($routePrefix ?? 'keuangan') . '.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                               class="form-control @error('tanggal') is-invalid @enderror" required>
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jenis Arus Kas</label>
                        <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                            <option value="">Pilih Jenis...</option>
                            <option value="Masuk" {{ old('jenis') == 'Masuk' ? 'selected' : '' }}>Pemasukan (Masuk)</option>
                            <option value="Keluar" {{ old('jenis') == 'Keluar' ? 'selected' : '' }}>Pengeluaran (Keluar)</option>
                        </select>
                        @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nominal (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">Rp</span>
                        <input type="number" name="nominal" value="{{ old('nominal') }}"
                               class="form-control @error('nominal') is-invalid @enderror border-start-0" placeholder="0" required>
                    </div>
                    @error('nominal')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan Transaksi</label>
                    <textarea name="keterangan" rows="4"
                              class="form-control @error('keterangan') is-invalid @enderror"
                              placeholder="Contoh: Biaya Pendaftaran Lomba Tari Jaipong, Pembelian Seragam, Hadiah Juara 1, dll." required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn py-3 rounded-3 shadow-sm btn-outline-success">
                        Simpan Transaksi
                    </button>
                    <a href="{{ route(($routePrefix ?? 'keuangan') . '.index') }}" class="btn py-2 rounded-3 text-muted btn-outline-primary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

