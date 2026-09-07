<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 'color' sudah dibuat di migration create_master_categories_table,
        // migration ini hanya menjaga kompatibilitas untuk database lama.
        if (! Schema::hasColumn('master_categories', 'color')) {
            Schema::table('master_categories', function (Blueprint $table) {
                // Default ke 'blue' jika user tidak memilih warna
                $table->string('color')->default('blue')->after('icon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('master_categories', 'color')) {
            Schema::table('master_categories', function (Blueprint $table) {
                $table->dropColumn('color');
            });
        }
    }
};
