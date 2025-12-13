<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_units', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key ke InventoryItem (Item Induk)
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            
            // Kolom unik untuk identifikasi unit (misalnya Serial Number)
            $table->string('serial_number')->nullable()->unique(); 
            
            // Status kondisi unit
            $table->enum('condition_status', ['available', 'in_use', 'damaged', 'maintenance'])->default('available');

            // Detail peminjam atau lokasi unit saat ini (Opsional)
            $table->string('current_holder')->nullable();
            
            // Kapan unit ini ditambahkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_units');
    }
};