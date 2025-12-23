<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Mata Kuliah (Courses)
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Kode Matkul (misal: IF-101)
            $table->string('name'); // Nama Matkul
            $table->integer('quota'); // Kuota Maksimal (Strong Consistency)
            $table->integer('taken')->default(0); // Kursi Terisi
            $table->integer('interested_count')->default(0); // Jumlah Peminat (Weak Consistency)
            $table->timestamps();
        });

        // 2. Tabel Mahasiswa Mengambil KRS (Enrollments)
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('student_nim'); // NIM Mahasiswa
            $table->unsignedBigInteger('course_id'); // ID Matkul yg diambil
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('courses');
    }
};