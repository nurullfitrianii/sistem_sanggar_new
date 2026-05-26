<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sanggar Goong Prasasti</title>
    <!-- Plus Jakarta Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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

        <div class="mb-6 text-center">
            <h2 class="text-lg font-semibold text-gray-800">Reset Password</h2>
            <p class="text-sm text-[#94A3B8] mt-1">Masukkan password baru untuk akun Anda.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            
            <!-- Hidden Token Field -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', request()->email) }}" placeholder="Alamat Email" required readonly
                       class="w-full px-5 py-3.5 bg-[#F8FAFC] border border-gray-200 rounded-xl text-gray-900 placeholder-[#94A3B8] outline-none transition-all duration-200 focus-ring-chocolate cursor-not-allowed">
                @error('email')
                    <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                <input type="password" id="password" name="password" placeholder="Password Baru" required
                       class="w-full px-5 py-3.5 bg-[#F8FAFC] border border-gray-200 rounded-xl text-gray-900 placeholder-[#94A3B8] outline-none transition-all duration-200 focus-ring-chocolate">
                @error('password')
                    <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password Baru" required
                       class="w-full px-5 py-3.5 bg-[#F8FAFC] border border-gray-200 rounded-xl text-gray-900 placeholder-[#94A3B8] outline-none transition-all duration-200 focus-ring-chocolate">
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pill-shaped button -->
            <button type="submit" class="w-full py-3.5 bg-[#8B4513] hover:bg-[#70350d] text-white font-semibold rounded-full transition-all duration-200 hover:-translate-y-0.5 shadow-[0_6px_20px_-4px_rgba(139,69,19,0.4)] mt-6">
                Perbarui Password
            </button>
            
            <!-- Kembali ke Login link -->
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#8B4513] hover:text-[#70350d] hover:underline transition-colors">
                    Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</body>
</html>
