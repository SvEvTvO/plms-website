<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->enum('pricing_type', ['free', 'freemium', 'premium'])->default('free')->after('is_public');
            $table->enum('payment_model', ['subscription', 'one_time'])->nullable()->after('pricing_type');
            $table->string('price_range')->nullable()->after('payment_model');
        });
    }

    public function down(): void
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropColumn(['pricing_type', 'payment_model', 'price_range']);
        });
    }
};
