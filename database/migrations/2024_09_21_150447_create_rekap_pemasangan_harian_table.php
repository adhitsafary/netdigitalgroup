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
        Schema::create('rekap_pemasangan', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_telpon');
            $table->string('tgl_aktivasi');
            $table->integer('paket_plg');
            $table->integer('nominal');
            $table->string('jt');
            $table->string('status');
            $table->string('tgl_pengajuan');
            $table->integer('registrasi');
            $table->string('marketing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_pemasangan_harian');
    }
};
