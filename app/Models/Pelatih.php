<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatih extends Model
{
    use HasFactory;

    protected $table = 'pelatih';
    protected $primaryKey = 'id_pelatih';
    public $timestamps = false;

    protected $fillable = [
        'id_sanggar', 'nama_pelatih', 'bidang', 'no_hp', 'alamat'
    ];

    public function sanggar()
    {
        return $this->belongsTo(Sanggar::class, 'id_sanggar', 'id_sanggar');
    }

    public function jadwalLatihan()
    {
        return $this->hasMany(JadwalLatihan::class, 'id_pelatih', 'id_pelatih');
    }
}
