@extends('layouts.admin')

@section('title', 'Manajemen Jadwal Kelas')

@push('styles')
    <style>
        .jadwal-header {
            background-color: #A47251; /* Primary brown */
            color: white;
        }
        .jadwal-row {
            background-color: #fff;
            transition: all 0.2s ease;
        }
        .jadwal-row:hover {
            background-color: #fcf8f5;
        }
        .jadwal-table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .jadwal-table td {
            vertical-align: middle;
            font-size: 0.95rem;
        }
        .day-title {
            color: #503422;
            font-weight: 700;
            border-bottom: 2px solid #DD9E59;
            padding-bottom: 0.5rem;
        }
    </style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <h1 class="h4 mb-0 fw-bold text-dark">Jadwal Kelas (Master List)</h1>
    <a href="{{ route('jadwal.create') }}" class="btn px-4 py-2 btn-outline-success" style="border-radius: 8px">Tambah Jadwal</a>
</div>

@if(session('success'))
    <div class="alert alert-success shadow-sm" style="border-radius: 10px;">{{ session('success') }}</div>
@endif

<div class="row g-4" data-aos="fade-up" data-aos-delay="100">
    <div class="col-12">
        @php
            // Filter hanya jadwal yang ada
            $groupedJadwal = $jadwal->groupBy(function($item) {
                return ucfirst(strtolower($item->hari));
            });
            
            // Format tanggal Indonesia
            setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia', 'Indonesian');
            \Carbon\Carbon::setLocale('id');
            
            // Urutan hari yang ingin ditampilkan (Hanya Sabtu dan Minggu)
            $hariUrutan = ['Sabtu', 'Minggu'];
        @endphp

        @if($groupedJadwal->isEmpty())
            <div class="card section-card" style="border-radius: 15px;">
                <div class="card-body p-5 text-center text-muted">
                    <h5>Belum ada jadwal yang dibuat</h5>
                    <p>Silakan klik tombol "Tambah Jadwal" untuk membuat jadwal baru.</p>
                </div>
            </div>
        @else
                    @foreach($hariUrutan as $hariName)
                        @if($groupedJadwal->has($hariName))
                            @php
                                $dayMapping = [
                                    'Sabtu' => 'Saturday',
                                    'Minggu' => 'Sunday',
                                    'Senin' => 'Monday',
                                    'Selasa' => 'Tuesday',
                                    'Rabu' => 'Wednesday',
                                    'Kamis' => 'Thursday',
                                    'Jumat' => 'Friday'
                                ];
                                $englishDay = $dayMapping[$hariName] ?? $hariName;
                                $date = \Carbon\Carbon::parse('this '.$englishDay);
                            @endphp
                            <div class="mb-5">
                                <h5 class="day-title mb-3">
                                    {{ $hariName }}, {{ $date->translatedFormat('d F Y') }}
                                </h5>
                        <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr >
                                        <th class="py-3 text-center" width="8%">Mulai</th>
                                        <th class="py-3 text-center" width="8%">Selesai</th>
                                        <th class="py-3" width="15%">Kategori Kelas</th>
                                        <th class="py-3" width="20%">Program Kelas</th>
                                        <th class="py-3" width="10%">Materi</th>
                                        <th class="py-3" width="15%">Ruang</th>
                                        <th class="py-3" width="15%">Pelatih</th>
                                        <th class="py-3 text-center" width="9%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedJadwal[$hariName]->sortBy('jam_mulai') as $j)
                                    <tr class="jadwal-row">
                                        <td class="text-center fw-semibold">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</td>
                                        <td class="text-center fw-semibold">{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                                        <td>
                                            {{ $j->programKelas->kategori ?? 'Umum' }}
                                        </td>
                                        <td class="" style="color: #503422;">
                                            {{ $j->programKelas->nama_program ?? '-' }}
                                        </td>
                                        <td class="text-muted fst-italic">
                                            Latihan Rutin
                                        </td>
                                        <td>
                                            {{ $j->lokasi ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-1 me-2 border">
                                                    </div>
                                                <span class="fw-medium">{{ $j->pelatih->nama_pelatih ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('jadwal.edit', $j->id_jadwal) }}" class="btn btn-sm shadow-sm btn-outline-warning" title="Edit">Edit</a>
                                                <form action="{{ route('jadwal.destroy', $j->id_jadwal) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm shadow-sm btn-outline-danger" title="Hapus">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

<div class="row mt-5" data-aos="fade-up">
    <div class="col-12">
        <h4 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-people-fill" style="color: #A47251;"></i> Daftar Siswa per Program</h4>
        <div class="row g-4">
            @foreach($kelas as $k)
            <div class="col-md-6">
                <div class="card rounded-4 h-100 section-card">
                    <div class="card-header bg-white text-dark py-3" style="border-radius: 1rem 1rem 0 0;">
                        <h5 class="mb-1 fw-bold">{{ $k->nama_program }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($k->pendaftaran as $pend)
                                <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-semibold">{{ $pend->nama_calon }}</span>
                                        <br>
                                        <small class="text-muted">{{ $pend->username }}</small>
                                    </div>
                                    <span class="badge bg-{{ in_array($pend->status, ['Disetujui', 'Aktif']) ? 'success' : ($pend->status == 'Menunggu' ? 'warning' : 'danger') }} rounded-pill">
                                        {{ in_array($pend->status, ['Disetujui', 'Aktif']) ? 'Aktif' : ($pend->status == 'Menunggu' ? 'Menunggu' : 'Nonaktif') }}
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">Belum ada siswa terdaftar</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

