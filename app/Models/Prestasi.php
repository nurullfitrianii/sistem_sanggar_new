<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';
    protected $primaryKey = 'id_prestasi';
    public $timestamps = false;

    protected $fillable = [
        'id_sanggar', 'nama_event', 'tahun', 'tingkat', 'keterangan'
    ];

    public function sanggar()
    {
        return $this->belongsTo(Sanggar::class, 'id_sanggar', 'id_sanggar');
    }
}
