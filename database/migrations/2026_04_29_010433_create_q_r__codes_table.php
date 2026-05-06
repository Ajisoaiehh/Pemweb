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
        Schema::create('qr_code', function (Blueprint $table) {
            $table->integer('ID_QR')->primary();
            $table->string('NO_PLAT', 15)->nullable();
            $table->integer('ID_GERBANG')->nullable();
            $table->string('TIPE', 10);
            $table->datetime('WAKTU_DIBUAT');
            $table->datetime('VALID_UNTIL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_code');
    }
};
