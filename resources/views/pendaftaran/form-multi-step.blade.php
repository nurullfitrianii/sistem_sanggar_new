<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru - Sanggar Goong Prasasti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
</head>
<body class="bg-[#FAFAFA] text-gray-800 font-sans antialiased">

    <div class="min-h-screen flex flex-col items-center py-12 px-4">
        {{-- Header --}}
        <div class="text-center mb-10">
            <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="h-20 mx-auto mb-4">
            <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">Pendaftaran Siswa Baru</h1>
            <p class="text-primary font-semibold tracking-wider uppercase text-sm mt-1">Sanggar Goong Prasasti</p>
        </div>

        <div class="w-full max-w-2xl bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
            {{-- Progress Stepper --}}
            <div class="bg-gray-50 border-b border-gray-100 px-8 py-6">
                <div class="flex items-center justify-between relative">
                    <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -translate-y-1/2 z-0"></div>
                    <div class="absolute top-1/2 left-0 h-0.5 bg-primary -translate-y-1/2 z-0 transition-all duration-500" style="width: {{ ($step - 1) * 50 }}%"></div>

                    @foreach ([1, 2, 3] as $s)
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 font-bold mb-2 transition-all {{ $step >= $s ? 'bg-primary text-white border-primary' : 'bg-white text-gray-400 border-gray-300' }}">
                            {{ $s }}
                        </div>
                        <span class="text-[10px] font-bold uppercase {{ $step >= $s ? 'text-primary' : 'text-gray-400' }}">
                            @if($s == 1) Info Pribadi @elseif($s == 2) Pilih Kelas @else Pembayaran @endif
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="p-8 md:p-12">
                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
                        <h3 class="text-sm font-bold text-red-800 italic">Ups! Ada yang salah:</h3>
                        <ul class="mt-1 list-disc list-inside text-xs text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- STEP 0: PENDAFTARAN DITUTUP / AKAN DIBUKA --}}
                @if($step == 0)
                    <div class="text-center py-10">
                        <div class="w-20 h-20 bg-orange-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                            </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 italic">Mohon Maaf...</h3>
                        <p class="text-gray-600 mb-8 px-4">{{ $message ?? 'Pendaftaran saat ini sedang ditutup.' }}</p>
                        <a href="/" class="inline-block bg-primary text-white font-bold px-8 py-3 rounded-xl shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all">Kembali ke Beranda</a>
                    </div>

                {{-- STEP 1: INFO PRIBADI --}}
                @elseif($step == 1)
                    <form action="{{ route('pendaftaran.postStep1') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap Siswa</label>
                                <input type="text" name="nama_calon" value="{{ session('registration_data.nama_calon') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="Masukkan nama lengkap...">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ session('registration_data.email') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="contoh@gmail.com">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" value="{{ session('registration_data.tanggal_lahir') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                                    <input type="text" name="no_hp" value="{{ session('registration_data.no_hp') }}" required 
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="0812...">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea name="alamat" rows="3" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="Alamat domisili saat ini...">{{ session('registration_data.alamat') }}</textarea>
                            </div>
                        </div>
                        <div class="mt-10">
                            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all">Lanjut ke Pilih Kelas</button>
                        </div>
                    </form>

                {{-- STEP 2: PILIH PROGRAM --}}
                @elseif($step == 2)
                    <form action="{{ route('pendaftaran.postStep2') }}" method="POST" id="form-step-2">
                        @csrf
                        <div class="space-y-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Program Kelas</label>
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($programKelas as $kelas)
                                    @php $selected = session('registration_data.id_program') == $kelas->id_program; @endphp
                                    <label class="class-option relative flex items-center p-5 rounded-2xl border-2 cursor-pointer transition-all {{ $selected ? 'selected' : 'border-gray-100 bg-white' }}">
                                        <input type="radio" name="id_program" value="{{ $kelas->id_program }}" class="hidden" required {{ $selected ? 'checked' : '' }} onchange="selectClass(this)">
                                        <div class="flex-1">
                                            <span class="block font-bold text-lg text-gray-900">{{ $kelas->nama_program }}</span>
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[10px] font-bold uppercase tracking-wider">{{ $kelas->kategori }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="block text-xl font-extrabold text-[#994D1C]">Rp {{ number_format($kelas->biaya, 0, ',', '.') }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-10 flex gap-4">
                            <a href="{{ route('pendaftaran.step1') }}" class="w-1/3 text-gray-500 font-bold py-4 rounded-xl border border-gray-200 text-center flex items-center justify-center hover:bg-gray-50 transition-all">Kembali</a>
                            <button type="submit" class="w-2/3 bg-primary text-white font-bold py-4 rounded-xl shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all">Lanjut ke Pembayaran</button>
                        </div>
                    </form>
                    <script>
                        function selectClass(el) {
                            document.querySelectorAll('.class-option').forEach(opt => opt.classList.remove('selected'));
                            el.closest('.class-option').classList.add('selected');
                            // Delay dikit biar keliatan animasinya sebelum submit otomatis
                            setTimeout(() => {
                                el.closest('form').submit();
                            }, 400);
                        }
                    </script>

                {{-- STEP 3: METODE PEMBAYARAN --}}
                @elseif($step == 3)
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 tracking-tight">Pilih Metode Pembayaran</h3>
                        <div class="grid grid-cols-1 gap-4 mb-6 text-left">
                            <label class="relative flex cursor-pointer rounded-2xl border-2 p-4 hover:bg-blue-50 transition-all border-gray-100 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="metode_pembayaran" value="transfer" class="sr-only" checked>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center"></div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900">Transfer / QRIS (Otomatis)</p>
                                        <p class="text-[10px] text-gray-500">Konfirmasi instan via Midtrans.</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-2xl border-2 p-4 hover:bg-orange-50 transition-all border-gray-100 has-[:checked]:border-[#994D1C] has-[:checked]:bg-orange-50">
                                <input type="radio" name="metode_pembayaran" value="tunai" class="sr-only">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-orange-100 text-[#994D1C] rounded-xl flex items-center justify-center"></div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900">Bayar Tunai</p>
                                        <p class="text-[10px] text-gray-500">Bayar langsung di Sanggar.</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="mt-10 flex gap-4">
                            <a href="{{ route('pendaftaran.step2') }}" class="w-1/3 text-gray-400 font-bold py-4 rounded-xl border border-gray-200 text-center flex items-center justify-center hover:bg-gray-50 transition-all">Kembali</a>
                            <button type="button" onclick="handleFinalSubmit()" id="btn-submit" class="w-2/3 bg-primary text-white font-bold py-4 rounded-xl shadow-lg hover:bg-[#7a3d16] transition-all">Selesaikan & Daftar</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} Sanggar Seni Goong Prasasti. All Rights Reserved.</p>
            <a href="/" class="mt-2 inline-block font-bold text-primary hover:underline transition-all">Kembali ke Beranda</a>
        </div>
    </div>

    {{-- Script Midtrans & SweetAlert2 --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function handleFinalSubmit() {
        const btn = document.getElementById('btn-submit');
        const radioMetode = document.querySelector('input[name="metode_pembayaran"]:checked');

        if (!radioMetode) {
            alert("Pilih metode pembayaran dulu ya!");
            return;
        }

        const metode = radioMetode.value;
        btn.innerHTML = "Memproses...";
        btn.disabled = true;

        fetch("{{ route('pendaftaran.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ metode_pembayaran: metode })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (metode === 'tunai') {
                    tampilkanModalSukses(data.nama_calon, data.username, 'tunai');
                } else {
                    fetch("/payment/token/" + data.id_pendaftaran)
                        .then(res => res.json())
                        .then(tokenData => {
                            if (tokenData.token) {
                                window.snap.pay(tokenData.token, {
                                    onSuccess: function(result) {
                                        tampilkanModalSukses(data.nama_calon, data.username, 'transfer');
                                    },
                                    onPending: function(result) {
                                        tampilkanModalSukses(data.nama_calon, data.username, 'pending');
                                    },
                                    onError: function(result) {
                                        alert("Pembayaran Gagal! Silakan coba lagi.");
                                        btn.innerHTML = "Selesaikan & Daftar";
                                        btn.disabled = false;
                                    },
                                    onClose: function() {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Pembayaran Dibatalkan',
                                        text: 'Kamu menutup halaman pembayaran. Silakan pilih metode dan coba lagi.',
                                        confirmButtonText: 'Kembali ke Form',
                                        confirmButtonColor: '#994D1C',
                                        allowOutsideClick: false,
                                    });
                                    btn.innerHTML = "Selesaikan & Daftar";
                                    btn.disabled = false;
                                }
                                });
                            }
                        });
                }
            } else {
                alert("Gagal: " + (data.message || "Terjadi kesalahan"));
                btn.innerHTML = "Selesaikan & Daftar";
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert("Server error, silakan coba lagi.");
            btn.innerHTML = "Selesaikan & Daftar";
            btn.disabled = false;
        });
    }

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg text-green-600"></i>';
            btn.style.transform = 'scale(1.1)';
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.transform = 'scale(1)';
            }, 1500);
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

    function tampilkanModalSukses(nama, username, metode) {
        const pesanMetode = metode === 'transfer'
            ? `<div class="status-badge success"><span>✅</span> <div>Pembayaran berhasil diterima. Menunggu verifikasi tim Humas.</div></div>`
            : metode === 'pending'
            ? `<div class="status-badge warning"><span>⏳</span> <div>Pembayaran pending. Tim Humas akan memverifikasi setelah pembayaran dikonfirmasi.</div></div>`
            : `<div class="status-badge info"><span>💵</span> <div>Silakan bayar tunai langsung ke sanggar. Tim Humas akan segera memverifikasi.</div></div>`;

        Swal.fire({
            width: 420,
            padding: '0',
            showConfirmButton: true,
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#994D1C',
            allowOutsideClick: false,
            customClass: {
                popup: 'custom-swal-popup',
                confirmButton: 'custom-swal-btn'
            },
            html: `
                <div class="modal-wrap">
                    <div class="modal-hero">
                        <span class="modal-celebrate-icon">🎉</span>
                        <h2>Pendaftaran Berhasil!</h2>
                        <p>Halo <strong>${nama}</strong>, pendaftaranmu sudah kami terima.</p>
                    </div>
                    <div class="modal-body">
                        ${pesanMetode}
                        
                        <div class="account-card">
                            <div class="account-card-header">
                                <span>Akun Anggota Baru</span>
                                <span class="badge-status-pending">Verifikasi</span>
                            </div>
                            <div class="account-card-body">
                                <div class="account-field">
                                    <span class="field-label">Username</span>
                                    <div class="field-value-group">
                                        <span class="field-value">${username}</span>
                                        <button type="button" class="btn-copy" onclick="copyToClipboard('${username}', this)" title="Salin Username">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="account-field">
                                    <span class="field-label">Password</span>
                                    <span class="password-masked">No. HP Kamu</span>
                                </div>
                            </div>
                        </div>

                        <p class="modal-note">Kamu bisa login setelah pendaftaran disetujui oleh Humas. Notifikasi akan dikirim ke emailmu.</p>
                    </div>
                </div>
            `
        }).then(() => {
            window.location.href = "/";
        });
    }
    </script>
</body>
</html>
