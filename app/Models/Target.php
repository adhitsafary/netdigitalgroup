<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'jumlah_target',
        'jumlah_hari',
        'sisa_target',
        'hari_tersisa',
        'last_update',
        'nama_target', // Tambahkan kolom nama_target
    ];
}
