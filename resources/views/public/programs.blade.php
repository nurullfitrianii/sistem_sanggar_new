@extends('layouts.app')

@section('title', 'Program | Sanggar Budaya Nusantara')

@section('content')
<section class="py-5 mt-4">
    <div class="container">
        <p class="section-title mb-2">Program</p>
        <h1 class="h3 mb-4">Rangkaian Program Latihan</h1>
        <p class="text-muted mb-4">
            Daftar program pada halaman ini disusun sebagai contoh susunan kurikulum sanggar seni tradisional.
            Anda dapat menambah, mengurangi, atau memodifikasi seluruh data sesuai dengan program resmi yang dijalankan.
        </p>
        <div class="row g-4">
            @forelse($programs as $program)
                <div class="col-md-4">
                    <article class="program-card p-4 bg-white h-100">
                        <h2 class="h5 mb-2">{{ $program->judul }}</h2>
                        <p class="small text-muted mb-3">
                            {{ \Illuminate\Support\Str::limit($program->deskripsi, 180) }}
                        </p>
                        @if($program->gambar)
                            <img src="{{ $program->gambar }}" class="img-fluid rounded-3"
                                 alt="Ilustrasi program {{ $program->judul }}">
                        @endif
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">
                        Belum ada data program yang ditambahkan. Silakan gunakan panel admin untuk mengelola program.
                    </p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $programs->links() }}
        </div>
    </div>
</section>
@endsection

