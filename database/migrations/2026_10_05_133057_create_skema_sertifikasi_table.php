<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skema_sertifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_skema')->unique();
            $table->string('nama_skema');
            $table->string('kategori')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('durasi_ujian')->nullable();
            $table->integer('kuota_gelombang')->nullable();
            $table->decimal('biaya', 12, 2)->nullable();
            $table->date('batas_daftar')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skema_sertifikasi');
    }
};