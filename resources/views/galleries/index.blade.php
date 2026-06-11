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
                             <form action="{{ route('galeri-admin.destroy', $galeri) }}" method="POST" class="d-inline form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-message="Apakah anda yakin ingin menghapus foto '{{ $galeri->judul }}'?">
                                    Hapus
                                </button>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                const message = this.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?';
                
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
