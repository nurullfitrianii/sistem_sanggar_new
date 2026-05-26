<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: #994D1C; padding: 32px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #444; line-height: 1.7; }
        .body h2 { color: #994D1C; margin-top: 0; }
        .info-box { background: #fdf5f0; border-left: 4px solid #994D1C; padding: 16px 20px; border-radius: 8px; margin: 20px 0; }
        .info-box p { margin: 4px 0; font-size: 14px; }
        .info-box strong { color: #994D1C; }
        .btn { display: block; text-align: center; background: #994D1C; color: #fff; padding: 14px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 24px 0; font-size: 15px; }
        .footer { text-align: center; color: #aaa; font-size: 12px; padding: 20px; border-top: 1px solid #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Pembayaran Berhasil Diverifikasi</h1>
        </div>
        <div class="body">
            <h2>Halo, {{ $namaCalon }}!</h2>
            <p>Pembayaran kamu untuk program <strong>{{ $namaProgram }}</strong> di <strong>Sanggar Goong Prasasti</strong> telah <strong>berhasil diverifikasi</strong> oleh tim bendahara kami.</p>

            <div class="info-box">
                <p>💰 <strong>Jumlah:</strong> Rp {{ $jumlah }}</p>
                <p>📅 <strong>Tanggal Verifikasi:</strong> {{ $tanggal }}</p>
                <p>✅ <strong>Status:</strong> Lunas</p>
            </div>

            <p>Akunmu kini sudah aktif dan kamu bisa mulai mengikuti kegiatan di sanggar. Sampai jumpa di latihan! 🎶</p>

            <a href="{{ config('app.url') }}/login" class="btn btn-outline-primary">
                🚀 Login ke Dashboard
            </a>

            <p style="color:#888; font-size:13px;">Jika ada pertanyaan, hubungi kami melalui WhatsApp atau kunjungi sanggar langsung.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Sanggar Goong Prasasti. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
