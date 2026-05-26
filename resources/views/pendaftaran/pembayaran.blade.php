@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<div class="min-h-screen bg-[#FDF7F2] py-12 flex items-center justify-center">
    <div class="w-full max-w-[450px] bg-white rounded-[32px] shadow-2xl overflow-hidden border border-orange-100">

        <div class="bg-[#994D1C] p-8 text-center text-white">
            <h2 class="text-xl font-bold tracking-tight uppercase">Metode Pembayaran</h2>
            <p class="text-[10px] opacity-80 mt-1 tracking-widest">SANGGAR GOONG PRASASTI</p>
        </div>

        <div class="p-8">
            <div class="bg-orange-50 rounded-2xl p-6 mb-6 text-center border border-orange-100">
                <p class="text-[10px] text-orange-600 font-bold uppercase mb-1">Total Tagihan</p>
                <h3 class="text-3xl font-extrabold text-[#994D1C]">
                    Rp {{ number_format($pendaftaran->programKelas->biaya, 0, ',', '.') }}
                </h3>
            </div>

            <div id="snap-container" class="min-h-[400px] bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <div class="flex flex-col items-center justify-center h-full py-20">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#994D1C] mb-4"></div>
                    <p class="text-xs text-gray-400">Menghubungkan ke Midtrans...</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT MIDTRANS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    window.onload = function() {
        fetch('/payment/token/{{ $pendaftaran->id_pendaftaran }}')
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Pakai window.snap.embed supaya nempel di dalam div
                    window.snap.embed(data.token, {
                        embedId: 'snap-container',
                        onSuccess: function(result) { window.location.href = "{{ route('dashboard.siswa') }}"; },
                        onPending: function(result) { window.location.href = "{{ route('dashboard.siswa') }}"; },
                        onError: function(result) { alert("Gagal!"); location.reload(); }
                    });
                }
            });
    };
</script>
@endsection
