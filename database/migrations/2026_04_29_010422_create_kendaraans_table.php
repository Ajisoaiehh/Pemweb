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
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->string('NO_PLAT', 15)->primary();
            $table->integer('ID_PENGGUNA')->nullable();
            $table->integer('ID_QR')->nullable();
            $table->string('JENIS_KENDARAAN', 50);
            $table->string('STATUS_KENDARAAN', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
