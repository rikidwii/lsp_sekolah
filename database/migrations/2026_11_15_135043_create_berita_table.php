<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori')->nullable();
            $table->text('konten');
            $table->string('gambar')->nullable();
            $table->foreignId('penulis_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->date('tanggal_publish')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};