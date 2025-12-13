<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }

    public function down(): void
        // Jika Anda ingin mengembalikan kolomnya saat rollback
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('name');
        });
    }
};