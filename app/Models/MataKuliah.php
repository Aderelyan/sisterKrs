<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs'; // Nama tabel di database
    
    protected $fillable = [
        'kode_mk', 'nama_mk', 'semester', 'prodi', 'sks'
    ];

    // Relasi: Satu Matkul bisa punya banyak Krs (multiple slot)
    public function krs()
    {
        return $this->hasMany(Krs::class, 'mata_kuliah_id');
    }
}   