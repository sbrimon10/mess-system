<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use Carbon\Carbon;
class DepositController extends Controller
{
   // Ensure the user is authenticated
   public function __construct()
   {
       //$this->middleware('auth');
   }

   /**
    * Show the user's deposit history
    *
    * @return \Illuminate\View\View
    */
   public function index()
   {
       $deposits = Deposit::where('user_id', auth()->id())->get(); // Get the user's deposits
       return view('deposits.index', compact('deposits'));
   }


   public function create()
   {
       return view('deposits.create');
   }
   public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:' . date('Y'),
            'payment_method' => 'required|string|max:255',
        ]);

        // Create the deposit
        Deposit::create([
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'deposited_at' => Carbon::create($request->year, $request->month, 1), // Store the deposit date as the first day of the selected month
            'status' => 'pending',
        ]);

        return redirect()->route('deposits.index')->with('success', 'Deposit has been submitted for review.');
    }
    public function show(Deposit $deposit)
    {

        return view('deposits.show', compact('deposit'));
    }
    
    
}