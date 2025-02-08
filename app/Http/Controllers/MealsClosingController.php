<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\User;
use App\Models\FoodPreference;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealsClosingController extends Controller
{
    public function index(Request $request)
    {
        // Get the month and year from request, or default to the current month/year
        $month = $request->get('month') ?: now()->format('Y-m');
        $year = $request->get('year') ?: now()->year;

        // Get the total approved amount for the month
        $approvedTotal = Deposit::where('status', 'approved')
            ->whereMonth('deposited_at', Carbon::parse($month)->month)
            ->whereYear('deposited_at', Carbon::parse($month)->year)
            ->sum('amount');

        // Get total expenses for the month
        $totalExpenses = Expense::whereMonth('expense_date', Carbon::parse($month)->month)
            ->whereYear('expense_date', Carbon::parse($month)->year)
            ->sum('amount');

        // Get extra charges (assuming these are a specific category in the expenses table)
        $extraCharges = Expense::where('type', 'extra')
            ->whereMonth('expense_date', Carbon::parse($month)->month)
            ->whereYear('expense_date', Carbon::parse($month)->year)
            ->sum('amount');

        // Get total meals count for the month
        $totalMeals = FoodPreference::whereMonth('meal_date', Carbon::parse($month)->month)
            ->whereYear('meal_date', Carbon::parse($month)->year)
            ->where('will_eat', 'yes')
            ->count();

        // Optionally, get previous month's data (if needed)
        $previousMonth = Carbon::parse($month)->subMonth();
        $previousApprovedTotal = Deposit::where('status', 'approved')
            ->whereMonth('deposited_at', $previousMonth->month)
            ->whereYear('deposited_at', $previousMonth->year)
            ->sum('amount');

        $previousTotalExpenses = Expense::whereMonth('expense_date', $previousMonth->month)
            ->whereYear('expense_date', $previousMonth->year)
            ->sum('amount');

        $previousExtraCharges = Expense::where('type', 'extra')
            ->whereMonth('expense_date', $previousMonth->month)
            ->whereYear('expense_date', $previousMonth->year)
            ->sum('amount');

        $previousTotalMeals = FoodPreference::whereMonth('meal_date', $previousMonth->month)
            ->whereYear('meal_date', $previousMonth->year)
            ->where('will_eat', 'yes')
            ->count();

        // Pass all the data to the view
        return view('admin.meals_closing.index', compact(
            'approvedTotal', 'totalExpenses', 'extraCharges', 'totalMeals',
            'previousApprovedTotal', 'previousTotalExpenses', 'previousExtraCharges', 'previousTotalMeals',
            'month', 'year'
        ));
    }
    public function reports(Request $request)
    {
        // Get the month and year from the request, or default to the current month/year
        $month = $request->get('month') ?: now()->format('Y-m');
        $year = $request->get('year') ?: now()->year;

        // Get all users
        $users = User::all();

        // Loop through each user to get their data
        $userStats = $users->map(function ($user) use ($month, $year) {
            // Calculate total meals eaten by the user
            $totalMeals = FoodPreference::where('user_id', $user->id)
                ->where('will_eat', 'yes')
                ->whereMonth('meal_date', Carbon::parse($month)->month)
                ->whereYear('meal_date', Carbon::parse($month)->year)
                ->count();

            // Calculate total deposits made by the user
            $totalDeposits = Deposit::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereMonth('deposited_at', Carbon::parse($month)->month)
                ->whereYear('deposited_at', Carbon::parse($month)->year)
                ->sum('amount');

            // Calculate due (For example, total meal cost - total deposits, you can adjust this logic based on your needs)
            // Assuming there is a fixed amount per meal, you could have something like this:
            $mealCost = 10; // For example, each meal costs 10
            $totalMealCost = $totalMeals * $mealCost;
            $dueAmount = $totalMealCost - $totalDeposits;

            return (object) [
                'name' => $user->name,
                'totalMeals' => $totalMeals,
                'totalDeposits' => $totalDeposits,
                'dueAmount' => $dueAmount
            ];
        });

        return view('admin.meals_closing.report', compact('userStats', 'month', 'year'));
    }
}
