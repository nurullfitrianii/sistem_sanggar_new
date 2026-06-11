<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model {
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    protected $fillable = ['id_user', 'id_jadwal', 'waktu_hadir', 'status', 'keterangan'];
    public $timestamps = false; // Biar gak error kalau gak ada created_at

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function jadwalLatihan() {
        return $this->belongsTo(JadwalLatihan::class, 'id_jadwal', 'id_jadwal');
    }
    
    public function getJadwal() {
        if ($this->jadwalLatihan) {
            return $this->jadwalLatihan;
        }

        $pendaftaran = $this->user->pendaftaran ?? null;
        $id_program = $pendaftaran ? $pendaftaran->id_program : null;

        if ($id_program) {
            $englishDay = \Carbon\Carbon::parse($this->waktu_hadir)->format('l');
            $hariMap = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $hari = $hariMap[$englishDay] ?? 'Sabtu';

            $jadwal = \App\Models\JadwalLatihan::where('id_program', $id_program)
                ->where('hari', $hari)
                ->first();

            if (!$jadwal) {
                $jadwal = \App\Models\JadwalLatihan::where('id_program', $id_program)->first();
            }
            return $jadwal;
        }

        return null;
    }
}
