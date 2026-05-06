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
        Schema::create('pengguna_parkir', function (Blueprint $table) {
            $table->integer('ID_PENGGUNA')->primary();
            $table->string('NAMA', 100);
            $table->char('NO_HP', 15);
            $table->string('EMAIL', 100);
            $table->string('PASSWORD', 100);
            $table->decimal('SALDO', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna_parkir');
    }
};
