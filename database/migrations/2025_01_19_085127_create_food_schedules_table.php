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
        Schema::create('food_schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner']);
            $table->date('meal_date');
            $table->time('cutoff_time')->nullable();
            $table->string('schedule_month'); // Store the month (e.g., '2025-01')
            $table->decimal('meal_value_multiplier', 3, 2)->default(1.0); // Default value is 1.0 (normal meal)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_schedules');
    }
};
