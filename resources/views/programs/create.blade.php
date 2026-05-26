@extends('layouts.admin')

@section('title', 'Tambah Program')

@section('content')
<h1 class="h4 mb-3">Tambah Program Sanggar</h1>

<form action="{{ route('program-admin.store') }}" method="POST" enctype="multipart/form-data" class="card section-card p-4 p-md-5">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="mb-4">
                <label class="form-label fw-bold">Nama Program</label>
                <input type="text" name="nama_program" id="nama_program" value="{{ old('nama_program') }}"
                       class="form-control form-control-lg @error('nama_program') is-invalid @enderror" 
                       placeholder="Contoh: Kelas Tari Tradisional" required>
                @error('nama_program')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-4">
                <label class="form-label fw-bold">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                       class="form-control form-control-lg @error('slug') is-invalid @enderror" 
                       placeholder="url-program-otomatis" required readonly>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label fw-bold">Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori') }}"
                       class="form-control @error('kategori') is-invalid @enderror" 
                       placeholder="Contoh: Tari, Musik, atau Teater" required>
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label fw-bold">Biaya Pendaftaran</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">Rp</span>
                    <input type="number" name="biaya" value="{{ old('biaya') }}"
                           class="form-control border-start-0 @error('biaya') is-invalid @enderror" 
                           placeholder="0" required>
                </div>
                @error('biaya')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label fw-bold">Durasi per Sesi (Opsional)</label>
                <input type="text" name="durasi" value="{{ old('durasi') }}"
                       class="form-control @error('durasi') is-invalid @enderror" 
                       placeholder="Contoh: 2 jam per sesi">
                @error('durasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label fw-bold">Jumlah Sesi (Opsional)</label>
                <input type="text" name="jumlah_sesi" value="{{ old('jumlah_sesi') }}"
                       class="form-control @error('jumlah_sesi') is-invalid @enderror" 
                       placeholder="Contoh: 4 sesi per bulan">
                @error('jumlah_sesi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Cover Gambar</label>
        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
        <div class="form-text">Rekomendasi ukuran 800x600px. Max 2MB.</div>
        @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Deskripsi Program</label>
        <textarea name="deskripsi" rows="5"
                  class="form-control @error('deskripsi') is-invalid @enderror" 
                  placeholder="Jelaskan detail kegiatan dalam program ini..." required>{{ old('deskripsi') }}</textarea>
        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Status Keaktifan</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>Aktif (Tampilkan di Pendaftaran)</option>
            <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="text-end">
        <button type="submit" class="btn px-5 py-3 btn-outline-success">
            Simpan Program
        </button>
    </div>
</form>

@push('scripts')
<script>
    const nama_program = document.querySelector('#nama_program');
    const slug = document.querySelector('#slug');
    nama_program.addEventListener('change', function() {
        const slugText = nama_program.value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
        slug.value = slugText;
    });
</script>
@endpush
@endsection

