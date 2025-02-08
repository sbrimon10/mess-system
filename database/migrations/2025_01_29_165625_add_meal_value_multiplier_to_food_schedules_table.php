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
        Schema::table('food_schedules', function (Blueprint $table) {
            $table->decimal('meal_value_multiplier', 3, 2)->default(1.0); // Default value is 1.0 (normal meal)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_schedules', function (Blueprint $table) {
            $table->dropColumn('meal_value_multiplier');
        });
    }
};
