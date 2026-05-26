@extends('layouts.admin')

@section('title', 'Input Absensi')

@section('content')
<h1 class="h4 mb-3">Input Absensi Siswa</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('absensi.store') }}" method="POST" class="card border-0 shadow-sm p-4 mb-3">
    @csrf
    <div class="mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
               class="form-control @error('tanggal') is-invalid @enderror" required>
        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Jadwal Kelas</label>
        <select name="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror" required>
            <option value="">-- Pilih Jadwal --</option>
            @foreach($jadwal as $j)
                <option value="{{ $j->id }}" {{ old('jadwal_id') == $j->id ? 'selected' : '' }}>
                    {{ $j->kelas->nama ?? '-' }} • {{ ucfirst($j->hari) }} • {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                </option>
            @endforeach
        </select>
        @error('jadwal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <h2 class="h6 mt-4 mb-2">Daftar Siswa</h2>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $ang)
                    <tr>
                        <td>{{ $ang->name }}</td>
                        <td>
                            <select name="absensi[{{ $ang->id }}]" class="form-select form-select-sm">
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="alpha">Alpha</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="keterangan[{{ $ang->id }}]" class="form-control form-control-sm">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada siswa aktif.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <button type="submit" class="btn mt-3 btn-outline-success">Simpan Absensi</button>
</form>
@endsection

