<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1️⃣ Hilangkan AUTO_INCREMENT
        DB::statement('ALTER TABLE inventory_units MODIFY id BIGINT');

        // 2️⃣ Drop primary key lama
        Schema::table('inventory_units', function (Blueprint $table) {
            $table->dropPrimary();
        });

        // 3️⃣ Ubah id menjadi string
        Schema::table('inventory_units', function (Blueprint $table) {
            $table->string('id', 5)->change();
        });

        // 4️⃣ Set primary key baru
        Schema::table('inventory_units', function (Blueprint $table) {
            $table->primary('id');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_units', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('inventory_units', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
    }
};
