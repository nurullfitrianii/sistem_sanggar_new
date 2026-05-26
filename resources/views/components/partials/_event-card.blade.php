<div class="card event-card section-card">
    <div class="event-title">
        {{ $jadwal->programKelas->nama_program ?? 'Program Tidak Ditemukan' }}
    </div>
    
    <div class="event-meta">
        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
    </div>
    
    <div class="event-meta">
        {{ $jadwal->pelatih->nama_pelatih ?? '-' }}
    </div>
    
    <div class="event-meta">
        {{ $jadwal->lokasi ?? 'Studio Sanggar' }}
    </div>

    @if($role === 'ketua')
        <div class="event-actions">
            <a href="{{ route('jadwal.edit', $jadwal->id_jadwal) }}" class="btn btn-sm py-0 px-2 btn-outline-warning" style="font-size: 0.7rem">
                Edit
            </a>
            <form action="{{ route('jadwal.destroy', $jadwal->id_jadwal) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Hapus jadwal ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm py-0 px-2 btn-outline-danger" style="font-size: 0.7rem">
                    Hapus
                </button>
            </form>
        </div>
    @endif
</div>
