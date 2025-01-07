<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomatisPaymentModel extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_plg';

    protected $fillable = [
        'nama_plg',
        'alamat_plg',
        'no_telepon_plg',
        'aktivasi_plg',
        'paket_plg',
        'harga_paket',
        'tgl_tagih_plg',
        'keterangan_plg'
    ];

    public function pembayaran()
    {
        return $this->hasMany(BayarPelanggan::class, 'id_plg', 'id_plg');
    }
}
