<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penilaian_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('penilaian')->cascadeOnDelete();
            $table->foreignId('unit_kompetensi_id')->constrained('unit_kompetensi')->restrictOnDelete();
            $table->enum('hasil', ['kompeten', 'belum_kompeten'])->nullable();
            $table->text('catatan')->nullable();
            $table->string('bukti_file')->nullable();

            $table->unique(['penilaian_id', 'unit_kompetensi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penilaian_unit');
    }
};