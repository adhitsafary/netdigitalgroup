<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsolirModel extends Model
{
    use HasFactory;

    protected $table = 'isolir';

    protected $fillable = [
        'id_plg',
        'nama_plg',
        'alamat_plg',
        'no_telepon_plg',
        'aktivasi_plg',
        'paket_plg',
        'harga_paket',
        'tgl_tagih_plg',
        'keterangan_plg',
        'odp',
        'longitude',
        'latitude',
        'status_pembayaran',
    ];
}
