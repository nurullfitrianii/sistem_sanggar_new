@extends('layouts.admin')

@section('title', 'Program (Admin)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Program Sanggar</h1>
    <a href="{{ route('program-admin.create') }}" class="btn btn-sm btn-outline-success">Tambah Program</a>
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
                    <th>Slug</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $program->nama_program }}</div>
                            <div class="small text-muted">{{ $program->kategori }}</div>
                        </td>
                        <td><code class="small text-muted">{{ $program->slug }}</code></td>
                        <td>
                            <span class="badge bg-{{ $program->status === 'Aktif' ? 'success' : 'secondary' }} rounded-pill px-3">
                                {{ $program->status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('program-admin.edit', $program->id_program) }}" class="btn btn-sm rounded-pill px-3 btn-outline-warning">
                                Edit
                            </a>
                            <form action="{{ route('program-admin.destroy', $program->id_program) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus program ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm rounded-pill px-3 btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada program.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $programs->links() }}
        </div>
    </div>
</div>
@endsection

