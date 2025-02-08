<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\DepositStatusNotification;
class DepositController extends Controller
{

    public function index(Request $request)
    {
    
        // Start with a query that fetches all deposits
        $query = Deposit::query();
    
        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }
    
        // Filter by user if provided (assuming you have a 'user_id' in your deposits table)
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
    
    // Apply month and year filters
    if ($request->has('month') && !empty($request->month)) {
        // If month is selected but year is not, use the current year
        $year = $request->has('year') && !empty($request->year) ? $request->year : now()->year;
        $query->whereMonth('deposited_at', $request->month)
              ->whereYear('deposited_at', $year);
    } elseif ($request->has('year') && !empty($request->year)) {
        // If year is selected but month is not, use the current month
        $month = now()->month;
        $query->whereYear('deposited_at', $request->year)
              ->whereMonth('deposited_at', $month);
    }

    // If no filters are set, order by current month and year
    if (!$request->has('month') && !$request->has('year')) {
        $query->whereYear('deposited_at', now()->year)
              ->whereMonth('deposited_at', now()->month);
    }
// Get user name (if user_id is provided)
$userName = $request->has('user_id') && $request->user_id ? User::find($request->get('user_id'))->name : 'All';
    // Order by most recent deposits, default ordering
    $deposits = $query->with('approvedBy')->orderBy('created_at', 'desc')->paginate(10);
 // Month and Year filters
 $month = $request->get('month') ?: now()->month;
 $year = $request->get('year') ?: now()->year;
    // Capture the filters for display in the view
    $filters = [
        'status' => $request->get('status'),
        'user_id' => $userName,
        'month' => $month,
        'year' => $year,
    ];
    
    // Calculate the total approved amount for the selected month, regardless of pagination
    $totalApprovedAmount = Deposit::where('status', 'approved')
                                  ->whereMonth('deposited_at', $month)
                                  ->whereYear('deposited_at', $year)
                                  ->sum('amount');

    // Calculate the total approved amount for the current page
    $currentPageApprovedAmount = $deposits->filter(function ($deposit) {
        return $deposit->status === 'approved';
    })->sum('amount');

        // Get all users for admin to select from
        $users = User::all();
        //$deposits = Deposit::where('status', 'pending')->get();
        return view('admin.deposits.index', compact('deposits','users', 'filters', 'totalApprovedAmount', 'currentPageApprovedAmount'));
    }
     /**
     * Show the form for creating a deposit on behalf of a user.
     */
    public function create()
    {
        // Get all users for admin to select from
        $users = User::all();

        return view('admin.deposits.create', compact('users'));
    }
/**
     * Store a newly created deposit in the database and approve it.
     */
    public function store(Request $request)
    {
        $va=$request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'deposit_date' => 'nullable|date',
            'deposit_time' => 'nullable|date_format:H:i',
            'payment_method' => 'required|string|max:255',
        ]);
// Use current date and time if the request values are null
$deposit_date = $request->deposit_date ?: Carbon::now()->toDateString(); // Use current date if null
$deposit_time = $request->deposit_time ?: Carbon::now()->toTimeString(); // Use current time if null

// Combine date and time into one Carbon instance
$deposited_date = Carbon::parse($deposit_date . ' ' . $deposit_time);
        // Create the deposit
        $deposit = Deposit::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'deposited_at' => $deposited_date, // Store the deposit date as the first day of the selected month
            'admin_approved_by' => auth()->user()->id,
            'status' => 'approved', // Directly approve the deposit
        ]);
        $user=User::find($request->user_id);

        $notification=$user->notify(new DepositStatusNotification($deposit,  'approved',auth()->user()->name));
       
        return redirect()->route('admin.deposits.index')->with('success', 'Deposit has been created and approved successfully.');
    }
    public function review(Deposit $deposit)
    {
        return view('admin.deposits.review', compact('deposit'));
    }
    public function approve(Deposit $deposit)
    {
        // Assuming the logged-in admin is already authenticated
        $admin = auth()->user();
        $deposit->approve($admin);

        return redirect()->route('admin.deposits.index')->with('success', 'Deposit approved successfully!');
    }
    
    public function reject(Deposit $deposit, Request $request)
{
    // Ensure comment is provided if rejection is happening
    $request->validate([
        'rejection_comment' => 'required|string|max:255',
    ]);

    $deposit->reject(auth()->user()->id,$request->input('rejection_comment'));

    return redirect()->route('admin.deposits.index')->with('success', 'Deposit rejected.');
}
}
