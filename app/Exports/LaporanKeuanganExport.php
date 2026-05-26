<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanKeuanganExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $periode;

    // Konstruktor untuk menerima filter periode dari Controller
    public function __construct($periode = null)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $query = Transaction::query()->orderBy('tanggal', 'asc');

        if ($this->periode) {
            $query->where('tanggal', 'like', '%' . $this->periode . '%');
        }

        return $query->get();
    }

    // Header Tabel (Baris 1 di Excel)
    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis Transaksi',
            'Nominal (Rp)',
            'Keterangan'
        ];
    }

    // Mapping data ke kolom (A, B, C, D)
    public function map($trx): array
    {
        return [
            $trx->tanggal,
            $trx->jenis,
            $trx->nominal, // Biarkan angka agar bisa di-SUM di excel
            $trx->keterangan
        ];
    }
}
