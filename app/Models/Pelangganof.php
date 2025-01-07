<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelangganof extends Model
{
    use HasFactory;
    protected $table = 'plg_off';

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
        'jml_port',
        'sisa_port',
        'latitude',
        'longitude',
        'maps'
    ];
}
