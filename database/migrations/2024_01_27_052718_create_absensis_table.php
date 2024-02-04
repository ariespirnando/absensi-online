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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->string('konfigurasi_pelajarans_id');
            $table->string('gurus_id')->nullable();
            $table->string('users_id');
            $table->string('keterangan');
            $table->timestamp('absensis_start')->useCurrent()->nullable();
            $table->timestamp('absensis_end')->nullable();
            $table->enum('status', ['A', 'N'])->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
