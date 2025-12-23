<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Data Dummy 5 Mata Kuliah
        DB::table('courses')->insert([
            [
                'code' => 'IF-401',
                'name' => 'Sistem Terdistribusi',
                'quota' => 40,
                'interested_count' => 120, // Peminat banyak
                'taken' => 0,
            ],
            [
                'code' => 'IF-402',
                'name' => 'Kecerdasan Buatan',
                'quota' => 30,
                'interested_count' => 85,
                'taken' => 0,
            ],
            [
                'code' => 'IF-403',
                'name' => 'Pemrograman Web',
                'quota' => 50,
                'interested_count' => 200,
                'taken' => 0,
            ],
            [
                'code' => 'IF-404',
                'name' => 'Basis Data Lanjut',
                'quota' => 35,
                'interested_count' => 40,
                'taken' => 0,
            ],
            [
                'code' => 'IF-405',
                'name' => 'Technopreneurship',
                'quota' => 60,
                'interested_count' => 15,
                'taken' => 0,
            ]
        ]);
    }
}