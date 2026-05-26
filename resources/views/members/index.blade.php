@extends('layouts.admin')

@section('title', 'Manajemen Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Daftar Pengguna Sistem</h1>
    <a href="{{ route('members.create') }}" class="btn btn-sm btn-outline-success">Tambah Admin Baru</a>
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
                @forelse($members as $m)
                    <tr>
                        <td class="">{{ $m->username }}</td>
                        <td>
                            @if(strtolower($m->role) === 'ketua' || strtolower($m->role) === 'bendahara' || strtolower($m->role) === 'humas')
                                <span class="badge bg-primary px-3 rounded-pill">{{ $m->role }}</span>
                            @else
                                <span class="badge bg-secondary px-3 rounded-pill">{{ $m->role ?? 'Siswa' }}</span>
                            @endif
                        </td>
                        <td>
                            @if($m->status === 'Aktif')
                                <span class="badge bg-success px-3 rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-danger px-3 rounded-pill">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('members.edit', $m->id_user) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form action="{{ route('members.destroy', $m->id_user) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus user ini?');">
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
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection

