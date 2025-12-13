<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama barang
            $table->integer('total_stock'); // Total Stok
            $table->integer('available_stock'); // Stok yang berfungsi
            $table->integer('damaged_stock'); // Stok yang rusak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
