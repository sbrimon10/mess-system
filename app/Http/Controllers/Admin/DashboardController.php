<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\FoodPreference;
use App\Models\UserInfo;
use App\Notifications\SystemNotification;
use App\Events\SystemNotificationEvent;
use App\Events\MyEvent;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        
        $userscount = User::where('status', 'active')->count();

        $month = $request->get('month') ?: now()->format('Y-m');
        $year = $request->get('year') ?: now()->year;

        // Get the total approved amount for the month
        $TotalApprovedAmount = Deposit::where('status', 'approved')
            ->whereMonth('deposited_at', Carbon::parse($month)->month)
            ->whereYear('deposited_at', Carbon::parse($month)->year)
            ->sum('amount');
            // Get total expenses for the month
        $totalExpenses = Expense::whereMonth('expense_date', Carbon::parse($month)->month)
        ->whereYear('expense_date', Carbon::parse($month)->year)
        ->sum('amount');
         // Get total meals count for the month
        //  $totalMeals = FoodPreference::whereMonth('meal_date', Carbon::parse($month)->month)
        //  ->whereYear('meal_date', Carbon::parse($month)->year)
        //  ->where('will_eat', 'yes')
        //  ->count();
        $totalMeals = FoodPreference::whereMonth('meal_date', Carbon::parse($month)->month)
    ->whereYear('meal_date', Carbon::parse($month)->year)
    ->where('will_eat', 'yes')
    ->with('foodSchedule') // Eager load the related foodSchedule
    ->get() // Get the collection of FoodPreferences
    ->sum(function ($meal) {
        return $meal->foodSchedule->meal_value_multiplier ?? 0; // Sum the value_multiplier for each related FoodSchedule
    });

         $previousMonth = Carbon::parse($month)->subMonth();
         // Get total meals count for the previous month
$previousMonthMeals = FoodPreference::whereMonth('meal_date', $previousMonth)
->whereYear('meal_date', Carbon::parse($month)->year)
->where('will_eat', 'yes')
->count();

// Check if previous month meals exist to avoid division by zero
if ($previousMonthMeals > 0) {
// Calculate the growth percentage
$MealsgrowthPercentage = (($totalMeals - $previousMonthMeals) / $previousMonthMeals) * 100;
} else {
// Handle case where there were no meals in the previous month
$MealsgrowthPercentage = 0;
}

        return view('admin.dashboard', compact('userscount', 'TotalApprovedAmount', 'totalExpenses', 'totalMeals', 'previousMonthMeals', 'MealsgrowthPercentage'));
    }

public function showTodaysMeals()
{
    // Get today's date
    $today = Carbon::today();

// Fetch today's meals, join with food_schedules and users_info
$meals = FoodPreference::whereDate('meal_date', $today)
->with(['foodSchedule', 'user']) // Eager load foodSchedule and user relationships
->get()
->groupBy('user_id'); // Group meals by user_id

// Sum the meal_value_multiplier for the meals per type where will_eat is 'yes'
$totalBreakfast = $meals->flatten()->where('foodSchedule.meal_type', 'breakfast')->where('will_eat', 'yes')->sum(function ($meal) {
    return $meal->foodSchedule->meal_value_multiplier;
});

$totalLunch = $meals->flatten()->where('foodSchedule.meal_type', 'lunch')->where('will_eat', 'yes')->sum(function ($meal) {
    return $meal->foodSchedule->meal_value_multiplier;
});

$totalDinner = $meals->flatten()->where('foodSchedule.meal_type', 'dinner')->where('will_eat', 'yes')->sum(function ($meal) {
    return $meal->foodSchedule->meal_value_multiplier;
});;

// Pass data to the view
return view('admin.index', compact('meals', 'totalBreakfast', 'totalLunch', 'totalDinner'));

}
public function showTodaysMealsForAuthUser()
{
    // Get the authenticated user
    $user = Auth::user();


// Get the current month and year
$month = Carbon::now()->format('Y-m'); // Format as 'YYYY-MM'
    
// Get the start and end date of the current month
$startOfMonth = Carbon::parse($month)->startOfMonth();
$endOfMonth = Carbon::parse($month)->endOfMonth();

// Fetch all meals for the user in the current month
$meals = FoodPreference::whereBetween('meal_date', [$startOfMonth, $endOfMonth])
    ->where('user_id', $user->id)
    ->with(['foodSchedule'])
    ->get();

    $eatenMeals = $meals->filter(function ($meal) {
        $currentDateTime = Carbon::now();
    
        // Check if the meal is in the past or if it's today and the time has passed
        if ($meal->meal_date < $currentDateTime->toDateString()) {
            return $meal->will_eat == 'yes'; // Past meal that is marked to eat
        }
    
        // For today's meal, check if the current time is past the cutoff time
        if ($meal->meal_date == $currentDateTime->toDateString()) {
            $cutoffTime = Carbon::parse($meal->foodSchedule->cutoff_time);
            return $meal->will_eat == 'yes' && $currentDateTime->toTimeString() > $cutoffTime->toTimeString();
        }
    
        return false;
    })->sum(function ($meal) {
        // For each filtered meal, add the meal's multiplier value
        return $meal->foodSchedule->meal_value_multiplier; // Default to 0 if multiplier is null
    });
    
   
    // Calculate will eat meals value (meals that are either in the future or today but not yet passed)
    $willEatMeals= $meals->filter(function ($meal) {
        $currentDateTime = Carbon::now();
    
        // Check if the meal is in the future or if it's today and the time hasn't passed yet
        if ($meal->meal_date > $currentDateTime->toDateString()) {
            return $meal->will_eat == 'yes'; // Future meal that is marked to eat
        }
    
        // For today's meal, check if the current time is before the cutoff time
        if ($meal->meal_date == $currentDateTime->toDateString()) {
            $cutoffTime = Carbon::parse($meal->foodSchedule->cutoff_time);
            return $meal->will_eat == 'yes' && $currentDateTime->lessThanOrEqualTo($cutoffTime);
        }
    
        return false;
    })->sum(function ($meal) {
        // For each filtered meal, add the meal's multiplier value
        return $meal->foodSchedule->meal_value_multiplier;
    });
$totalMeals = $eatenMeals + $willEatMeals; // Total meals is a combination of eaten and will eat.

// Pass data to the view
return view('common_pages.index', compact('meals', 'user', 'startOfMonth', 'endOfMonth', 'eatenMeals', 'willEatMeals', 'totalMeals'));
}


// Function to send a system notification to all users
public function sendSystemNotification(Request $request)
{
    

    // Validate the request (e.g., make sure the message is provided)
    $validated = $request->validate([
        'message' => 'required|string|max:255',
    ]);

    //$user = User::Find(7);

    // Create the notification instance
// $notification = new SystemNotification($validated['message']);

// // Trigger the notification
// $user->notify($notification);

// // Dispatch the event with the notification
// event(new MyEvent('hello world'));
// event(new SystemNotificationEvent('test notification event'));
event(new SystemNotificationEvent($validated['message']));
    // // Get all users (you can modify this to target specific users if needed)
    //  $users = User::all(); // You can use where() to filter users if necessary

    // // // Send the notification to each user
    // foreach ($users as $user) {
    //     $user->notify(new SystemNotification($validated['message']));
    // }

    return back()->with('success', 'System notification sent to all users.');
}
public function viewSystemNotification(){
   return view('admin.send-notification');
}
}
