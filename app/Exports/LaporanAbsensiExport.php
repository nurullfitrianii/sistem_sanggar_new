<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanAbsensiExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $tanggal;
    protected $status;
    protected $id_program;
    protected $rowNumber = 0;

    /**
     * Constructor to accept filter parameters.
     */
    public function __construct($tanggal = null, $status = null, $id_program = null)
    {
        $this->tanggal = $tanggal;
        $this->status = $status;
        $this->id_program = $id_program;
    }

    /**
     * Fetch the filtered attendance records.
     */
    public function collection()
    {
        $query = Absensi::with(['user.pendaftaran.programKelas', 'jadwalLatihan.programKelas'])
            ->orderBy('waktu_hadir', 'desc');

        if ($this->tanggal) {
            $query->whereDate('waktu_hadir', $this->tanggal);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->id_program) {
            $query->where(function ($q) {
                $q->whereHas('jadwalLatihan', function ($sub) {
                    $sub->where('id_program', $this->id_program);
                })->orWhereHas('user.pendaftaran', function ($sub) {
                    $sub->where('id_program', $this->id_program);
                });
            });
        }

        return $query->get();
    }

    /**
     * Define the headings/header row.
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Username',
            'Program Kelas',
            'Waktu Hadir',
            'Status Kehadiran'
        ];
    }

    /**
     * Map each record to the export sheet.
     */
    public function map($absensi): array
    {
        $this->rowNumber++;
        
        $namaLengkap = $absensi->user->pendaftaran->nama_calon 
            ?? $absensi->user->nama_lengkap 
            ?? $absensi->user->username 
            ?? '-';
            
        $waktuHadirFormatted = $absensi->waktu_hadir 
            ? \Carbon\Carbon::parse($absensi->waktu_hadir)->timezone('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB'
            : '-';

        $namaProgram = $absensi->jadwalLatihan->programKelas->nama_program 
            ?? $absensi->user->pendaftaran->programKelas->nama_program 
            ?? '-';

        return [
            $this->rowNumber,
            $namaLengkap,
            $absensi->user->username ?? '-',
            $namaProgram,
            $waktuHadirFormatted,
            $absensi->status
        ];
    }
}
