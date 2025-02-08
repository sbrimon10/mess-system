<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodPreference;
use App\Models\User;
use App\Models\FoodSchedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class FoodPreferenceController extends Controller
{
    public function index(Request $request)
    {
    //      // Check if the user is an admin or super admin
    // if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) {
    //     // Admins and super admins can see everyone's food preferences for today
    //     $foodPreferences = FoodPreference::whereDate('meal_date', now()
    //     ->toDateString())
    //     ->with('user') // Eager load the 'user' relationship
    //     ->get();
    // } else {
    //     // Regular users can only see their own food preferences for today
    //     $foodPreferences = FoodPreference::where('user_id', Auth::user()->id)
    //                                      ->whereDate('meal_date', now()->toDateString())
    //                                      ->get();
    // }


    //     return view('food_preferences.index', compact('foodPreferences'));

   $date = $request->get('date', now()->toDateString());

        // Retrieve food preferences, grouped by meal_date
        $foodPreferences = FoodPreference::with(['user', 'foodSchedule'])
            ->whereDate('meal_date', $date)
            ->orderBy('meal_date')
            ->get()
            ->groupBy('meal_date');
            if(!$foodPreferences){
                $foodPreferences=[];
            }
        return view('food_preferences.indexx', compact('foodPreferences', 'date'));
    }
    // Show the form to create food preferences
    public function create()
    {
        $user = User::findOrFail(Auth::user()->id);
        $foodSchedules = FoodSchedule::whereDate('meal_date', now()->toDateString())->get();

        return view('food_preferences.create', compact('foodSchedules', 'user'));
    }
        // Store the food preferences for a user
        public function store(Request $request)
        {
        //     $request->validate([
        //         'food_schedule_id' => 'required|exists:food_schedules,id',
        //         'will_eat' => 'required|boolean',
        //     ]);
    
        //     FoodPreference::create([
        //         'user_id' => Auth::user()->id,
        //         'food_schedule_id' => $request->food_schedule_id,
        //         'will_eat' => $request->will_eat,
        //         'meal_date' => now()->toDateString(), // Today's date
        //         'month' => now()->format('Y-m'),
        //     ]);
    
        //     return redirect()->route('food_preferences.index')->with('success', 'Food preference saved successfully!');
        // 
// Validate the request
$validated = $request->validate([
    'will_eat' => 'nullable|boolean',
    'food_schedule_id' => 'required|exists:food_schedules,id', // Validate that food_schedule_id exists
    'auto_meal' => 'nullable|boolean', // Validate auto_meal option
]);

$foodSchedule = FoodSchedule::find($validated['food_schedule_id']);
        $mealDate = Carbon::parse($foodSchedule->meal_date); // Ensure it's a Carbon instance

        // If 'auto_meal' is checked, sync the preferences with future meal dates
        if ($request->has('auto_meal') && $request->auto_meal) {
            // Get all FoodSchedules with meal_date >= current meal_date
            $foodSchedules = FoodSchedule::where('meal_date', '>=', $mealDate)->where('meal_type','=',$foodSchedule->meal_type)->get();

            foreach ($foodSchedules as $schedule) {
                
                // Create a new food preference for each future meal date
                FoodPreference::create([
                    'user_id' => auth()->id(),
                    'food_schedule_id' => $schedule->id,
                    'will_eat' => $validated['will_eat']??'no',
                    'meal_date' => $schedule->meal_date,
                    'month' => Carbon::parse($schedule->meal_date)->format('Y-m'), // Use Carbon to format the date
                    'auto_meal' => true, // Mark it as auto
                ]);
            }

            return redirect()->route('food_preferences.index')->with('success', 'Preferences saved and set to auto.');
        }

        // Store a single preference if 'auto' is not checked
        FoodPreference::create([
            'user_id' => auth()->id(),
            'food_schedule_id' => $validated['food_schedule_id'],
            'will_eat' => $validated['will_eat']??'no',
            'meal_date' => $mealDate, // Use the Carbon instance for meal_date
            'month' => $mealDate->format('Y-m'), // Format using Carbon
            'auto_meal' => false, // Not auto
        ]);

        return redirect()->route('food_preferences.index')->with('success', 'Preference saved.');
    
    
    }
    
        // Show the form to edit food preferences
        public function edit($id)
        {
            $user = User::findOrFail(Auth::user()->id);
            $foodPreferences = FoodPreference::findOrFail($id);
            $foodSchedule = FoodSchedule::where('id', $foodPreferences->food_schedule_id)->first();
    //dd($foodSchedule);
            return view('food_preferences.edit', compact('foodPreferences', 'foodSchedule', 'user'));
        }

            // Update the food preferences
    // public function update(Request $request,  $id)
    // {
    //     if (Auth::user()->hasPermissionTo('food-preference-edit')) {
    //         $request->validate([
    //             'food_schedule_id.*' => 'required|exists:food_schedules,id',
    //             'will_eat.*' => 'required|in:yes,no',
    //         ]);
    
    //         $foodPreference = FoodPreference::findOrFail($id);
    //         $foodPreference->update([
    //             'food_schedule_id' => $request->food_schedule_id,
    //             'will_eat' => $request->will_eat,
    //             'meal_date' => now()->toDateString(), // Today's date
    //         ]);
    
    //         return redirect()->route('food_preferences.index')->with('success', 'Food preference updated successfully!');
    //     } else {
    //         return redirect()->route('food_preferences.index')->with('error', 'Food preference Not Updated!');
    //     }
        
    // }
    public function update(Request $request, $id)
{
    if (!Auth::user()->hasPermissionTo('food-preference-edit')) {
        abort(403, 'You do not have permission to update food preferences.');
    }
    // Validate the input first
    $validated = $request->validate([
        'food_schedule_id.*' => 'required|exists:food_schedules,id',
        'will_eat.*' => 'required|in:yes,no',
    ]);

    // Initialize a flag to track if any meal is blocked
    $blockedMeals = [];

    // Loop through the validated food schedule and check if the user has modified any field
    foreach ($validated['food_schedule_id'] as $index => $foodScheduleId) {
        // Get the FoodSchedule record for the given food schedule ID
        $foodSchedule = FoodSchedule::findOrFail($foodScheduleId);
        
        // Get the FoodPreference record for the given member and food schedule
        $foodPreference = FoodPreference::where('user_id', Auth::user()->id)
            ->where('food_schedule_id', $foodScheduleId)
            ->where('meal_date', now()->toDateString()) // Assuming the meal date is today
            ->first();

        // Check if the 'will_eat' field was changed
        if ($foodPreference && $request->input('will_eat.' . $foodScheduleId) !== $foodPreference->will_eat) {
            // The 'will_eat' value was changed, now check the cutoff time
            if ($this->mealTimePassed($foodSchedule)) {
                $blockedMeals[] = $foodSchedule->meal_time; // Add to the blocked list if the time has passed
            } else {
                // Proceed to update the meal preference if allowed
                FoodPreference::updateOrCreate(
                    [
                        'user_id' => Auth::user()->id,
                        'food_schedule_id' => $foodScheduleId,
                        'meal_date' => now()->toDateString(),
                        'month' => now()->format('Y-m')
                    ],
                    [
                        'will_eat' => $validated['will_eat'][$index]
                    ]
                );
            }
        } else {
            // If the 'will_eat' value hasn't changed, no need to check the cutoff time
            // Simply skip updating this meal preference
        }
    }

    // After processing, check if any meals are blocked
    if (!empty($blockedMeals)) {
        // Construct a message with the blocked meal times
        $blockedMealTimes = implode(', ', $blockedMeals);
        return redirect()->route('food_preferences.edit', ['food_preference' => $id])
            ->with('error', "You cannot update the following meal(s) after the allowed time: $blockedMealTimes.");
    }

    return redirect()->route('food_preferences.edit', ['food_preference' => $id])->with('success', 'Meal preferences updated!');
}


// Update the preferences if user unchecks 'auto'
public function updateAutoPreference($id, Request $request)
{
    $preference = FoodPreference::findOrFail($id);
    $foodSchedule = FoodSchedule::find($preference->id);
    // Update the preference auto field
    $preference->update([
        'auto_meal' => $request->auto_meal??0, // Set it to true or false
    ]);

    // If unchecking auto, delete future preferences
    if (!$request->auto_meal) {
       $food= FoodPreference::where('user_id', auth()->id()) // Ensure it's the same user
            ->where('meal_date', '>', $preference->meal_date) // Only delete future meals
            ->where('auto_meal', true) // Only delete those marked as auto
            ->whereHas('foodSchedule', function ($query) use ($foodSchedule) {
                // Ensure the meal type matches the one in the schedule
                $query->where('meal_type', $foodSchedule->meal_type);
            })->get();
        print_r($food);
    }

    //return redirect()->route('food_preferences.index')->with('success', 'Auto preference updated.');
}

public function editmealsbydate($meal_date){

    $meals=FoodPreference::where('meal_date','=',$meal_date)
            ->where('user_id',auth()->id())->get();
         

            return view('food_preferences.mealedit', compact('meals'));
}



protected function mealTimePassed(FoodSchedule $foodSchedule)
{
    // Get the current date and time in the application's timezone
    $currentDate = Carbon::now(config('app.timezone')); // Get current time in app timezone
    
    // Get the meal date and cutoff time from the food schedule
    $mealDate = Carbon::parse($foodSchedule->meal_date)->timezone(config('app.timezone')); // Ensure the meal date is in the correct timezone
    $cutoffTime = Carbon::parse($foodSchedule->cutoff_time)->timezone(config('app.timezone')); // Ensure cutoff time is in the correct timezone

    // Special case for breakfast cutoff time being set late in the evening (e.g., 11:30 PM)
    if ($foodSchedule->meal_time == 'breakfast' && $cutoffTime->hour >= 18) {
        // If the cutoff time is set in the evening (after 6 PM), we consider it as the previous day's cutoff
        $mealDate->subDay(); // Subtract 1 day for breakfast cutoff times after 6 PM
    }

    // Combine the meal date with the cutoff time to get the full date-time of the cutoff
    $cutoffDateTime = $mealDate->copy()->setTimeFromTimeString($cutoffTime->toTimeString());

    // Check if the current time is after the cutoff time for the current meal date
    if ($currentDate->gt($cutoffDateTime)) {
        return true; // Meal time has passed, prevent update
    }

    // Otherwise, return false, meaning the meal time has not passed yet
    return false;
}
}
