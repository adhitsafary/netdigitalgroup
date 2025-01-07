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
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true); // Kolom untuk status visibilitas
            $table->date('last_payment_date')->nullable(); // Kolom untuk tanggal pembayaran terakhir
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn(['is_visible', 'last_payment_date']);
        });
    }
};
