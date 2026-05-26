@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Manajemen Kelas</h1>
    <a href="{{ route('kelas.create') }}" class="btn btn-sm btn-outline-success">Tambah Kelas</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card section-card">
    <div class="card-body">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama Program</th>
                    <th>Kategori</th>
                    <th>Biaya</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $item)
                    <tr>
                        <td>{{ $item->nama_program }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>Rp {{ number_format($item->biaya, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status === 'Aktif' ? 'success' : 'secondary' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('kelas.edit', $item->id_program) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form action="{{ route('kelas.destroy', $item->id_program) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus data program ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data program kelas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $kelas->links() }}
        </div>
    </div>
</div>
@endsection

