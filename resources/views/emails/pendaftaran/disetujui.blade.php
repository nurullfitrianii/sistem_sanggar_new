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
        .wa-btn { display: block; text-align: center; background: #25D366; color: #fff; padding: 14px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 24px 0; font-size: 15px; }
        .footer { text-align: center; color: #aaa; font-size: 12px; padding: 20px; border-top: 1px solid #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Selamat! Pendaftaran Disetujui</h1>
        </div>
        <div class="body">
            <h2>Halo, {{ $namaCalon }}!</h2>
            <p>Pendaftaran kamu ke program <strong>{{ $namaProgram }}</strong> di <strong>Sanggar Goong Prasasti</strong> telah <strong>disetujui</strong> oleh tim kami.</p>
            <p>Berikut informasi akun kamu untuk login ke sistem:</p>

            <div class="info-box">
                <p>👤 <strong>Username:</strong> {{ $username }}</p>
                <p>🔑 <strong>Password:</strong> {{ $password }} <small style="color:#999">(nomor HP kamu)</small></p>
                <p>🌐 <strong>Login di:</strong> <a href="{{ config('app.url') }}/login" style="color:#994D1C">{{ config('app.url') }}/login</a></p>
            </div>

            <p>Setelah login, kamu bisa bergabung ke grup WhatsApp komunitas kami:</p>
            <a href="https://chat.whatsapp.com/HxiTzSjq6Z17GHPqcC0mso" class="wa-btn">
                💬 Gabung Grup WhatsApp Sanggar
            </a>

            <p style="color:#888; font-size:13px;">Jika ada pertanyaan, hubungi kami melalui WhatsApp atau kunjungi sanggar langsung. Sampai jumpa di latihan! 🎶</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Sanggar Goong Prasasti. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
