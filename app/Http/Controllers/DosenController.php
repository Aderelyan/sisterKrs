<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Krs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil Kelas Milik Saya (Dari Krs, join MataKuliah)
        $myClasses = Krs::where('dosen_id', $user->id)
                        ->with('mataKuliah')
                        ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                        ->orderBy('start_time')
                        ->get();

        // Hitung Total SKS dari myClasses
        $totalSks = $myClasses->sum(function ($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });

        // 2. Ambil Semua Kelas Tersedia (Dari MataKuliah), tambah flag isTaken
        $allClasses = MataKuliah::orderBy('nama_mk')->get()->map(function ($class) use ($user) {
            $class->isTaken = Krs::where('mata_kuliah_id', $class->id)->where('dosen_id', $user->id)->exists();
            return $class;
        });

        return view('dosen_dashboard', compact('myClasses', 'allClasses', 'totalSks'));
    }

    public function claim(Request $request)
    {
        $user = Auth::user();
        $classId = $request->class_id;
        $day = $request->day;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        $targetClass = MataKuliah::find($classId);

        if (!$targetClass) {
            return back()->with('error', 'Kelas tidak ditemukan.');
        }

        // Cek bentrok jadwal di Krs
        $conflict = Krs::where('dosen_id', $user->id)
            ->where('day', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($conflict) {
            return back()->with('error', "Jadwal bentrok dengan {$conflict->mataKuliah->nama_mk}.");
        }

        // Insert ke Krs
        Krs::create([
            'mata_kuliah_id' => $classId,
            'dosen_id' => $user->id,
            'claimed_at' => now(),
            'day' => $day,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return back()->with('success', "Berhasil ambil {$targetClass->nama_mk}.");
    }

    public function unclaim(Request $request)
    {
        // Fitur melepas kelas (Jaga-jaga kalau salah ambil)
        $krs = Krs::where('id', $request->krs_id)
                    ->where('dosen_id', Auth::id())
                    ->first();
        
        if ($krs) {
            $krs->delete();
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
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }
}