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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->integer('ID_PEMBAYARAN')->primary();
            $table->integer('ID_PARKIR')->nullable();
            $table->string('METODE', 50);
            $table->string('STATUS', 20);
            $table->decimal('JUMLAH', 12, 2);
            $table->datetime('WAKTU_BAYAR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
