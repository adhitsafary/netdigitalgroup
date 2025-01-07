<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bayar_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('pelanggan_id');
            $table->string('nama_plg'); // Nama pelanggan
            $table->string('alamat_plg'); // Alamat pelanggan
            $table->date('tgl_tagih_plg'); // Tanggal tagih pelanggan
            $table->date('tanggal_pembayaran'); // Tanggal pembayaran
            $table->integer('jumlah_pembayaran'); // Jumlah pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bayar_pelanggan');
    }
};
