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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_target'); // Target total
            $table->integer('jumlah_hari'); // Jumlah hari yang ditargetkan
            $table->integer('sisa_target'); // Sisa target yang belum tercapai
            $table->integer('hari_tersisa'); // Sisa hari untuk mencapai target
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
