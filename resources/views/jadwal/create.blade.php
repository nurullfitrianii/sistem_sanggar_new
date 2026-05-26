@extends('layouts.admin')

@section('title', 'Tambah Jadwal Kelas')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3 fw-bold text-dark">Tambah Jadwal Kelas</h1>

    <form action="{{ route('jadwal.store') }}" method="POST" class="card border-0 shadow-sm p-4 rounded-4">
        @csrf

        {{-- Pilihan Program Kelas --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Kelas / Program</label>
            <select name="id_program" class="form-select @error('id_program') is-invalid @enderror" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id_program }}" {{ old('id_program') == $k->id_program ? 'selected' : '' }}>
                        {{ $k->nama_program }}
                    </option>
                @endforeach
            </select>
            @error('id_program')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Pilihan Pelatih --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Pelatih</label>
            <select name="id_pelatih" class="form-select @error('id_pelatih') is-invalid @enderror" required>
                @foreach($pelatih as $p)
                    <option value="{{ $p->id_pelatih }}" {{ (old('id_pelatih') == $p->id_pelatih || $loop->first) ? 'selected' : '' }}>
                        {{ $p->nama_pelatih }}
                    </option>
                @endforeach
            </select>
            @error('id_pelatih')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Pilihan Lokasi Sanggar --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Tempat / Sanggar</label>
            <select name="id_sanggar" class="form-select @error('id_sanggar') is-invalid @enderror" required>
                @foreach($sanggar as $s)
                    <option value="{{ $s->id_sanggar }}" {{ (old('id_sanggar') == $s->id_sanggar || $loop->first) ? 'selected' : '' }}>
                        {{ $s->nama_sanggar }}
                    </option>
                @endforeach
            </select>
            @error('id_sanggar')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Hari</label>
            <select name="hari" class="form-select @error('hari') is-invalid @enderror" required>
                @foreach(['Sabtu','Minggu'] as $hari)
                    <option value="{{ $hari }}" {{ old('hari') === $hari ? 'selected' : '' }}>
                        {{ $hari }}
                    </option>
                @endforeach
            </select>
            @error('hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Jam Mulai</label>
                <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}"
                       class="form-control @error('jam_mulai') is-invalid @enderror" required>
                @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Jam Selesai</label>
                <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}"
                       class="form-control @error('jam_selesai') is-invalid @enderror" required>
                @error('jam_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Keterangan Lokasi</label>
            <input type="text" name="lokasi" value="Sanggar Goong Prasasti" readonly
                   class="form-control bg-light @error('lokasi') is-invalid @enderror">
            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Reminder / Materi Latihan</label>
            <textarea name="materi" rows="3" class="form-control @error('materi') is-invalid @enderror" 
                      placeholder="Contoh: Minggu ini gerak tari, minggu berikutnya gerak tangan">{{ old('materi') }}</textarea>
            <small class="text-muted">Informasi ini akan muncul di dashboard siswa sebagai reminder latihan.</small>
            @error('materi')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mt-3">
            <button type="submit" class="btn px-4 btn-outline-success">Simpan Jadwal</button>
            <a href="{{ route('jadwal.index') }}" class="btn px-4 btn-outline-primary">Batal</a>
        </div>
    </form>
</div>
@endsection
