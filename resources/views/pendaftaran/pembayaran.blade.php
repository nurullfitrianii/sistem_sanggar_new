@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="min-h-screen bg-[#FDF7F2] py-12 flex items-center justify-center px-4">
    <div class="w-full max-w-[450px] bg-white rounded-[32px] shadow-2xl overflow-hidden border border-orange-100">

        <div class="bg-[#994D1C] p-8 text-center text-white">
            <h2 class="text-xl font-bold tracking-tight uppercase">Pembayaran Pendaftaran</h2>
            <p class="text-[10px] opacity-80 mt-1 tracking-widest">SANGGAR GOONG PRASASTI</p>
        </div>

        <div class="p-8">
            <div class="bg-orange-50 rounded-2xl p-6 mb-6 text-center border border-orange-100">
                <p class="text-[10px] text-orange-600 font-bold uppercase mb-1">Total Tagihan</p>
                <h3 class="text-3xl font-extrabold text-[#994D1C]">
                    Rp {{ number_format($pendaftaran->programKelas->biaya, 0, ',', '.') }}
                </h3>
            </div>

            <!-- QRIS Section -->
            <div class="text-center mb-6">
                <p class="text-xs text-orange-600 font-bold uppercase mb-2">Scan QRIS untuk Bayar</p>
                <img src="{{ asset('img/qris.jpeg') }}" alt="QRIS Sanggar" class="w-56 mx-auto rounded-2xl shadow-lg border border-gray-200 mb-2">
                <p class="text-[10px] text-gray-500">Silakan scan QRIS di atas melalui aplikasi e-wallet atau mobile banking pilihan Anda.</p>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('pendaftaran.uploadBukti', $pendaftaran->id_pendaftaran) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Unggah Bukti Pembayaran (Maks. 2MB)</label>
                    <div class="relative flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-2xl p-6 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer group">
                        <input type="file" name="bukti_bayar" accept="image/*,application/pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelected(this)">
                        <div class="text-center" id="file-upload-placeholder">
                            <i class="bi bi-cloud-arrow-up text-4xl text-gray-400 group-hover:text-[#994D1C] transition-colors"></i>
                            <p class="mt-2 text-sm font-bold text-gray-700">Pilih bukti pembayaran</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, PDF (Maks. 2MB)</p>
                        </div>
                        <div class="hidden text-center" id="file-upload-info">
                            <i class="bi bi-file-earmark-check text-4xl text-emerald-500"></i>
                            <p class="mt-2 text-sm font-bold text-gray-800" id="selected-file-name">Nama berkas...</p>
                            <p class="text-xs text-emerald-600 mt-1 flex items-center justify-center gap-1">
                                <i class="bi bi-check-circle-fill"></i> Bukti siap diunggah
                            </p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#994D1C] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#7a3d16] transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-send-fill"></i> Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function handleFileSelected(input) {
        const placeholder = document.getElementById('file-upload-placeholder');
        const info = document.getElementById('file-upload-info');
        const nameSpan = document.getElementById('selected-file-name');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            if (file.size > 2 * 1024 * 1024) {
                alert('Maksimal ukuran berkas adalah 2MB.');
                input.value = '';
                placeholder.classList.remove('hidden');
                info.classList.add('hidden');
                return;
            }
            
            nameSpan.textContent = file.name;
            placeholder.classList.add('hidden');
            info.classList.remove('hidden');
        } else {
            placeholder.classList.remove('hidden');
            info.classList.add('hidden');
        }
    }
</script>
@endsection
