<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    use HasFactory;

    protected $table = 'kontak';
    protected $primaryKey = 'id_kontak';
    public $timestamps = false;

    protected $fillable = [
        'nama_pengirim', 'email', 'pesan', 'tanggal_kirim'
    ];
}
