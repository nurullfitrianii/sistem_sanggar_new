@extends('layouts.admin')

@section('title', 'Input Absensi Manual')

@section('content')
<div class="row border-bottom border-light pb-3 mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-1">Input Absensi Manual</h3>
        <p class="text-muted mb-0">Humas dapat menginput absensi siswa (Sakit, Izin, Alfa)</p>
    </div>
</div>

<div class="row">
    <!-- Form Input Manual -->
    <div class="col-md-4">
        <div class="card section-card p-4">
            <form action="{{ route('absensi.storeManual') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Siswa</label>
                    <select name="id_user" class="form-select @error('id_user') is-invalid @enderror" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswa as $s)
                            <option value="{{ $s->id_user }}">{{ $s->username }}</option>
                        @endforeach
                    </select>
                    @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Jadwal</label>
                    <select name="id_jadwal" class="form-select @error('id_jadwal') is-invalid @enderror" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach($jadwal as $j)
                            <option value="{{ $j->id_jadwal }}">
                                {{ $j->programKelas->nama_program ?? '-' }} &mdash; {{ $j->hari }}, 
                                {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_jadwal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status Kehadiran</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusHadir" value="Hadir" checked>
                            <label class="form-check-label" for="statusHadir">Hadir</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusSakit" value="Sakit">
                            <label class="form-check-label" for="statusSakit">Sakit</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusIzin" value="Izin">
                            <label class="form-check-label" for="statusIzin">Izin</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusAlfa" value="Alfa">
                            <label class="form-check-label" for="statusAlfa">Alfa</label>
                        </div>
                    </div>
                    @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Sakit Flu, Izin Acara Keluarga"></textarea>
                </div>

                <button type="submit" class="btn w-100 py-2 btn-outline-success">Simpan Absensi</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Absensi -->
    <div class="col-md-8">
        <div class="card section-card p-4">
            <h5 class="fw-bold mb-3">Data Absensi Siswa</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Waktu Hadir</th>
                            <th>Nama Siswa</th>
                            <th>Program</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaAbsensi as $absen)
                        @php
                            $jadwal = $absen->getJadwal();
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($absen->waktu_hadir)->format('d M Y, H:i') }}</td>
                            <td class="">{{ $absen->user->nama_lengkap ?? $absen->user->username ?? '-' }}</td>
                            <td>{{ $jadwal->programKelas->nama_program ?? $absen->user->pendaftaran->programKelas->nama_program ?? '-' }}</td>
                            <td>
                                @if(strtolower($absen->status) == 'hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif(strtolower($absen->status) == 'sakit' || strtolower($absen->status) == 'izin')
                                    <span class="badge bg-warning text-dark">{{ ucfirst($absen->status) }}</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($absen->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $absen->id_absensi }}"></button>
                                <form action="{{ route('absensi.destroyManual', $absen->id_absensi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $absen->id_absensi }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Absensi: {{ $absen->user->username ?? '-' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('absensi.updateManual', $absen->id_absensi) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="Hadir" {{ $absen->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                                    <option value="Sakit" {{ $absen->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                                    <option value="Izin" {{ $absen->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="Alfa" {{ $absen->status == 'Alfa' ? 'selected' : '' }}>Alfa</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Keterangan</label>
                                                <textarea name="keterangan" class="form-control" rows="2">{{ $absen->keterangan }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-outline-success">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-2">
                    {{ $semuaAbsensi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
