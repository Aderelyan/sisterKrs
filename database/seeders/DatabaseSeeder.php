<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Akun Dosen (Supaya Anda bisa Login)
        // Dosen A (Pak Budi)
        User::create([
            'name' => 'Pak Budi',
            'nidn' => '1234567890',
            'password' => Hash::make('password'), // Passwordnya: password
        ]);
        
        // Dosen B (Bu Siti) - Buat tes rebutan kelas nanti
        User::create([
            'name' => 'Bu Siti',
            'nidn' => '0987654321',
            'password' => Hash::make('password'),
        ]);

        // 2. Buat Daftar Mata Kuliah (Ceritanya data dari Admin Akademik)
        $courses = [
            ['kode_mk' => 'SI101', 'nama_mk' => 'Sistem Terdistribusi A', 'semester' => 5, 'prodi' => 'A', 'sks' => 3, 'day' => 'Senin', 'start_time' => '08:00', 'end_time' => '10:30', 'dosen_id' => null, 'created_at' => now()],
            ['kode_mk' => 'SI102', 'nama_mk' => 'Sistem Terdistribusi B', 'semester' => 5, 'prodi' => 'A', 'sks' => 3, 'day' => 'Senin', 'start_time' => '13:00', 'end_time' => '15:30', 'dosen_id' => null, 'created_at' => now()],
            ['kode_mk' => 'PW101', 'nama_mk' => 'Pemrograman Web A', 'semester' => 4, 'prodi' => 'B', 'sks' => 4, 'day' => 'Selasa', 'start_time' => '07:00', 'end_time' => '11:00', 'dosen_id' => null, 'created_at' => now()],
            ['kode_mk' => 'PW102', 'nama_mk' => 'Pemrograman Web B', 'semester' => 4, 'prodi' => 'B', 'sks' => 4, 'day' => 'Rabu', 'start_time' => '07:00', 'end_time' => '11:00', 'dosen_id' => null, 'created_at' => now()],
            ['kode_mk' => 'AI101', 'nama_mk' => 'Kecerdasan Buatan', 'semester' => 6, 'prodi' => 'C', 'sks' => 3, 'day' => 'Kamis', 'start_time' => '09:00', 'end_time' => '11:30', 'dosen_id' => null, 'created_at' => now()],
            ['kode_mk' => 'BD101', 'nama_mk' => 'Basis Data Lanjut', 'semester' => 5, 'prodi' => 'A', 'sks' => 3, 'day' => 'Jumat', 'start_time' => '13:00', 'end_time' => '15:30', 'dosen_id' => null, 'created_at' => now()],
        ];

        DB::table('mata_kuliahs')->insert($courses);
    }
}