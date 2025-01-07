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
        Schema::create('isolir', function (Blueprint $table) {
            $table->id();
            $table->string('id_plg');
            $table->string('nama_plg');
            $table->string('alamat_plg');
            $table->string('no_telepon_plg');
            $table->string('aktivasi_plg');
            $table->string('paket_plg');
            $table->string('harga_paket');
            $table->string('tgl_tagih_plg');
            $table->string('keterangan_plg');
            $table->string('odp');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('status_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isolir');
    }
};
