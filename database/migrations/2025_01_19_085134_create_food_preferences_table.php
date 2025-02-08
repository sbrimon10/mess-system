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
        Schema::create('food_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->unsignedBigInteger('food_schedule_id');  // Ensure it's unsigned
        $table->enum('will_eat', ['yes', 'no'])->default('yes');
        $table->date('meal_date');
        $table->string('month'); // Store the month (e.g., '2025-01')
        $table->boolean('auto_meal')->default(false); // Default is false (unchecked)
        $table->timestamps();

        // Define the foreign key constraint explicitly
        $table->foreign('food_schedule_id')
              ->references('id')
              ->on('food_schedules')
              ->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_preferences');
    }
};
