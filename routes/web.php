<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DosenController; // <-- Jangan lupa import ini
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Register Dosen
Route::get('/register-dosen', function () {
    return view('auth.register-dosen');
})->name('register.dosen');
Route::post('/register-dosen', [DosenController::class, 'register'])->name('register.dosen.post');

// Route Dashboard (Otomatis diarahkan ke DosenController)
Route::get('/dashboard', [DosenController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route untuk Proses Claim/Unclaim (Harus Login)
Route::middleware('auth')->group(function () {
    Route::post('/claim-kelas', [DosenController::class, 'claim'])->name('dosen.claim');
    Route::post('/unclaim-kelas', [DosenController::class, 'unclaim'])->name('dosen.unclaim');
    
    // Route bawaan Breeze (Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';