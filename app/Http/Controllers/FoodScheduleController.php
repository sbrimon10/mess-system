<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class FoodScheduleController extends Controller
{
    public function index()
    {
        $groupedFoodSchedules = FoodSchedule::where('schedule_month', Carbon::now()->format('Y-m'))->orderBy('meal_date')->get()->groupBy('meal_date');
    
    return view('food_schedules.index', compact('groupedFoodSchedules'));
        
    }
    /**
     * Show the form for creating a new food schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('food_schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'meal_type' => 'required',
            'meal_value' => 'required|numeric',
            'cutoff_time' => 'nullable|date_format:H:i',
            'meal_date' => 'required|date_format:Y-m-d',
            'meal_date_end' => 'nullable|date_format:Y-m-d',
        ]);

        if ($request->meal_date_end) {
            // Convert the 'meal_date' and 'meal_date_end' to DateTime instances
               $startDate = new \DateTime($request->meal_date);
               $endDate = new \DateTime($request->meal_date_end);
       
               // Loop through the date range
               while ($startDate <= $endDate) {
                   // Display the current date in 'Y-m-d' format (equivalent to toDateString())
                   
                   FoodSchedule::create([
                   'meal_type' => $request->meal_type,
                   'meal_value_multiplier' => $request->meal_value,
                   'cutoff_time' => $request->cutoff_time,
                   'meal_date' => $startDate->format('Y-m-d'),
                   'schedule_month' => $startDate->format('Y-m'),

               ]);
                   // Move to the next day
                   $startDate->modify('+1 day');
               }
       }else{

       FoodSchedule::create([
                   'meal_type' => $request->meal_type,
                   'meal_value_multiplier' => $request->meal_value,
                   'cutoff_time' => $request->cutoff_time,
                   'meal_date' => $request->meal_date,
                   'schedule_month' => $startDate->format('Y-m'),
               ]);
       }

        return redirect()->route('food_schedules.index')->with('success', 'Food schedule created successfully.');
    }

    public function edit($id)
    {
    $foodSchedule = FoodSchedule::findOrFail($id);
        return view('food_schedules.edit', compact('foodSchedule'));
    }

        // Update an existing food schedule
        public function update(Request $request, $id)
        {

            $request->validate([
                'meal_type' => 'required|string',
                'meal_value' => 'required|numeric',
                'cutoff_time' => 'nullable|date_format:H:i',
                'meal_date' => 'required|date',
            ]);

            $foodSchedule = FoodSchedule::findOrFail($id);
            $foodSchedule->update([
                'meal_type' => $request->meal_type,
                'meal_value_multiplier' => $request->meal_value,
                'cutoff_time' => $request->cutoff_time,
                'meal_date' => $request->meal_date,
            ]);
            return redirect()->route('food_schedules.index')->with('success', 'Food schedule updated successfully!');
        }
        
        public function destroy($id)
        {
            $foodSchedule = FoodSchedule::findOrFail($id);
            $foodSchedule->delete();
            return redirect()->route('food_schedules.index')->with('success', 'Food schedule deleted successfully!');
        }
}
