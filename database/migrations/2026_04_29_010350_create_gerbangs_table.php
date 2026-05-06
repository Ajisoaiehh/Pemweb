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
        Schema::create('gerbang', function (Blueprint $table) {
            $table->integer('ID_GERBANG')->primary();
            $table->integer('ID_QR')->nullable();
            $table->string('LOKASI', 100);
            $table->string('STATUS_PLANG', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerbang');
    }
};
