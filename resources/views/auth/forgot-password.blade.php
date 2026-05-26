<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sanggar Goong Prasasti</title>
    <!-- Plus Jakarta Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .text-chocolate { color: #8B4513; }
        .bg-chocolate { background-color: #8B4513; }
        .border-chocolate { border-color: #8B4513; }
        .focus-ring-chocolate:focus { 
            --tw-ring-color: rgba(139, 69, 19, 0.25);
            border-color: #8B4513; 
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
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#fff6ed] rounded-full mb-5 shadow-sm border border-[#f5e6d3]">
                <span class="text-3xl">🎭</span>
            </div>
            <h1 class="text-[1.4rem] font-bold text-[#8B4513] mb-1.5 tracking-tight">Sanggar Goong Prasasti</h1>
            <p class="text-sm text-[#94A3B8] font-medium">Sistem Informasi Sanggar</p>
        </div>

        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-[#8B4513] mb-2">Lupa Password?</h2>
            <p class="text-sm text-[#94A3B8] font-medium">Masukkan email kamu dan kami akan kirimkan link untuk atur ulang password.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            
            @if(session('status'))
                <div class="bg-green-50 text-green-700 text-sm p-4 rounded-2xl mb-5 text-center font-medium border border-green-100">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any() || session('error'))
                <div class="bg-red-50 text-red-600 text-sm p-4 rounded-2xl mb-5 text-center font-medium border border-red-100">
                    {{ $errors->first() ?: session('error') }}
                </div>
            @endif

            <!-- Input field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-[#8B4513] mb-2">Masukkan Email Anda</label>
                <input type="email" name="email" id="email" placeholder="nama@email.com" required autofocus
                       class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-[#94A3B8] outline-none transition-all duration-200 focus-ring-chocolate" value="{{ old('email') }}">
            </div>

            <!-- Pill-shaped button with hover lift -->
            <button type="submit" class="w-full py-3.5 bg-[#8B4513] hover:bg-[#70350d] text-white font-semibold rounded-full transition-all duration-200 hover:-translate-y-0.5 shadow-[0_6px_20px_-4px_rgba(139,69,19,0.4)] mt-4">
                Kirim Link Reset
            </button>
            
        </form>

        <!-- Footer / Back Link -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-sm text-[#94A3B8]">
            <a href="{{ route('login') }}" class="font-bold text-[#8B4513] hover:text-[#70350d] hover:underline transition-colors flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>