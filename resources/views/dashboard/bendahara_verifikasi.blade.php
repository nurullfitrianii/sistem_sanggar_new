@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center">
        <h2 class="fw-bold text-dark">Verifikasi Pembayaran</h2>
        <p class="text-muted">Tinjau dan konfirmasi bukti pembayaran pendaftaran dan iuran.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card section-card" style="border-radius: 12px;">
            <div class="card-header bg-white pt-4 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">Semua Antrean Pembayaran</h6>
                <span class="badge bg-warning text-dark px-3 rounded-pill">{{ $totalPending }} Menunggu</span>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="border-0">Siswa</th>
                                <th class="border-0">Jenis Pembayaran</th>
                                <th class="border-0">Jumlah</th>
                                <th class="border-0">Metode</th>
                                <th class="border-0 text-center">Bukti</th>
                                <th class="border-0 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unifiedList as $item)
                            <tr class="border-bottom">
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->user->username ?? 'Unknown' }}</div>
                                    <div class="small text-muted">ID: {{ $item->user->id_user ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($item->type == 'pendaftaran')
                                        <span class="badge bg-warning text-dark border border-warning px-3 py-2 rounded-pill">PENDAFTARAN</span>
                                    @else
                                        <span class="badge bg-primary text-white border border-primary px-3 py-2 rounded-pill">IURAN</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</div>
                                    <div class="small text-muted">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ ucfirst($item->metode) }}</span></td>
                                <td class="text-center">
                                     @if($item->bukti_bayar)
                                         <a href="{{ asset('storage/' . $item->bukti_bayar) }}" target="_blank" class="btn btn-sm rounded-pill px-3 btn-outline-primary">
                                             Lihat
                                         </a>
                                     @else
                                         <span class="text-muted small">-</span>
                                     @endif
                                 </td>
                                 <td class="text-end">
                                     <div class="d-flex justify-content-end gap-2">
                                         @if($item->type == 'pendaftaran' || $item->type == 'pendaftaran_awal' || $item->type == 'pembayaran')
                                             <!-- Action untuk Pendaftaran / Pembayaran -->
                                             <form action="{{ route('bendahara.verifikasi', [$item->type, $item->id_item, 'terima']) }}" method="POST" class="m-0">
                                                 @csrf
                                                 <button type="submit" class="btn btn-sm px-3 rounded-pill btn-outline-success" onclick="return confirm('Validasi pembayaran ini?');"> Valid </button>
                                             </form>
                                             
                                             <form action="{{ route('bendahara.verifikasi', [$item->type, $item->id_item, 'tolak']) }}" method="POST" class="m-0">
                                                 @csrf
                                                 <button type="submit" class="btn btn-sm px-3 rounded-pill btn-outline-danger" onclick="return confirm('Tolak pembayaran ini?');"> Tolak </button>
                                             </form>
                                         @else
                                             <!-- Action untuk Iuran Mingguan / Lainnya -->
                                             <form action="{{ route('bendahara.iuran.status', $item->id_item) }}" method="POST" class="m-0">
                                                 @csrf
                                                 <input type="hidden" name="status" value="valid">
                                                 <button type="submit" class="btn btn-sm px-3 rounded-pill btn-outline-success" onclick="return confirm('Validasi {{ $item->kategori }} ini?');"> Valid </button>
                                             </form>
                                             
                                             <form action="{{ route('bendahara.iuran.status', $item->id_item) }}" method="POST" class="m-0">
                                                 @csrf
                                                 <input type="hidden" name="status" value="ditolak">
                                                 <button type="submit" class="btn btn-sm px-3 rounded-pill btn-outline-danger" onclick="return confirm('Tolak {{ $item->kategori }} ini?');"> Tolak </button>
                                             </form>
                                         @endif
                                     </div>
                                 </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <p class="mb-0">Tidak ada antrean pembayaran yang perlu diverifikasi.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
