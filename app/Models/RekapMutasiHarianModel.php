<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapMutasiHarianModel extends Model
{
    use HasFactory;

    protected $table = 'rekap_mutasi_harian';

    protected $fillable = [
        'tanggal',
        'tagihan_awal',
        'pemasukan_bulan_kemarin',
        'pemasukan_bulan_sekarang',
        'saldo_akhir',
        'total_koreksi',
        'belum_tertagih',
        'pemasukan_harian',
        'piutang',
        'piutang_masuk',
        'pendapatan_total',
        'keterangan'
    ];
}
