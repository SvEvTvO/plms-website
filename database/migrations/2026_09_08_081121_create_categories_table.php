<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel Induk (nullable agar jika ada kategori tak berinduk tetap aman)
            $table->foreignId('category_group_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->default('folder');
            $table->string('color')->default('#0089ba');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
