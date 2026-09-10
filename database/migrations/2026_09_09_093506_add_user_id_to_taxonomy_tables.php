<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah user_id ke Group
        Schema::table('category_groups', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // 2. Tambah user_id ke Kategori dan Hapus ikatan Unique lama
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();

            // Hapus aturan unik lama agar User A dan User B bisa sama-sama bikin kategori "Design"
            $table->dropUnique(['slug']);

            // Buat aturan unik baru: Slug tidak boleh sama hanya jika di dalam scope user yang sama
            // user_id NULL (Global) juga akan dievaluasi secara terpisah
            $table->unique(['user_id', 'slug']);
        });

        // 3. Tambah user_id ke Tag
        Schema::table('tags', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('category_groups', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'slug']);
            $table->unique('slug'); // Kembalikan seperti semula

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
