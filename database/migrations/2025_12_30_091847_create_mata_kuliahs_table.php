<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Matkul
            $table->integer('sks'); // SKS
            $table->string('day'); // Hari (Senin, Selasa...)
            $table->time('start_time'); // Jam Mulai
            $table->time('end_time'); // Jam Selesai
            
            // KUNCI UTAMA TUGAS INI:
            // Kolom untuk menyimpan ID Dosen yang mengambil kelas.
            // Kita buat 'nullable' (boleh kosong) karena awalnya belum ada dosen yang ambil.
            $table->unsignedBigInteger('dosen_id')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};