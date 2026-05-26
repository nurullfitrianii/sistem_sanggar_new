<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sanggar Goong Prasasti</title>
    <!-- Plus Jakarta Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .text-chocolate { color: #A47251; }
        .bg-chocolate { background-color: #A47251; }
        .border-chocolate { border-color: #A47251; }
        .focus-ring-chocolate:focus { 
            --tw-ring-color: rgba(164, 114, 81, 0.25);
            border-color: #A47251; 
            box-shadow: 0 0 0 4px var(--tw-ring-color); 
        }
    </style>
</head>
<body class="bg-[#FAFAFA] min-h-screen flex items-center justify-center p-4 text-[#333333]">
    <!-- Soft-clay drop shadow, extra rounded corners -->
    <div class="w-full max-w-md bg-white rounded-[1.5rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] p-10 border border-gray-100">
        
        <!-- Header Section -->
        <div class="text-center mb-8">
            <!-- Logo Placeholder -->
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-5 shadow-sm border border-gray-100 p-2">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-[1.4rem] font-bold text-[#A47251] mb-1.5 tracking-tight">Sanggar Goong Prasasti</h1>
            <p class="text-sm text-[#94A3B8] font-medium">Sistem Informasi Sanggar</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf
            
            @if($errors->any() || session('error'))
                <div class="bg-red-50 text-red-600 text-sm p-4 rounded-2xl mb-5 text-center font-medium border border-red-100">
                    {{ $errors->first() ?: session('error') }}
                </div>
            @endif

            <!-- Input fields -->
            <div>
                <input type="text" name="username" placeholder="Email / Username" required
                       class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-[#94A3B8] outline-none transition-all duration-200 focus-ring-chocolate">
            </div>

            <div class="relative">
                <input type="password" id="password" name="password" placeholder="Password" required
                       class="w-full pl-5 pr-12 py-3.5 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-[#94A3B8] outline-none transition-all duration-200 focus-ring-chocolate">
                
                <!-- Toggle Password Button -->
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#94A3B8] hover:text-[#A47251] transition-colors focus:outline-none">
                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <!-- Pill-shaped button with hover lift -->
            <button type="submit" class="w-full py-3.5 bg-[#A47251] hover:bg-[#8e6245] text-white font-semibold rounded-full transition-all duration-200 hover:-translate-y-0.5 shadow-[0_6px_20px_-4px_rgba(164,114,81,0.4)] mt-4">
                Masuk
            </button>
            
            <!-- Lupa password? link -->
            <div class="text-center mt-5">
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#A47251] hover:text-[#8e6245] hover:underline transition-colors">Lupa password?</a>
            </div>
        </form>

        <!-- Separator -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-4 bg-white text-[#94A3B8] font-bold tracking-widest">ATAU</span>
            </div>
        </div>

        <!-- Google Login -->
        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 px-4 py-3.5 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 hover:-translate-y-0.5 shadow-sm group">
            <svg class="h-5 w-5" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            <span class="font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">Masuk dengan Google</span>
        </a>

        <!-- Footer -->
        <div class="mt-10 text-center text-sm text-[#94A3B8]">
            Belum punya akun? <a href="{{ route('pendaftaran.step1') }}" class="font-bold text-[#A47251] hover:text-[#8e6245] hover:underline transition-colors">Daftar sekarang</a>
        </div>
    </div>

    <!-- Toggle Password Script -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Toggle type
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle icon (eye open/closed)
            if (type === 'text') {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });
    </script>
</body>
</html>
