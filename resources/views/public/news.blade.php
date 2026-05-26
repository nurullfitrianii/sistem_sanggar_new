@extends('layouts.app')

@section('title', 'Berita | Sanggar Budaya Nusantara')

@section('content')
<section class="py-5 mt-4">
    <div class="container">
        <p class="section-title mb-2">Berita</p>
        <h1 class="h3 mb-4">Informasi dan Publikasi</h1>
        <p class="text-muted mb-4">
            Berita pada laman ini berfungsi sebagai contoh saluran komunikasi formal sanggar kepada siswa, orang tua,
            dan mitra. Seluruh isi berita bersifat dummy dan dapat diganti dengan informasi resmi yang aktual.
        </p>
        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-6">
                    <article class="card h-100 border-0 shadow-sm">
                        @if($post->gambar)
                            <img src="{{ $post->gambar }}" class="card-img-top"
                                 alt="Ilustrasi berita {{ $post->judul }}">
                        @endif
                        <div class="card-body">
                            <h2 class="h5 card-title">{{ $post->judul }}</h2>
                            @if($post->ringkasan)
                                <p class="card-text small text-muted">{{ $post->ringkasan }}</p>
                            @endif
                            <a href="{{ route('public.posts.show', $post->slug) }}" class="small text-decoration-none">
                                Baca selengkapnya →
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">
                        Belum terdapat berita yang dipublikasikan. Silakan gunakan panel admin untuk menambah berita.
                    </p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</section>
@endsection

