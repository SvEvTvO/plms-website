<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->text('original_url');
            $table->string('url_hash')->unique(); // Mencegah website kembar
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('icon_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
