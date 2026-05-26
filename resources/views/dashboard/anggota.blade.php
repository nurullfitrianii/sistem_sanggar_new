@extends('layouts.admin')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row border-bottom border-light pb-3 mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-1">Dashboard Siswa</h3>
        <p class="text-muted mb-0">Selamat datang kembali, {{ auth()->user()->username }}</p>
    </div>
</div>

@if(isset($statusTracking) && $statusTracking)
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card section-card shadow-lg p-4 p-md-5 text-center" style="border-radius: 30px;">
            <div class="mb-4">
                <div class="status-icon-container mb-3">
                    <div class="spinner-grow text-warning" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <h3 class="fw-bold text-dark">Status Pendaftaran: <span class="text-warning">Menunggu</span></h3>
                <p class="text-muted">Data pendaftaran kamu sedang dalam proses verifikasi oleh tim Humas kami.</p>
            </div>

            <div class="registration-details bg-light rounded-4 p-4 text-start mb-4">
                <h6 class="fw-bold text-uppercase small text-muted mb-3">Detail Pendaftaran</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Nama Lengkap</span>
                    <span class="fw-bold">{{ $pendaftaran->nama_calon ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Program Latihan</span>
                    <span class="fw-bold text-primary">{{ $pendaftaran->programKelas->nama_program ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Nomor WhatsApp</span>
                    <span class="fw-bold">{{ $pendaftaran->no_hp ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="alert alert-info border-0 rounded-3 shadow-sm d-flex align-items-center mb-0">
                <div class="text-start small">
                    <strong>Informasi:</strong> Akun kamu akan diaktifkan secara otomatis setelah pendaftaran disetujui. Kamu akan menerima notifikasi via WhatsApp.
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card px-4 py-4 h-100 section-card">
            <h6 class="fw-bold text-dark mb-1">Profil Siswa</h6>
            <p class="text-muted small mb-4">Informasi data diri</p>

            <div class="d-flex justify-content-between mb-3 border-bottom border-light pb-2">
                <span class="text-muted">Nama Lengkap</span>
                <span class="fw-bold text-dark">{{ auth()->user()->username }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 border-bottom border-light pb-2">
                <span class="text-muted">ID Siswa</span>
                <span class="fw-bold text-dark">SGP-{{ date('Y') }}-{{ str_pad(auth()->user()->id_user, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 border-bottom border-light pb-2">
                <span class="text-muted">Kelas</span>
                <span class="border rounded-pill px-3 py-1 small fw-bold text-dark">{{ $pendaftaran->programKelas->nama_program ?? 'Belum Ada' }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 border-bottom border-light pb-2">
                <span class="text-muted">Tanggal Bergabung</span>
                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse(auth()->user()->created_at ?? now())->translatedFormat('d F Y') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Status</span>
                <span class="text-success fw-bold small py-1 px-3" style="background-color: #ECFDF5; border-radius: 6px;">Aktif</span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card px-4 py-4 h-100 section-card">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="text-sanggar-primary fw-bold" style="font-size: 1.1rem;">$</span>
                <h6 class="fw-bold text-dark mb-0">Status Pembayaran</h6>
            </div>
            <p class="text-muted small mb-4">Pembayaran bulan ini</p>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Periode<br><strong class="text-dark">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</strong></span>
                <span class="text-success bg-soft-success px-3 py-1 rounded-pill small fw-bold" style="background-color: #ECFDF5;">
                    @php
                        $lunasThisMonth = $riwayatPembayaran->where('bulan', \Carbon\Carbon::now()->translatedFormat('F Y'))
                            ->filter(function($item) {
                                $status = strtolower($item->status_pembayaran ?? $item->status);
                                return in_array($status, ['success', 'settlement', 'lunas']);
                            })->count() > 0;
                    @endphp
                    {{ $lunasThisMonth ? 'Lunas' : 'Belum Bayar' }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="text-muted">Nominal</span>
                <h4 class="fw-bold text-dark mb-0">Rp 100.000</h4>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="text-muted">Jatuh Tempo</span>
                <span class="text-dark fw-bold">10 {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
            </div>

            <a href="#payment-table" class="btn w-100 py-2 mt-auto btn-outline-success" style="border-radius: 8px">Lihat Riwayat Pembayaran</a>
        </div>
    </div>
</div>

{{-- QR Code Section --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="card px-4 py-4 section-card" style="border-radius: 15px;">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="text-sanggar-primary fs-5"></span>
                        <h6 class="fw-bold text-dark mb-0">QR Code Absensi</h6>
                    </div>
                    <p class="text-muted small mb-0">Tunjukkan atau cetak QR Code ini untuk absensi</p>
                </div>
                <a href="{{ route('siswa.downloadqr') }}" class="btn btn-sm rounded-pill px-4 shadow-sm btn-outline-success">
                    Unduh ID Card (.svg)
                </a>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-5 p-4 border rounded-4 bg-light" style="border-color: #E5E7EB !important;">
                <div class="bg-white p-3 rounded-4 shadow-sm border border-light">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->color(0,0,0)->generate(auth()->user()->id_user) !!}
                </div>
                <div class="d-flex flex-column gap-3">
                    <div>
                        <div class="text-muted small mb-1 uppercase fw-bold" style="letter-spacing: 1px;">ID Siswa</div>
                        <h5 class="fw-bold text-dark mb-0 tracking-wider">SGP-{{ date('Y') }}-{{ str_pad(auth()->user()->id_user, 3, '0', STR_PAD_LEFT) }}</h5>
                    </div>
                    <div>
                        <div class="text-muted small mb-1 fw-bold">Nama Lengkap</div>
                        <h6 class="text-dark fw-bold mb-0 text-uppercase">{{ auth()->user()->username }}</h6>
                    </div>
                    <div>
                        <div class="text-muted small mb-1 fw-bold">Program Latihan</div>
                        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                            {{ $pendaftaran->programKelas->nama_program ?? 'Belum Ada' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Payment Table --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="card px-4 py-4 section-card" id="payment-table">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0 text-dark">Daftar Transaksi Iuran</h6>
                <button class="btn btn-sm btn-buka-modal px-3 rounded-pill btn-outline-success"
                        data-id="{{ $riwayatPembayaran->first()->id_pembayaran ?? '' }}"
                        data-bulan="{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}"
                        data-jumlah="100000">
                    + Bayar Iuran
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" >
                    <thead>
                        <tr>
                            <th class="border-0 fw-normal">Bulan Tagihan</th>
                            <th class="border-0 fw-normal">Jumlah</th>
                            <th class="border-0 fw-normal">Status</th>
                            <th class="border-0 fw-normal text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPembayaran as $bayar)
                        <tr>
                            <td class="ps-4 text-dark border-top-0 border-bottom-0 bg-white rounded-start-3" style="border: 1px solid #E5E7EB; border-right: none;">
                                {{ $bayar->bulan }}
                            </td>
                            <td class="text-dark border-top-0 border-bottom-0 bg-white" style="border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB;">
                                Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="border-top-0 border-bottom-0 bg-white" style="border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB;">
                                @php
                                    $statusFinal = strtolower($bayar->status_pembayaran ?? $bayar->status);
                                    $isLunas = in_array($statusFinal, ['success', 'settlement', 'lunas']);
                                @endphp

                                @if($isLunas)
                                    <span class="text-success fw-bold small py-1 px-3" style="background-color: #ECFDF5; border-radius: 6px;">Lunas</span>
                                @elseif(in_array($statusFinal, ['pending', 'menunggu verifikasi', 'menunggu pembayaran']))
                                    <span class="text-warning fw-bold small py-1 px-3" style="background-color: #FEF3C7; border-radius: 6px;">Menunggu</span>
                                @else
                                    <span class="text-danger fw-bold small py-1 px-3" style="background-color: #FEF2F2; border-radius: 6px;">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="border-top-0 border-bottom-0 bg-white rounded-end-3 text-end pe-4" style="border: 1px solid #E5E7EB; border-left: none;">
                                @if($isLunas)
                                    <span class="text-success small fw-bold">Selesai</span>
                                @else
                                    <span class="text-muted small italic">Proses...</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted small bg-white rounded-4 border">Belum ada catatan pembayaran.</td></tr>
                        @endforelse

                        {{-- Tagihan Mendatang --}}
                        @php
                            $bulanDepan = \Carbon\Carbon::now()->addMonth()->translatedFormat('F Y');
                        @endphp
                        <tr style="opacity: 0.7;">
                            <td class="ps-4 text-muted border-top-0 border-bottom-0 bg-light rounded-start-3" style="border: 1px dashed #E5E7EB; border-right: none;">
                                {{ $bulanDepan }} <small class="badge bg-secondary ms-1">Mendatang</small>
                            </td>
                            <td class="text-muted border-top-0 border-bottom-0 bg-light" style="border-top: 1px dashed #E5E7EB; border-bottom: 1px dashed #E5E7EB;">
                                Rp 100.000
                            </td>
                            <td class="border-top-0 border-bottom-0 bg-light" style="border-top: 1px dashed #E5E7EB; border-bottom: 1px dashed #E5E7EB;">
                                <span class="text-muted small">Belum Tersedia</span>
                            </td>
                            <td class="border-top-0 border-bottom-0 bg-light rounded-end-3 text-end pe-4" style="border: 1px dashed #E5E7EB; border-left: none;">
                                </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="modalBayarIuranBaru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="fw-bold">Form Pembayaran Iuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">Aksi</button>
            </div>
            <div class="modal-body px-4">
                <p class="text-muted small">Pembayaran untuk bulan: <strong id="txtBulan"></strong></p>
                <input type="hidden" id="pembayaran_id">

                <div class="mb-3">
                <label class="form-label small fw-bold">Tipe Iuran</label>
                <select id="tipe_iuran" class="form-select rounded-3"
                        data-biaya-bulanan="100000">
                    <option value="bulanan">Bulanan (8 Pertemuan)</option>
                    <option value="mingguan">Mingguan (Per Datang)</option>
                </select>
            </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Metode Pembayaran</label>
                    <select id="metode_pembayaran" class="form-select rounded-3">
                        <option value="transfer">Transfer (Otomatis Midtrans)</option>
                        <option value="tunai">Tunai (Bayar ke Sanggar)</option>
                    </select>
                </div>

                <div class="alert alert-secondary py-2 border-0">
                <small>Total Bayar: <strong>Rp <span id="display_nominal">0</span></strong></small>
                <input type="hidden" id="input_nominal_bayar">
            </div>
            <div class="modal-footer border-0 pb-4 px-4 pt-0">
                <button type="button" class="btn rounded-pill px-4 btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnProsesBayar" class="btn rounded-pill px-4 btn-outline-success" >Proses Bayar</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('modalBayarIuranBaru');
    const modalBayar = new bootstrap.Modal(modalElement);
    const btnProses = document.getElementById('btnProsesBayar');
    const selectTipe = document.getElementById('tipe_iuran');
    const displayNominal = document.getElementById('display_nominal');
    const inputNominal = document.getElementById('input_nominal_bayar');

    const resetBtn = () => {
        btnProses.disabled = false;
        btnProses.innerHTML = 'Proses Bayar';
    };

    function updateHarga() {
        const biayaBulanan = parseInt(selectTipe.getAttribute('data-biaya-bulanan')) || 100000;
        let hargaFinal = biayaBulanan;

        if (selectTipe.value === 'mingguan') {
            hargaFinal = 15000;
        }

        if(displayNominal) displayNominal.innerText = hargaFinal.toLocaleString('id-ID');
        if(inputNominal) inputNominal.value = hargaFinal;
    }

    if(selectTipe) {
        selectTipe.addEventListener('change', updateHarga);
    }

    document.querySelectorAll('.btn-buka-modal').forEach(button => {
        button.onclick = function() {
            document.getElementById('pembayaran_id').value = this.getAttribute('data-id');
            document.getElementById('txtBulan').innerText = this.getAttribute('data-bulan');
            updateHarga();
            modalBayar.show();
        };
    });

    btnProses.onclick = function() {
        const id = document.getElementById('pembayaran_id').value;
        const tipe = selectTipe.value;
        const metode = document.getElementById('metode_pembayaran').value;
        const nominal = inputNominal.value;

        if (nominal == 0 || nominal == "") {
            alert("Nominal pembayaran tidak valid!");
            return;
        }

        btnProses.disabled = true;
        btnProses.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

        fetch(`/iuran/bayar/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                tipe_iuran: tipe,
                metode_pembayaran: metode,
                jumlah_bayar: nominal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.metode === 'transfer') {
                    modalBayar.hide();
                    window.snap.pay(data.token, {
                        onSuccess: function(result) { location.reload(); },
                        onPending: function(result) { location.reload(); },
                        onError: function() { alert("Pembayaran Gagal!"); resetBtn(); },
                        onClose: function() { resetBtn(); }
                    });
                } else {
                    alert(data.message);
                    location.reload();
                }
            } else {
                alert("Error: " + data.message);
                resetBtn();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Terjadi kesalahan sistem!");
            resetBtn();
        });
    };
});
</script>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
    <style>
        .status-icon-container { display: inline-flex; align-items: center; justify-content: center; width: 100px; height: 100px; background: rgba(255, 193, 7, 0.1); border-radius: 50%; }
        .rounded-4 { border-radius: 1.25rem !important; }
        .bg-soft-success { background-color: #ECFDF5; color: #059669; }
    </style>
@endpush
@endsection
