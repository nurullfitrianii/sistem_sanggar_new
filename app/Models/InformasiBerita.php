<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiBerita extends Model
{
    use HasFactory;

    protected $table = 'informasi_berita';
    protected $primaryKey = 'id_informasi';
    public $timestamps = true;

    protected $fillable = [
        'id_user', 'judul', 'slug', 'isi', 'tanggal_publish', 'tanggal_selesai', 'foto', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
