@extends('layouts.admin')

@section('title', 'Galeri (Admin)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Galeri Kegiatan</h1>
    <a href="{{ route('galeri-admin.create') }}" class="btn btn-sm btn-outline-success">Tambah Galeri</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card section-card">
    <div class="card-body">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($galleries as $galeri)
                    <tr>
                        <td>{{ $galeri->judul }}</td>
                        <td>{{ $galeri->tanggal }}</td>
                        <td class="text-end">
                            <a href="{{ route('galeri-admin.edit', $galeri) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form action="{{ route('galeri-admin.destroy', $galeri) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus item galeri ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada data galeri.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $galleries->links() }}
        </div>
    </div>
</div>
@endsection

