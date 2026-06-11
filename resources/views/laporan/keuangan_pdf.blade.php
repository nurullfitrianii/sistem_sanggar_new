<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Sanggar Goong</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .header { text-align: center; margin-bottom: 25px; }
        .footer { text-align: right; margin-top: 30px; font-size: 10px; color: #777; }
        .text-success { color: #155724; font-weight: bold; }
        .text-danger { color: #721c24; font-weight: bold; }
        .summary-box {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            width: 30%;
            text-align: center;
        }
        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #858796;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN</h2>
        <h3>SANGGAR GOONG PRASASTI</h3>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-item" style="border-right: 1px solid #ddd;">
            <div class="summary-label">Total Pemasukan</div>
            <div class="summary-value text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item" style="border-right: 1px solid #ddd;">
            <div class="summary-label">Total Pengeluaran</div>
            <div class="summary-value text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Saldo Bersih</div>
            <div class="summary-value {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'text-success' : 'text-danger' }}">
                Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tanggal</th>
                <th width="20%">Jenis Transaksi</th>
                <th width="20%">Nominal</th>
                <th width="35%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d F Y') }}</td>
                <td>
                    @if($t->jenis === 'Masuk')
                        Pemasukan
                    @else
                        Pengeluaran
                    @endif
                </td>
                <td class="{{ $t->jenis === 'Masuk' ? 'text-success' : 'text-danger' }}">
                    {{ $t->jenis === 'Masuk' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                </td>
                <td>{{ $t->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #858796;">Belum ada data transaksi keuangan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak secara otomatis oleh Sistem Sanggar Goong</p>
    </div>
</body>
</html>
