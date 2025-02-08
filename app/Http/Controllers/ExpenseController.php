<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;
use App\Models\User;
class ExpenseController extends Controller
{
    public function create()
    {
        $categories = Category::all();
         // Get all users for admin to select from
         $users = User::all();
        return view('admin.expenses.create', compact('categories', 'users'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);
    
        $expense =Expense::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'expense_date' => $request->expense_date,
        ]);
        // Assign the current user
    $expense->users()->attach(auth()->id());
    
        // Attach the selected category
        if ($request->has('category_id')) {
            $expense->categories()->attach($request->category_id);
        }
    
        return redirect()->route('expenses.index')->with('success', 'Expense added successfully');
    
    }
    public function index()
    {
        $expenses = Expense::with('categories')->get();

        return view('admin.expenses.index', compact('expenses'));
    }

    public function edit(Expense $expense)
    {
        $expense->load('categories');
        $categories = Category::all();
       
        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, $id)
{
    // Validate the input data
    $request->validate([
        'type' => 'required',
        'amount' => 'required|numeric',
        'description' => 'nullable|string',
        'expense_date' => 'required|date',
        'category_id' => 'nullable|exists:categories,id',  // Ensure categories exist
    ]);

    // Find the expense by ID
    $expense = Expense::findOrFail($id);

    // Update the expense fields (type, amount, etc.)
    $expense->update([
        'type' => $request->type,
        'amount' => $request->amount,
        'description' => $request->description,
        'expense_date' => $request->expense_date,
    ]);

    // Detach all previous categories (optional if you want to reset categories)
    $expense->categories()->detach();

    // Attach the new categories
    if ($request->has('category_id')) {
        $expense->categories()->attach($request->category_id);
    }

    // Redirect with a success message
    return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
}

public function destroy(Expense $expense)
{
    // Detach the categories associated with the expense (if any)
    $expense->categories()->detach();

    // Now delete the expense
    $expense->delete();

    // Redirect with success message
    return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully');

}
}
