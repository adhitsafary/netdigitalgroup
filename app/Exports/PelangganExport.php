<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PelangganExport implements FromCollection, WithHeadings
{
    protected $pelanggan;

    // Constructor untuk menerima data pelanggan
    public function __construct($pelanggan)
    {
        $this->pelanggan = $pelanggan;
    }

    /**
     * Return a collection of data to be exported.
     */
    public function collection()
    {
        return $this->pelanggan;
    }

    /**
     * Define the headings for the Excel export.
     */
    public function headings(): array
    {
        return [
            'ID', 'Nama', 'Alamat', 'No Telepon', 'Aktivasi', 'Paket', 'Jumlah Pembayaran', 'Tanggal Tagih', 'Status Pembayaran'
        ];
    }
}
