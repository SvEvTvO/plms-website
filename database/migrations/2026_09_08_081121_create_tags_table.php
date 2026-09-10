<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            // 1. Tambahkan relasi ke kategori
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('slug');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();

            // 2. Kunci Kombinasi: Tidak boleh ada SLUG yang sama di dalam 1 KATEGORI yang sama.
            // Tapi boleh ada slug sama jika kategorinya beda.
            $table->unique(['category_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
