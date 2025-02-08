<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('food_preferences', function (Blueprint $table) {
            $table->boolean('auto_meal')->default(false); // Default is false (unchecked)
        });
    }
    
    public function down()
    {
        Schema::table('food_preferences', function (Blueprint $table) {
            $table->dropColumn('auto_meal');
        });
    }
    
};
