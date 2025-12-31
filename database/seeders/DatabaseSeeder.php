<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT AKUN DOSEN (Agar Anda bisa login)
        // Kita buat 2 user untuk contoh
        User::create([
            'name' => 'Pak Budi',
            'nidn' => '12345678',
            'email' => 'budi@dosen.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Bu Siti',
            'nidn' => '87654321',
            'email' => 'siti@dosen.com',
            'password' => Hash::make('password'),
        ]);

        // 2. BUAT DATA MATA KULIAH
        // Ingat: Tanpa 'day', 'time', atau 'dosen_id'
        $courses = [
            [
                'kode_mk' => 'SI101',
                'nama_mk' => 'Sistem Terdistribusi A',
                'semester' => 5,
                'prodi' => 'Informatika',
                'sks' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'SI102',
                'nama_mk' => 'Sistem Terdistribusi B',
                'semester' => 5,
                'prodi' => 'Informatika',
                'sks' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'PW101',
                'nama_mk' => 'Pemrograman Web A',
                'semester' => 3,
                'prodi' => 'Informatika',
                'sks' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'AI101',
                'nama_mk' => 'Kecerdasan Buatan',
                'semester' => 6,
                'prodi' => 'Informatika',
                'sks' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('mata_kuliahs')->insert($courses);
    }
}