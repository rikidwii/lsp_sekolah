<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_asesmen')->cascadeOnDelete();
            $table->foreignId('pendaftaran_id')->unique()->constrained('pendaftaran')->cascadeOnDelete();
            $table->enum('status_kehadiran', ['wajib_hadir', 'hadir', 'tidak_hadir'])->default('wajib_hadir');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_jadwal');
    }
};