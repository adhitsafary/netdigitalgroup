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
        Schema::create('plg_of', function (Blueprint $table) {
            $table->id(); // Kolom id otomatis
            $table->string('id_plg');
            $table->string('nama_plg');
            $table->string('alamat_plg');
            $table->string('no_telepon_plg');
            $table->date('aktivasi_plg');
            $table->string('paket_plg');
            $table->integer('harga_paket', 10, 2); // Menyimpan harga dengan dua digit desimal
            $table->date('tgl_tagih_plg');
            $table->string('status_plg');
            $table->text('keterangan_plg')->nullable(); // Bisa null
            $table->string('odp');
            $table->integer('jml_port');
            $table->integer('sisa_port');
            $table->decimal('longitude', 11, 8); // Untuk menyimpan koordinat longitude
            $table->decimal('latitude', 10, 8);  // Untuk menyimpan koordinat latitude
            $table->text('maps')->nullable(); // Bisa null untuk link Google Maps atau data peta
            $table->boolean('plg_off')->default(0); // Status pelanggan off, default 0 (aktif)
            $table->boolean('is_visible')->default(1); // Default pelanggan visible
            $table->date('last_payment_date')->nullable(); // Bisa null, untuk menyimpan tanggal pembayaran terakhir
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plg_of');
    }
};
