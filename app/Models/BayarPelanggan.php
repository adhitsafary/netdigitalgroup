<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BayarPelanggan extends Model
{
    use HasFactory;

    protected $table = 'bayar_pelanggan';

    protected $fillable = [
        'pelanggan_id',
        'id_plg',          // Tambahkan kolom id_plg
        'nama_plg',
        'alamat_plg',
        'no_telepon_plg',
        'tanggal_pembayaran',
        'jumlah_pembayaran',
        'metode_transaksi',
        'updated_at',
        'created_at',
        'paket_plg',
        'keterangan_plg',
        'tgl_tagih_plg',
        'aktivasi_plg',
        'admin_name',
        'untuk_pembayaran',

    ];

    protected $dates = ['tanggal_pembayaran'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
