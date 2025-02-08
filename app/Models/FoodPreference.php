<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FoodSchedule;
class FoodPreference extends Model
{
    protected $table = 'food_preferences';

    protected $fillable = ['user_id', 'food_schedule_id', 'will_eat', 'meal_date', 'month','auto_meal'];



    public function foodSchedule()
    {
        return $this->belongsTo(FoodSchedule::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
