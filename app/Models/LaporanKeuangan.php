<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    use HasFactory;

    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'id_laporan';
    public $timestamps = false;

    protected $fillable = [
        'id_user', 'periode', 'total_pemasukan', 'total_pengeluaran', 'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
