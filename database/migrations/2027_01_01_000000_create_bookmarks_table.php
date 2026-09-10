<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Simpanan User (Satu User menyimpan Satu Website)
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('custom_title')->nullable(); // User bisa ubah nama sendiri
            $table->boolean('is_public')->default(true); // Privasi
            $table->timestamps();

            $table->unique(['user_id', 'website_id']); // Cegah user simpan web yang sama 2x
        });

        // 2. Pivot Table untuk Bookmark dan Tags (Many-to-Many)
        Schema::create('bookmark_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bookmark_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['bookmark_id', 'tag_id']); // Cegah duplikat tag di 1 bookmark
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookmark_tag');
        Schema::dropIfExists('bookmarks');
    }
};
