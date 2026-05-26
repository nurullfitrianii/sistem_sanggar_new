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
}
