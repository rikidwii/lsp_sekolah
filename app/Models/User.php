<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'foto_profil',
        'role',
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

    public function asesiProfile(): HasOne
    {
        return $this->hasOne(AsesiProfile::class);
    }

    public function asesorProfile(): HasOne
    {
        return $this->hasOne(AsesorProfile::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAsesor(): bool
    {
        return $this->role === 'asesor';
    }

    public function isAsesi(): bool
    {
        return $this->role === 'asesi';
    }
}