<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
{
    // 1. FITUR READ (EVENTUAL CONSISTENCY)
    // Menampilkan daftar kelas. Di dunia nyata, ini bisa baca dari Database Slave (Replika).
    // Kalau datanya telat update 1-2 detik, mahasiswa tidak rugi fatal.
    public function index()
    {
        $courses = Course::all();
        return view('krs_list', compact('courses'));
    }

    // 2. FITUR WRITE (STRONG CONSISTENCY)
    // Mahasiswa klik "Ambil Kelas". Ini WAJIB akurat.
    // Kita pakai DB Transaction & lockForUpdate untuk mencegah bentrok data (Race Condition).
    public function store(Request $request)
    {
        $courseId = $request->course_id;
        $studentNim = 'MHS-' . rand(1000, 9999); // Simulasi NIM Mahasiswa

        // Mulai Transaksi Database (ACID)
        DB::beginTransaction();

        try {
            // "LOCK" baris data ini. Orang lain harus antri sampai proses ini selesai.
            // Ini inti dari STRONG CONSISTENCY.
            $course = Course::where('id', $courseId)->lockForUpdate()->first();

            // Cek apakah kuota masih ada?
            if ($course->taken < $course->quota) {
                // Tambah jumlah yang diambil
                $course->taken = $course->taken + 1;
                $course->save();

                // Catat siapa yang ambil (Log)
                DB::table('enrollments')->insert([
                    'student_nim' => $studentNim,
                    'course_id' => $courseId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit(); // Simpan permanen
                return back()->with('success', "Berhasil ambil kelas {$course->name}!");
            } else {
                DB::rollBack(); // Batalkan jika penuh
                return back()->with('error', "Gagal! Kelas {$course->name} sudah penuh.");
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error sistem
            return back()->with('error', 'Terjadi kesalahan sistem, coba lagi.');
        }
    }

    // 3. FITUR WRITE (WEAK CONSISTENCY)
    // Mahasiswa klik "Saya Tertarik".
    // Tidak perlu locking ketat. Kalau datanya selisih dikit, tidak masalah.
    public function interest(Request $request)
    {
        $course = Course::find($request->course_id);
        
        // Update biasa tanpa Lock (Optimistic/Weak logic)
        $course->interested_count = $course->interested_count + 1;
        $course->save();

        return back()->with('success', 'Ketertarikan anda tercatat!');
    }
public function devote(Request $request)
    {
        $course = Course::find($request->course_id);
        
        // Cek biar tidak minus
        if ($course->interested_count > 0) {
            $course->interested_count = $course->interested_count - 1;
            $course->save();
            return back()->with('success', 'Anda membatalkan ketertarikan.');
        }

        return back();
    }
}