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
            $table->string('schedule_month'); // Store the month (e.g., '2025-01')
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_schedules', function (Blueprint $table) {
            //
        });
    }
};
