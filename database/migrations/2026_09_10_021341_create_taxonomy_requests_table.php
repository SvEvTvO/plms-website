<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taxonomy_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Jenis yang diajukan
            $table->enum('type', ['group', 'category', 'tag']);

            // Detail Pengajuan
            $table->string('name');
            $table->foreignId('target_parent_id')->nullable(); // Berisi Group ID (jika kategori) atau Category ID (jika tag)
            $table->string('icon')->nullable();
            $table->string('color')->nullable();

            // Status Approval Admin
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable(); // Alasan jika ditolak

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxonomy_requests');
    }
};
