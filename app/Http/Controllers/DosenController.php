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

        // 1. Ambil Kelas Milik Saya
        $myClasses = Krs::where('dosen_id', $user->id)
                        ->with('mataKuliah')
                        ->get()
                        ->sortBy(function($krs) {
                            return $krs->mataKuliah->nama_mk ?? '';
                        });

        $totalSks = $myClasses->sum(function ($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });

        // 2. Ambil Semua Kelas & Cek Status
        $allClasses = MataKuliah::orderBy('nama_mk')->get()->map(function ($class) use ($user) {
            // Cek apakah SAYA yang ambil
            $class->isTakenByMe = Krs::where('mata_kuliah_id', $class->id)
                                     ->where('dosen_id', $user->id)
                                     ->exists();
            
            // Cek apakah SUDAH DIAMBIL (oleh siapa saja, termasuk saya)
            $class->isBooked = Krs::where('mata_kuliah_id', $class->id)->exists();
            
            return $class;
        });

        return view('dosen_dashboard', compact('myClasses', 'allClasses', 'totalSks'));
    }

    public function claim(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:mata_kuliahs,id',
        ]);

        $user = Auth::user();
        $classId = $request->class_id;

        // CEK 1: Apakah kelas ini sudah ada pemiliknya di tabel KRS?
        $isBooked = Krs::where('mata_kuliah_id', $classId)->exists();

        if ($isBooked) {
            // Jika sudah ada pemiliknya, tolak!
            return back()->with('error', 'Maaf, kelas ini baru saja diambil oleh dosen lain.');
        }

        // Simpan
        Krs::create([
            'mata_kuliah_id' => $classId,
            'dosen_id' => $user->id,
            'claimed_at' => now(),
        ]);

        return back()->with('success', 'Berhasil mengambil kelas.');
    }    public function unclaim(Request $request)
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
            'email' => $request->nidn . '@dummy.com',
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }
}