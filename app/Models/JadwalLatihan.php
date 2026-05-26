<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalLatihan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_latihan';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;

    protected $fillable = [
        'id_pelatih', 'id_program', 'id_sanggar', 'hari', 'jam_mulai', 'jam_selesai', 'lokasi', 'materi'
    ];

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class, 'id_pelatih', 'id_pelatih');
    }

    public function programKelas()
    {
        return $this->belongsTo(ProgramKelas::class, 'id_program', 'id_program');
    }

    public function sanggar()
    {
        return $this->belongsTo(Sanggar::class, 'id_sanggar', 'id_sanggar');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_jadwal', 'id_jadwal');
    }
}
