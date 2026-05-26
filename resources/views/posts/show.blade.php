@extends('layouts.app')

@section('title', $post->judul . ' | Berita Sanggar')

@section('content')
<section class="py-5 mt-4">
    <div class="container">
        <p class="section-title mb-2">Berita</p>
        <h1 class="h3 mb-3">{{ $post->judul }}</h1>
        <p class="small text-muted mb-4">
            Dipublikasikan sebagai contoh format berita resmi sanggar.
            Seluruh isi teks ini bersifat dummy dan dapat diganti.
        </p>
        <div class="row gy-4">
            <div class="col-lg-8">
                @if($post->foto)
                    <img src="{{ Storage::url($post->foto) }}" class="img-fluid rounded-3 mb-3"
                         alt="Ilustrasi berita {{ $post->judul }}">
                @endif
                <article class="prose">
                    {!! nl2br(e($post->isi)) !!}
                </article>
            </div>
        </div>
    </div>
</section>
@endsection

