@extends('layouts.admin')

@section('title', 'Berita (Admin)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0 fw-bold">Berita Sanggar</h1>
    <a href="{{ route('berita-admin.create') }}" class="btn btn-sm btn-outline-success">Tambah Berita</a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3">{{ session('success') }}</div>
@endif

<div class="card rounded-4 overflow-hidden section-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Judul</th>
                        <th>Tanggal Publish</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $post->judul }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($post->tanggal_publish)->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('berita-admin.edit', $post->id_informasi) }}" class="btn btn-sm rounded-pill px-3 btn-outline-warning">Edit</a>
                                <form action="{{ route('berita-admin.destroy', $post->id_informasi) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus berita ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm rounded-pill px-3 btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">Belum ada berita.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

