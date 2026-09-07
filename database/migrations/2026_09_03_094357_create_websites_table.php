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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('slug');
            $table->string('url', 2048); // URL bisa panjang

            $table->text('description')->nullable();

            $table->string('favicon_url')->nullable();
            $table->string('image_url')->nullable();

            // ENUM untuk pricing type
            $table->enum('pricing_type', ['free', 'freemium', 'paid'])->default('free');

            // Personal Information
            $table->text('why_saved')->nullable();
            $table->text('notes')->nullable();

            // Status dan Favorite
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->boolean('is_favorite')->default(false);

            $table->timestamp('last_visited_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
