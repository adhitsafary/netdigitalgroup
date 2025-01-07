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
        Schema::table('bayar_pelanggan', function (Blueprint $table) {
            $table->string('admin_name')->nullable(); // Kolom untuk menyimpan nama admin
        });
    }

    public function down()
    {
        Schema::table('bayar_pelanggan', function (Blueprint $table) {
            $table->dropColumn('admin_name');
        });
    }

};
