<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program_kelas';
    protected $primaryKey = 'id_program';
    public $timestamps = false; // Matikan timestamps

    protected $fillable = ['nama_program', 'slug', 'kategori', 'deskripsi', 'foto', 'biaya', 'durasi', 'jumlah_sesi', 'status'];

    public function jadwalLatihan()
    {
        return $this->hasMany(JadwalLatihan::class, 'id_program', 'id_program');
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_program', 'id_program');
    }
}
