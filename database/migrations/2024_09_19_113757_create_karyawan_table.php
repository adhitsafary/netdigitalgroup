<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id(); // Kolom id otomatis
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_telpon');
            $table->string('bagian');
            $table->string('mulai_kerja');
            $table->string('keterangan')->nullable();
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
};
