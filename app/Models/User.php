<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Kita matikan ini karena pakai NIDN
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nidn',      // INI YANG PENTING
        'password',
        'email',     // Biarkan ada untuk kompatibilitas database, tapi tidak wajib diisi
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // PENTING: Kita hapus getAuthIdentifierName()
    // Biarkan Laravel mengidentifikasi user berdasarkan ID (Primary Key) secara internal
    // Saat LoginRequest mencari user via NIDN, session akan menyimpan ID user tersebut.
}