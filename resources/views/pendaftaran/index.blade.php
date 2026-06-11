@extends('layouts.admin')

@section('title', 'Manajemen Pendaftaran')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark">Daftar Calon Siswa</h2>
                <p class="text-muted">Kelola calon siswa yang mendaftar melalui website.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center" role="alert">
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card rounded-4 overflow-hidden section-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Tanggal</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Nama Calon</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Program</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Kontak</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Dokumen</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Status</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $pendaftaran)
                        <tr>
                            <td class="ps-4">
                                <span class="text-dark fw-medium">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d M Y') }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $pendaftaran->nama_calon }}</div>
                                <div class="small text-muted">{{ $pendaftaran->alamat }}</div>
                            </td>
                            <td>
                                <span class="fw-medium">
                                    {{ $pendaftaran->programKelas->nama_program ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ $pendaftaran->no_hp }}" target="_blank" class="text-decoration-none text-success d-inline-flex align-items-center">
                                    <span>{{ $pendaftaran->no_hp }}</span>
                                </a>
                            </td>
                            <td>
                                @if($pendaftaran->dokumen)
                                    <a href="{{ asset('storage/' . $pendaftaran->dokumen) }}" target="_blank" class="btn btn-sm rounded-pill px-3 btn-outline-primary">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-muted small">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'Menunggu' => 'bg-warning',
                                        'Aktif'    => 'bg-success',
                                        'Ditolak'  => 'bg-danger'
                                    ][$pendaftaran->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }} px-2 py-1">{{ $pendaftaran->status }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                @if($pendaftaran->status === 'Menunggu')
                                    <form action="{{ route('pendaftaran.approve', $pendaftaran->id_pendaftaran) }}" method="POST" class="d-inline" id="approve-{{ $pendaftaran->id_pendaftaran }}">
                                        @csrf
                                        <button type="button" class="btn btn-sm rounded-pill px-3 me-1 btn-outline-success" onclick="confirmAction('approve-{{ $pendaftaran->id_pendaftaran }}', 'Setujui pendaftaran ini?')">
                                            Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('pendaftaran.reject', $pendaftaran->id_pendaftaran) }}" method="POST" class="d-inline" id="reject-{{ $pendaftaran->id_pendaftaran }}">
                                        @csrf
                                        <button type="button" class="btn btn-sm rounded-pill px-3 btn-outline-danger" onclick="confirmAction('reject-{{ $pendaftaran->id_pendaftaran }}', 'Tolak pendaftaran ini?')">
                                            Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Belum ada data pendaftaran.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pendaftarans->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $pendaftarans->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    /* Custom Styling */
    .bg-soft-primary { background-color: rgba(164, 114, 81, 0.1); color: #994D1C; border: 1px solid rgba(164, 114, 81, 0.2); }
    .rounded-4 { border-radius: 1rem !important; }
    .table thead th { border-bottom: none; }
    .card { transition: transform 0.2s; }

    /* FIX IKON RAKSASA: Membatasi ukuran ikon */
    .icon-fix, i.bi, .bi::before {
        display: inline-block !important;
        width: 1.2rem !important;
        height: 1.2rem !important;
        font-size: 1.2rem !important;
        vertical-align: middle !important;
    }

    /* Mencegah SVG bocor ukuran ke seluruh layar */
    svg {
        max-width: 24px !important;
        max-height: 24px !important;
    }
</style>

@push('scripts')
<script>
    function confirmAction(formId, message) {
        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#994D1C',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush
@endsection
