<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'tanggal_bayar',
        'bulan',
        'jumlah',
        'status',
        'bukti_bayar',
        'tipe_iuran',
        'metode_pembayaran' // Suda diperbaiki (t dihapus)
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
