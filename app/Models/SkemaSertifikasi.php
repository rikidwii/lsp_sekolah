<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkemaSertifikasi extends Model
{
    protected $table = 'skema_sertifikasi';

    public $timestamps = false;

    protected $fillable = [
        'kode_skema', 'nama_skema', 'kategori', 'deskripsi',
        'durasi_ujian', 'kuota_gelombang', 'biaya', 'batas_daftar',
        'status', 'dibuat_oleh',
    ];

    protected $casts = [
        'batas_daftar' => 'date',
        'biaya' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}