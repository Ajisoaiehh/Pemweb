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
        Schema::create('parkir', function (Blueprint $table) {
            $table->integer('ID_PARKIR')->primary();
            $table->integer('PAR_ID_PARKIR')->nullable();
            $table->string('NO_PLAT', 15)->nullable();
            $table->integer('ID_PENGGUNA')->nullable();
            $table->datetime('WAKTU_MASUK');
            $table->datetime('WAKTU_KELUAR');
            $table->decimal('BIAYA', 12, 2);
            $table->string('STATUS_PARKIR', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkir');
    }
};
