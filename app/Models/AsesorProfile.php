<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsesorProfile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'gelar', 'no_registrasi_bnsp', 'instansi_asal',
        'status_verifikasi', 'catatan_verifikasi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kompetensi(): HasMany
    {
        return $this->hasMany(KompetensiAsesor::class);
    }
}