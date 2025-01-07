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
        Schema::table('pemberitahuan', function (Blueprint $table) {
            $table->text('pesan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pemberitahuan', function (Blueprint $table) {
            $table->string('pesan', 255)->change();
        });
    }
};
