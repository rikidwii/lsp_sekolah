<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_persyaratan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->string('jenis_dokumen');
            $table->string('nama_file');
            $table->string('path_file');
            $table->integer('ukuran_file_kb')->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_persyaratan');
    }
};