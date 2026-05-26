<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sanggar Goong Prasasti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-amber-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-700 to-amber-900 rounded-2xl flex items-center justify-center shadow-lg">
                    <span class="text-white text-3xl">🎭</span>
                </div>
                <div class="text-left">
                    <h1 class="text-2xl font-bold text-gray-900">Sanggar Goong Prasasti</h1>
                    <p class="text-sm text-gray-500">Sistem Informasi Sanggar</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            <h2 class="text-2xl font-bold text-center mb-2 text-gray-800">Masuk</h2>
            <p class="text-center text-gray-500 mb-8 text-sm">Masukkan kredensial Anda untuk mengakses sistem</p>

            @if($errors->any() || session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Login',
                            text: '{{ $errors->first() ?: session('error') }}',
                            confirmButtonColor: '#B45309',
                            confirmButtonText: 'Coba Lagi'
                        });
                    });
                </script>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email / Username</label>
                    <input type="text" name="login" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-600 outline-none transition-all" placeholder="nama@email.com atau username" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-600 outline-none transition-all" placeholder="••••••••" required>
                </div>

                <button type="submit" class="w-full bg-amber-700 hover:bg-amber-800 text-white font-bold py-3.5 rounded-xl transition duration-300 shadow-md active:scale-95">
                    Masuk
                </button>
            </form>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-gray-100"></span></div>
                <div class="relative flex justify-center text-xs uppercase tracking-widest"><span class="px-4 bg-white text-gray-400 font-bold">Atau</span></div>
            </div>

            <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 border border-gray-200 py-3.5 rounded-xl hover:bg-gray-50 transition-all font-bold text-gray-700 shadow-sm active:scale-95">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="h-5" alt="Google Logo">
                Masuk dengan Google
            </a>

            <p class="text-center text-sm text-gray-500 mt-10">
                Belum menjadi siswa? <a href="/#pendaftaran" class="text-amber-700 font-bold hover:underline">Daftar Les Sanggar</a>
            </p>
        </div>

        <p class="mt-8 text-center text-gray-400 text-xs italic">
            &copy; {{ date('Y') }} Sanggar Goong Prasasti. Seluruh hak cipta dilindungi.
        </p>
    </div>
</body>
</html>
