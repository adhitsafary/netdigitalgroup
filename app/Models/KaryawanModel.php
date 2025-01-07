<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanModel extends Model
{
    use HasFactory;

    protected $table = "karyawan";

    // Menentukan kolom-kolom yang dapat diisi
    protected $fillable = [
        'nama',
        'ktp',
        'alamat',
        'no_telepon',
        'posisi',
        'mulai_kerja',
        'keterangan',
        'foto',
        'gaji',
        'tgl_gajihan',
    ];

    // Jika kamu ingin menggunakan timestamps otomatis
    public $timestamps = true;

    // Jika ingin menonaktifkan timestamps otomatis
    // public $timestamps = false;s

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

}
