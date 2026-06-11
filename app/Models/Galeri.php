<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';
    protected $primaryKey = 'id_galeri';
    public $timestamps = false;

    protected $fillable = [
        'id_sanggar', 'judul', 'foto', 'keterangan', 'tanggal', 'gambar', 'deskripsi'
    ];

    public function getGambarAttribute()
    {
        return $this->attributes['foto'] ?? null;
    }

    public function setGambarAttribute($value)
    {
        $this->attributes['foto'] = $value;
    }

    public function getDeskripsiAttribute()
    {
        return $this->attributes['keterangan'] ?? null;
    }

    public function setDeskripsiAttribute($value)
    {
        $this->attributes['keterangan'] = $value;
    }

    public function sanggar()
    {
        return $this->belongsTo(Sanggar::class, 'id_sanggar', 'id_sanggar');
    }
}
