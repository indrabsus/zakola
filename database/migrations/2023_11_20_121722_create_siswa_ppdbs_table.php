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
        Schema::create('siswa_ppdb', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenkel',['l','p']);
            $table->string('no_hp');
            $table->string('agama');
            $table->string('alamat');
            $table->string('nisn');
            $table->string('nik_siswa');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('asal_sekolah');
            $table->string('minat_jurusan1');
            $table->string('minat_jurusan2');
            $table->enum('bayar_daftar',['y','n','l']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_ppdb');
    }
};
