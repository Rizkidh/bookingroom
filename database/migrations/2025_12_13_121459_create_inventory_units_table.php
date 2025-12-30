<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_units', function (Blueprint $table) {

            // ID custom (00001, 00002, dst)
            $table->string('id', 5)->primary();

            // Relasi ke inventory_items
            $table->foreignId('inventory_item_id')
                ->constrained('inventory_items')
                ->onDelete('cascade');

            // Serial number opsional
            $table->string('serial_number')->nullable()->unique();

            // Status kondisi
            $table->enum('condition_status', [
                'available',
                'in_use',
                'damaged',
                'maintenance'
            ])->default('available');

            // Pemegang / lokasi
            $table->string('current_holder')->nullable();

            // QR Code path
            $table->string('qr_code')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_units');
    }
};
