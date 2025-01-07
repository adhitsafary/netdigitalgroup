<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;
    protected $table = 'perbaikan';

    // Model Perbaikan
    protected $fillable = [
        'id_plg',
        'nama_plg',
        'alamat_plg',
        'no_telepon_plg',
        'paket_plg',
        'odp',
        'maps'
    ];
}
