<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pendaftaran')->unique();
            $table->foreignId('asesi_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('skema_id')->constrained('skema_sertifikasi')->restrictOnDelete();
            $table->enum('status', [
                'menunggu_verifikasi',
                'terverifikasi',
                'ditolak',
                'terjadwal',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi');
            $table->text('catatan_admin')->nullable();
            $table->date('tanggal_daftar');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['asesi_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};