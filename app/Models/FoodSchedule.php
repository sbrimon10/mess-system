<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FoodPreference;


class FoodSchedule extends Model
{
    use HasFactory;
    protected $table = 'food_schedules';

    protected $fillable = ['meal_type', 'meal_date', 'cutoff_time', 'schedule_month', 'meal_value_multiplier'];

    // Define the relationships
    public function foodPreferences()
    {
        return $this->hasMany(FoodPreference::class);
    }
}
