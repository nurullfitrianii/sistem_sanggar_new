<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanggar extends Model
{
    use HasFactory;

    protected $table = 'sanggar';
    protected $primaryKey = 'id_sanggar';
    public $timestamps = false;

    protected $fillable = [
        'nama_sanggar', 'alamat', 'email', 'no_hp', 'visi', 'misi', 'pendaftaran_dibuka', 'pendaftaran_ditutup'
    ];

    public function pelatih()
    {
        return $this->hasMany(Pelatih::class, 'id_sanggar', 'id_sanggar');
    }

    public function jadwalLatihan()
    {
        return $this->hasMany(JadwalLatihan::class, 'id_sanggar', 'id_sanggar');
    }

    public function galeri()
    {
        return $this->hasMany(Galeri::class, 'id_sanggar', 'id_sanggar');
    }

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'id_sanggar', 'id_sanggar');
    }
}
