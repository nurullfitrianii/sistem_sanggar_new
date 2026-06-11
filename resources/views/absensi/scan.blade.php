@extends('layouts.admin')

@section('title', 'Scan QR Code Absensi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
        <div class="card section-card shadow-lg overflow-hidden" style="border-radius: 30px;">
            <div class="card-header py-5 text-center" style="background: linear-gradient(135deg, var(--sanggar-primary), var(--sanggar-secondary)); border-radius: 0 0 40px 40px;">
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                    <i class="bi bi-qr-code-scan fs-1" style="color: var(--sanggar-primary)"></i>
                </div>
                <h3 class="fw-bold text-white mb-1">Scan Kehadiran</h3>
                <p class="text-white opacity-75 small mb-0">Arahkan QR Code Siswa ke Kamera</p>
            </div>

            <div class="card-body p-4 p-md-5">
                {{-- Bagian Pilih Kamera --}}
                <div class="mb-4" id="camera-controls">
                    <label class="form-label fw-bold text-dark">Pilih Kamera</label>
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                        <select id="camera-select" class="form-select border-0 py-2" style="background-color: #f8f9fa;"></select>
                        <button id="refresh-cameras" class="btn px-4 btn-outline-primary" type="button" style="background: var(--sanggar-primary)">Lihat</button>
                    </div>
                    <div id="camera-warning" class="text-danger small d-none mt-2 fw-semibold">
                        Kamera hardware tidak terdeteksi.
                    </div>
                </div>

                {{-- Area Scanner --}}
                <div class="position-relative">
                    <div id="reader" class="rounded-4 border-0 shadow-sm overflow-hidden" style="background: #fdfcfb; min-height: 300px;"></div>
                </div>

                {{-- Area Feedback Berhasil/Gagal --}}
                <div id="result" class="mt-4 p-3 rounded-4 text-center d-none animate__animated animate__fadeIn"></div>
            </div>

            <div class="card-footer bg-white py-4 text-center">
                @if(auth()->user()->role === 'Ketua')
                    <a href="{{ route('laporan.absensi') }}" class="btn rounded-pill px-5 py-2 fw-semibold btn-outline-primary">
                        Kembali
                    </a>
                @elseif(auth()->user()->role === 'Humas')
                    <a href="{{ route('humas.absensi.index') }}" class="btn rounded-pill px-5 py-2 fw-semibold btn-outline-primary">
                        Kembali
                    </a>
                @else
                    <a href="javascript:history.back()" class="btn rounded-pill px-5 py-2 fw-semibold btn-outline-primary">
                        Kembali
                    </a>
                @endif
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">
                Sistem Absensi Sanggar Goong Prasasti
            </p>
        </div>
    </div>
</div>

<style>
    #reader { border: none !important; }
    #reader video { border-radius: 20px !important; object-fit: cover !important; }
    #reader__scan_region { background: transparent !important; }
    /* Mempercantik tombol start/stop bawaan library jika muncul */
    #reader__dashboard_section_csr button {
        background: linear-gradient(135deg, var(--sanggar-primary), var(--sanggar-secondary)) !important;
        border: none !important;
        color: white !important;
        border-radius: 50px !important;
        padding: 10px 25px !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    .result-success { background: #f0fdf4; color: #166534; border: 1px solid #bbfcce; }
    .result-error { background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }
    .result-pending { background: #fffbeb; color: #92400e; border: 1px solid #fef3c7; }
</style>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let html5QrCode;
    let isScanning = false;
    const cameraSelect = document.getElementById('camera-select');
    const refreshBtn = document.getElementById('refresh-cameras');
    const cameraWarning = document.getElementById('camera-warning');

    html5QrCode = new Html5Qrcode("reader");

    function startScanner(cameraId) {
        if (isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                initScanner(cameraId);
            }).catch(err => console.error(err));
        } else {
            initScanner(cameraId);
        }
    }

    function initScanner(cameraId) {
        html5QrCode.start(
            cameraId,
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanFailure
        ).then(() => {
            isScanning = true;
        }).catch(err => console.error(err));
    }

    function fetchCameras() {
        Html5Qrcode.getCameras().then(devices => {
            cameraSelect.innerHTML = '';
            if (devices && devices.length) {
                let selectedId = devices[0].id;
                devices.forEach(device => {
                    let option = document.createElement('option');
                    option.value = device.id;
                    option.text = device.label || `Camera ${cameraSelect.length + 1}`;
                    cameraSelect.appendChild(option);

                    // Prioritas kamera asli (Integrated/Back)
                    let lbl = option.text.toLowerCase();
                    if (lbl.includes('integrated') || lbl.includes('back') || lbl.includes('facetime')) {
                        selectedId = device.id;
                    }
                });
                cameraSelect.value = selectedId;
                startScanner(selectedId);
            }
        }).catch(err => cameraWarning.classList.remove('d-none'));
    }

    // Tombol refresh untuk paksa browser minta izin kamera
    refreshBtn.addEventListener('click', () => {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                stream.getTracks().forEach(track => track.stop());
                fetchCameras();
            })
            .catch(err => alert("Izin kamera ditolak!"));
    });

    cameraSelect.addEventListener('change', (e) => startScanner(e.target.value));

    function onScanSuccess(decodedText, decodedResult) {
    if (isScanning) {
        // Langsung proses tanpa stop kamera dulu
        isScanning = false;
        processScan(decodedText);
    }
}

    function processScan(decodedText) {
    let resultEl = document.getElementById('result');
    resultEl.classList.remove('d-none', 'animate__fadeOut', 'animate__animated');
    resultEl.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div> Memproses...';
    resultEl.className = 'mt-4 p-3 rounded-4 text-center result-pending';

    axios.post('{{ route('absensi.scan') }}', {
        id_user: decodedText,
        _token: '{{ csrf_token() }}'
    })
    .then(function (response) {
        if(response.data.status === 'success') {
            // NOTIF CEKLIS HIJAU BERHASIL
            resultEl.innerHTML = `
                <div class="animate__animated animate__bounceIn">
                    <span class="fw-bold">${response.data.message}</span>
                </div>
            `;
            resultEl.className = 'mt-4 p-3 rounded-4 text-center result-success border-success border-2 shadow-sm';
        } else if (response.data.status === 'warning') {
            // NOTIF WARNING KUNING SUDAH ABSEN
            resultEl.innerHTML = `
                <div class="animate__animated animate__bounceIn">
                    <span class="fw-bold">${response.data.message}</span>
                </div>
            `;
            resultEl.className = 'mt-4 p-3 rounded-4 text-center result-pending border-warning border-2 shadow-sm';
        } else {
            // NOTIF SILANG MERAH GAGAL
            resultEl.innerHTML = `
                <div class="animate__animated animate__shakeX">
                    <span class="fw-bold">${response.data.message}</span>
                </div>
            `;
            resultEl.className = 'mt-4 p-3 rounded-4 text-center result-error border-danger border-2 shadow-sm';
        }

        // TUNGGU 1.5 DETIK TERUS BALIKIN KAMERA (GAK USAH REFRESH)
        setTimeout(() => {
            resultEl.classList.add('animate__animated', 'animate__fadeOut');
            setTimeout(() => {
                resultEl.classList.add('d-none');
                resultEl.classList.remove('animate__fadeOut', 'animate__animated');
                startScanner(cameraSelect.value); // Nyalain kamera lagi buat scan selanjutnya
            }, 500);
        }, 1500);
    })
    .catch(function (error) {
        resultEl.innerHTML = `
            <div class="animate__animated animate__shakeX">
                <span class="fw-bold">Error Koneksi!</span>
            </div>
        `;
        resultEl.className = 'mt-4 p-3 rounded-4 text-center result-error border-danger border-2 shadow-sm';
        setTimeout(() => {
            resultEl.classList.add('animate__animated', 'animate__fadeOut');
            setTimeout(() => {
                resultEl.classList.add('d-none');
                resultEl.classList.remove('animate__fadeOut', 'animate__animated');
                startScanner(cameraSelect.value);
            }, 500);
        }, 2000);
    });
}
    function onScanFailure(error) {}

    fetchCameras();
</script>
@endpush
@endsection
