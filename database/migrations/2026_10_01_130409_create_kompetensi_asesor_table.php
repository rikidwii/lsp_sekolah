<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kompetensi_asesor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asesor_profile_id')->constrained('asesor_profiles')->cascadeOnDelete();
            $table->string('nama_kompetensi')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('no_id_sertifikat')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('file_sertifikat')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kompetensi_asesor');
    }
};