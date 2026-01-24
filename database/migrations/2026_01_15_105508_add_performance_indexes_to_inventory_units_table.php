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
        Schema::table('inventory_units', function (Blueprint $table) {
            $table->index('condition_status');
            $table->index('inventory_item_id'); // Ensure foreign key is indexed for joins
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_units', function (Blueprint $table) {
            $table->dropIndex(['condition_status']);
            $table->dropIndex(['inventory_item_id']);
        });
    }
};
