@extends('layouts.admin')

@section('title', 'Jadwal Saya')

@push('styles')
    <style>
        .jadwal-header {
            background-color: #A47251;
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
<div class="row border-bottom border-light pb-3 mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-1">Jadwal Kelas Saya</h3>
        <p class="text-muted mb-0">Program: {{ $defaultTab }}</p>
    </div>
</div>

<div class="row" data-aos="fade-up">
    <div class="col-12">
        <div class="card section-card" style="border-radius: 15px;">
            <div class="card-body p-4">
                @php
                    $groupedJadwal = $jadwal->groupBy(function($item) {
                        return $item->tanggal_sesi->format('Y-m'); // Group by Month
                    });
                    
                    setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia', 'Indonesian');
                    \Carbon\Carbon::setLocale('id');
                @endphp

                @if($jadwal->isEmpty())
                    <div class="text-center text-muted py-5">
                        <h5>Belum ada jadwal untuk program ini</h5>
                        <p>Jadwal Anda belum tersedia saat ini.</p>
                    </div>
                @else
                    @foreach($groupedJadwal as $month => $items)
                        <h4 class="mb-4 mt-2 fw-bold text-primary border-bottom pb-2">
                            {{ \Carbon\Carbon::parse($month.'-01')->translatedFormat('F Y') }}
                        </h4>

                        @foreach($items->groupBy(function($i) { return $i->tanggal_sesi->format('Y-m-d'); }) as $dateString => $sessions)
                            <div class="mb-4 ms-3">
                                <h5 class="day-title mb-3">
                                    {{ \Carbon\Carbon::parse($dateString)->translatedFormat('l, d F Y') }}
                                </h5>
                                <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr >
                                                <th class="py-3 text-center" width="10%">Mulai</th>
                                                <th class="py-3 text-center" width="10%">Selesai</th>
                                                <th class="py-3" width="30%">Program & Materi</th>
                                                <th class="py-3" width="20%">Ruang</th>
                                                <th class="py-3" width="30%">Pelatih</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sessions->sortBy('jam_mulai') as $j)
                                            <tr class="jadwal-row">
                                                <td class="text-center fw-semibold">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</td>
                                                <td class="text-center fw-semibold">{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                                                <td>
                                                    <div class="fw-bold" style="color: #503422;">
                                                        {{ $j->programKelas->nama_program ?? '-' }}
                                                    </div>
                                                    <div class="small text-muted mt-1">
                                                        Materi: 
                                                        <span class="text-sanggar fw-medium">{{ $j->materi ?: 'Latihan Rutin' }}</span>
                                                    </div>
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
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
