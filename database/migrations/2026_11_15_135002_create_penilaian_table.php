<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_jadwal_id')->unique()->constrained('peserta_jadwal')->cascadeOnDelete();
            $table->foreignId('asesor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('skor_ujian_teori', 5, 2)->nullable();
            $table->decimal('skor_ujian_praktik', 5, 2)->nullable();
            $table->text('catatan_asesor')->nullable();
            $table->enum('status_kelulusan', ['kompeten', 'belum_kompeten', 'proses'])->default('proses');
            $table->date('tanggal_penilaian')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};