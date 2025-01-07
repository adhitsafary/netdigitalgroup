<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BayarPelanggan;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggan';

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

    // Relasi ke tabel bayar_pelanggan
    public function pembayaran()
    {
        return $this->hasMany(BayarPelanggan::class, 'pelanggan_id', 'id'); // Menghubungkan ke foreign key 'pelanggan_id'
    }

    // Relasi ke tabel bayar_pelanggan untuk mengambil pembayaran terakhir
    public function pembayaranTerakhir()
    {
        return $this->hasOne(BayarPelanggan::class, 'pelanggan_id', 'id') // Menghubungkan ke foreign key 'pelanggan_id'
            ->latest('created_at'); // Mengambil pembayaran terakhir berdasarkan 'created_at'
    }

    
}
