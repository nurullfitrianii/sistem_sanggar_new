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
        'id_sanggar', 'judul', 'foto', 'keterangan'
    ];

    public function sanggar()
    {
        return $this->belongsTo(Sanggar::class, 'id_sanggar', 'id_sanggar');
    }
}
