<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    public $timestamps = false;

    protected $fillable = [
        'id_user', 'id_program', 'email', 'username', 'nama_calon', 'tanggal_lahir', 'tanggal_daftar', 'no_hp', 'alamat', 'status', 'dokumen', 'metode_pembayaran', 'status_pembayaran'
    ];

    public function programKelas()
    {
        return $this->belongsTo(ProgramKelas::class, 'id_program', 'id_program');
    }
    public function user() {
    return $this->belongsTo(User::class, 'username', 'username');
}
}
