<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// TAMBAHKAN IMPORT INI
use App\Models\Permohonan;
use App\Models\Notifikasi;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'nik',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function permohonans()
    {
        return $this->hasMany(Permohonan::class);
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Helper methods
    public function isMasyarakat()
    {
        return $this->role === 'masyarakat';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }
}
