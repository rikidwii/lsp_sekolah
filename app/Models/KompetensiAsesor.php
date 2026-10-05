<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KompetensiAsesor extends Model
{
    protected $table = 'kompetensi_asesor';
    public $timestamps = false;

    protected $fillable = [
        'asesor_profile_id', 'nama_kompetensi', 'penerbit',
        'no_id_sertifikat', 'tanggal_terbit', 'file_sertifikat',
    ];

    public function asesorProfile(): BelongsTo
    {
        return $this->belongsTo(AsesorProfile::class);
    }
}