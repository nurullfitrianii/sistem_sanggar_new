<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Sanggar Goong</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: right; margin-top: 30px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN ABSENSI SISWA</h2>
        <h3>SANGGAR GOONG PRASASTI</h3>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Program/Kelas</th>
                <th>Jadwal</th>
                <th>Waktu Hadir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $index => $row)
            @php
                $jadwal = $row->getJadwal();
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->user->nama_lengkap ?? $row->user->pendaftaran->nama_calon ?? $row->user->username ?? '-' }}</td>
                <td>{{ $jadwal->programKelas->nama_program ?? $row->user->pendaftaran->programKelas->nama_program ?? '-' }}</td>
                <td>{{ $jadwal->hari ?? '-' }}, {{ $jadwal->jam_mulai ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($row->waktu_hadir)->format('d/m/Y H:i') }}</td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak secara otomatis oleh Sistem Sanggar Goong</p>
    </div>
</body>
</html>
