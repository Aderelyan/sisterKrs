<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KrsController;

// Halaman Utama (List Kelas)
Route::get('/', [KrsController::class, 'index']);

// Aksi Ambil Kelas (Strong)
Route::post('/ambil-kelas', [KrsController::class, 'store'])->name('ambil.kelas');

// Aksi Tertarik (Weak)
Route::post('/tertarik', [KrsController::class, 'interest'])->name('tertarik');
Route::post('/batal-tertarik', [KrsController::class, 'devote'])->name('batal.tertarik');
