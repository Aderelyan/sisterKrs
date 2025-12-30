<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil Kelas Milik Saya (Diurutkan berdasarkan hari)
        $myClasses = MataKuliah::where('dosen_id', $user->id)
                        ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                        ->orderBy('start_time')
                        ->get();

        // Hitung Total SKS
        $totalSks = $myClasses->sum('sks');

        // 2. Ambil Semua Kelas Tersedia (Untuk tabel bawah)
        $allClasses = MataKuliah::orderBy('name')->get();

        return view('dosen_dashboard', compact('myClasses', 'allClasses', 'totalSks'));
    }

    public function claim(Request $request)
    {
        $user = Auth::user();
        $classId = $request->class_id;

        $targetClass = MataKuliah::find($classId);

        if (!$targetClass) {
            return back()->with('error', 'Kelas tidak ditemukan.');
        }

        if ($targetClass->dosen_id != null) {
            return back()->with('error', 'Kelas sudah diambil.');
        }

        // Cek bentrok jadwal
        $conflict = MataKuliah::where('dosen_id', $user->id)
            ->where('day', $targetClass->day)
            ->where(function ($query) use ($targetClass) {
                $query->where('start_time', '<', $targetClass->end_time)
                      ->where('end_time', '>', $targetClass->start_time);
            })
            ->first();

        if ($conflict) {
            return back()->with('error', "Jadwal bentrok dengan {$conflict->name}.");
        }

        $targetClass->dosen_id = $user->id;
        $targetClass->claimed_at = now();
        $targetClass->save();

        return back()->with('success', "Berhasil ambil {$targetClass->name}.");
    }

    public function unclaim(Request $request)
    {
        // Fitur melepas kelas (Jaga-jaga kalau salah ambil)
        $class = MataKuliah::where('id', $request->class_id)
                    ->where('dosen_id', Auth::id())
                    ->first();
        
        if ($class) {
            $class->dosen_id = null; // Kosongkan lagi
            $class->claimed_at = null;
            $class->save();
            return back()->with('success', 'Kelas berhasil dilepas.');
        }

        return back()->with('error', 'Gagal melepas kelas.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|unique:users,nidn',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'nidn' => $request->nidn,
            'email' => $request->nidn, // Use NIDN as email for login
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }
}