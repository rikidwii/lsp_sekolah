<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skema_id')->constrained('skema_sertifikasi')->cascadeOnDelete();
            $table->string('kode_unit');
            $table->string('judul_unit');
            $table->text('kriteria_penilaian')->nullable();
            $table->integer('urutan')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_kompetensi');
    }
};