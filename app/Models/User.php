<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'role',
        'status',
        'email',      // Tambahkan ini
        'google_id',   // Tambahkan ini
        'id_program',  // Tambahkan ini biar sinkron sama pendaftaran
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_user', 'id_user');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_user', 'id_user');
    }

    public function informasiBerita()
    {
        return $this->hasMany(InformasiBerita::class, 'id_user', 'id_user');
    }

    public function laporanKeuangan()
    {
        return $this->hasMany(LaporanKeuangan::class, 'id_user', 'id_user');
    }

    public function kelas()
    {
    return $this->belongsTo(Kelas::class, 'id_program', 'id_program');
    }

    public function pendaftaran()
    {
    return $this->hasOne(Pendaftaran::class, 'username', 'username');
    }
}
