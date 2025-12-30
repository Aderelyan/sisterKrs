<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs'; // Nama tabel di database
    
    protected $fillable = [
        'name', 'sks', 'day', 'start_time', 'end_time', 'dosen_id', 'claimed_at'
    ];

    // Relasi: Satu Matkul dimiliki Satu Dosen
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}   