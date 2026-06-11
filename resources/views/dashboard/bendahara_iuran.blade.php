@extends('layouts.admin')

@section('title', 'Verifikasi Iuran')

@section('content')
<div class="row mb-4 border-bottom border-light pb-3">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-1">Verifikasi Iuran</h3>
        <p class="text-muted mb-0">Kelola dan verifikasi pembayaran iuran dari siswa</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="background-color: #ECFDF5; color: #10B981;">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="background-color: #FEF2F2; color: #EF4444;">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card px-4 py-4 section-card">
            <h6 class="fw-bold mb-4 text-dark">Daftar Transaksi Iuran</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" >
                    <thead>
                        <tr>
                            <th class="border-0 fw-normal">Siswa</th>
                            <th class="border-0 fw-normal">Tipe Iuran</th>
                            <th class="border-0 fw-normal">Jumlah</th>
                            <th class="border-0 fw-normal">Metode</th>
                            <th class="border-0 fw-normal">Status</th>
                            <th class="pe-4 text-end border-0 fw-normal">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($iurans as $iuran)
                        <tr>
                            <td class="ps-4 text-dark border-top-0 border-bottom-0 bg-white rounded-start-3" style="border: 1px solid #E5E7EB; border-right: none;">
                                {{ $iuran->user->username ?? 'Unknown' }}
                            </td>
                            <td class="text-dark border-top-0 border-bottom-0 bg-white" style="border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB;">
                                {{ ucfirst($iuran->tipe_iuran) }}
                            </td>
                            <td class="text-dark border-top-0 border-bottom-0 bg-white" style="border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB;">
                                Rp {{ number_format($iuran->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="border-top-0 border-bottom-0 bg-white" style="border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB;">
                                <span class="badge bg-light text-dark border">{{ ucfirst($iuran->metode_pembayaran) }}</span>
                            </td>
                            <td class="border-top-0 border-bottom-0 bg-white" style="border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB;">
                                @if($iuran->status == 'valid')
                                    <span class="badge px-3 py-1 rounded-pill" style="background-color: #ECFDF5; color: #10B981;">Valid</span>
                                @elseif($iuran->status == 'ditolak')
                                    <span class="badge px-3 py-1 rounded-pill" style="background-color: #FEF2F2; color: #EF4444;">Ditolak</span>
                                @else
                                    <span class="badge px-3 py-1 rounded-pill" style="background-color: #FEF3C7; color: #F59E0B;">Pending</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end border-top-0 border-bottom-0 bg-white rounded-end-3" style="border: 1px solid #E5E7EB; border-left: none;">
                                
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    @if($iuran->metode_pembayaran == 'transfer' && $iuran->bukti_bayar)
                                        <button type="button" class="btn btn-sm rounded-pill px-3 btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $iuran->id }}">
                                            Bukti
                                        </button>
                                        
                                        <!-- Modal Bukti Bayar -->
                                        <div class="modal fade" id="modalBukti{{ $iuran->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-4 text-start">
                                                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                                                        <h5 class="fw-bold">Bukti Transfer</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4 text-center">
                                                        <img src="{{ asset('storage/' . $iuran->bukti_bayar) }}" alt="Bukti Pembayaran" class="img-fluid rounded-3" style="max-height: 400px; object-fit: contain;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($iuran->status == 'pending')
                                        <form id="approve-iuran-form-{{ $iuran->id }}" action="{{ route('bendahara.iuran.status', $iuran->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="status" value="valid">
                                            <button type="button" class="btn btn-sm rounded-pill px-3 shadow-sm btn-outline-success" onclick="confirmAction('approve-iuran-form-{{ $iuran->id }}', 'Yakin ingin memvalidasi iuran ini?')">
                                                Valid
                                            </button>
                                        </form>
                                        
                                        <form id="reject-iuran-form-{{ $iuran->id }}" action="{{ route('bendahara.iuran.status', $iuran->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="button" class="btn btn-sm rounded-pill px-3 shadow-sm btn-outline-danger" onclick="confirmAction('reject-iuran-form-{{ $iuran->id }}', 'Yakin ingin menolak iuran ini?')">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        @if($iuran->metode_pembayaran == 'tunai' || !$iuran->bukti_bayar)
                                            <span class="text-muted small">Selesai</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted small bg-white rounded-4 border">Belum ada transaksi iuran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
