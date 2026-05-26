<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: #6c757d; padding: 32px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #444; line-height: 1.7; }
        .body h2 { color: #555; margin-top: 0; }
        .footer { text-align: center; color: #aaa; font-size: 12px; padding: 20px; border-top: 1px solid #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Update Pendaftaran Kamu</h1>
        </div>
        <div class="body">
            <h2>Halo, {{ $namaCalon }}.</h2>
            <p>Terima kasih telah mendaftar ke program <strong>{{ $namaProgram }}</strong> di <strong>Sanggar Goong Prasasti</strong>.</p>
            <p>Setelah ditinjau oleh tim kami, dengan berat hati kami sampaikan bahwa pendaftaran kamu <strong>belum dapat kami terima</strong> saat ini.</p>
            <p>Jika kamu ingin informasi lebih lanjut atau ingin mendaftar kembali, silakan hubungi kami langsung melalui WhatsApp atau kunjungi sanggar kami.</p>
            <p style="color:#888; font-size:13px;">Terima kasih atas minat kamu. Semoga kita bisa bertemu di kesempatan berikutnya! 🙏</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Sanggar Goong Prasasti. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
