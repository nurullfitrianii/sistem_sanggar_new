@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Daftar Pengguna Staf</h1>
    <a href="{{ route('pengguna.create') }}" class="btn btn-sm btn-outline-success">Tambah Staf Baru</a>
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
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengguna as $item)
                    <tr>
                        <td>{{ $item->username }}</td>
                        <td>{{ $item->role }}</td>
                        <td>
                            @if($item->status === 'Aktif')
                                <span class="badge bg-success px-3 rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-danger px-3 rounded-pill">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('pengguna.edit', $item->id_user) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form autocomplete="off" action="{{ route('pengguna.destroy', $item->id_user) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data staf.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $pengguna->links() }}
        </div>
    </div>
</div>
@endsection
