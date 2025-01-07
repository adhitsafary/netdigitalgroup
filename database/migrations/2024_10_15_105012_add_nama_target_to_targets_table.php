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
        Schema::table('targets', function (Blueprint $table) {
            $table->string('nama_target')->after('id'); // Menambahkan kolom nama_target setelah kolom id
        });
    }

    public function down()
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn('nama_target'); // Menghapus kolom jika migrasi dibatalkan
        });
    }
};
