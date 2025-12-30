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
            'email' => '1234567890', // Use NIDN as email
            'password' => Hash::make('password'), // Passwordnya: password
        ]);
        
        // Dosen B (Bu Siti) - Buat tes rebutan kelas nanti
        User::create([
            'name' => 'Bu Siti',
            'nidn' => '0987654321',
            'email' => '0987654321',
            'password' => Hash::make('password'),
        ]);

        // 2. Buat Daftar Mata Kuliah (Ceritanya data dari Admin Akademik)
        $courses = [
            ['name' => 'Sistem Terdistribusi A', 'sks' => 3, 'day' => 'Senin', 'start_time' => '08:00', 'end_time' => '10:30', 'dosen_id' => null, 'created_at' => now()],
            ['name' => 'Sistem Terdistribusi B', 'sks' => 3, 'day' => 'Senin', 'start_time' => '13:00', 'end_time' => '15:30', 'dosen_id' => null, 'created_at' => now()],
            ['name' => 'Pemrograman Web A', 'sks' => 4, 'day' => 'Selasa', 'start_time' => '07:00', 'end_time' => '11:00', 'dosen_id' => null, 'created_at' => now()],
            ['name' => 'Pemrograman Web B', 'sks' => 4, 'day' => 'Rabu', 'start_time' => '07:00', 'end_time' => '11:00', 'dosen_id' => null, 'created_at' => now()],
            ['name' => 'Kecerdasan Buatan', 'sks' => 3, 'day' => 'Kamis', 'start_time' => '09:00', 'end_time' => '11:30', 'dosen_id' => null, 'created_at' => now()],
            ['name' => 'Basis Data Lanjut', 'sks' => 3, 'day' => 'Jumat', 'start_time' => '13:00', 'end_time' => '15:30', 'dosen_id' => null, 'created_at' => now()],
        ];

        DB::table('mata_kuliahs')->insert($courses);
    }
}