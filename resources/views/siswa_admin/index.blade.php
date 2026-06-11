@extends('layouts.admin')

@section('title', 'Manajemen Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Daftar Siswa Terdaftar</h1>
    <a href="{{ route('siswa-admin.create') }}" class="btn btn-sm btn-outline-success">Tambah Siswa Baru</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card section-card">
    <div class="card-body">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $item)
                    <tr>
                        <td class="">{{ $item->username }}</td>
                        <td>{{ $item->email ?? '-' }}</td>
                        <td>
                            @if($item->status === 'Aktif')
                                <span class="badge bg-success px-3 rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-danger px-3 rounded-pill">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('siswa-admin.edit', $item->id_user) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form autocomplete="off" action="{{ route('siswa-admin.destroy', $item->id_user) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus siswa ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $siswa->links() }}
        </div>
    </div>
</div>
@endsection
