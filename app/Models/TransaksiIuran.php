<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiIuran extends Model
{
    use HasFactory;

    protected $table = 'transaksi_iurans';

    protected $fillable = [
        'user_id',
        'tipe_iuran',
        'metode_pembayaran',
        'jumlah_bayar',
        'bukti_bayar',
        'status',
        'tanggal_bayar', // TAMBAHAN
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}
