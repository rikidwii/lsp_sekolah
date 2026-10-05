<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsesiProfile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'nis_nisn', 'asal_sekolah', 'kelas_jurusan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}