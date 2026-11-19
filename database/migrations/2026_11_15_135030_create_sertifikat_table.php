<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sertifikat')->unique();
            $table->foreignId('penilaian_id')->unique()->constrained('penilaian')->restrictOnDelete();
            $table->foreignId('asesi_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('skema_id')->constrained('skema_sertifikasi')->restrictOnDelete();
            $table->date('tanggal_terbit');
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['aktif', 'kadaluarsa', 'dicabut'])->default('aktif');
            $table->foreignId('diterbitkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};