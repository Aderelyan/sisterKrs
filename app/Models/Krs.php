<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $fillable = [
        'mata_kuliah_id', 'dosen_id', 'claimed_at', 'day', 'start_time', 'end_time'
    ];

    // Relasi ke MataKuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    // Relasi ke User (dosen)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
