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
        Schema::create('absen_siswa', function (Blueprint $table) {
            $table->uuid('id_absen')->primary();
            $table->foreignUuid('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('id_materi')->references('id_materi')->on('materi')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('keterangan');
            $table->dateTime('waktu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_siswas');
    }
};