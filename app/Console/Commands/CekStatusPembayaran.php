<?php

namespace App\Console\Commands;

use App\Models\Pelanggan;
use Illuminate\Console\Command;

class CekStatusPembayaran extends Command
{
    protected $signature = 'pembayaran:cek';
    protected $description = 'Cek status pembayaran pelanggan berdasarkan tanggal tagih';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $pelanggan_all = Pelanggan::all();

        foreach ($pelanggan_all as $pelanggan) {
            if (now()->gt($pelanggan->tgl_tagih_plg) && $pelanggan->status_pembayaran === 'sudah bayar') {
                $pelanggan->status_pembayaran = 'belum bayar';
                $pelanggan->save();
            }
        }

        $this->info('Status pembayaran berhasil diperiksa.');
    }
}
