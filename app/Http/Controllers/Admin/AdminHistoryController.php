<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminHistory;
use App\Models\User;
use Carbon\Carbon;

class AdminHistoryController extends Controller
{
    // Show the form for creating a new admin history
    public function create()
    {
        // Fetch all users and pass them to the view
        $users = User::all();
        return view('admin.admin_histories.create', compact('users'));
    }

    // Store a newly created admin history in storage
    public function store(Request $request)
    {
        // Validate the form input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'admin_start_date' => 'required|date',
            'admin_end_date' => 'nullable|date|after_or_equal:admin_start_date',
        ]);

        // Create a new AdminHistory
        AdminHistory::create([
            'user_id' => $validated['user_id'],
            'admin_start_date' => $validated['admin_start_date'],
            'admin_end_date' => $validated['admin_end_date'] ?? null,
        ]);

        return redirect()->route('admin_histories.index')->with('success', 'Admin history created successfully.');
    }

    // Show the list of admin histories
    public function index()
    {
        // Fetch all admin histories and pass them to the view
        $adminHistories = AdminHistory::with('user')->get();
        return view('admin.admin_histories.index', compact('adminHistories'));
    }


    public function edit(AdminHistory $adminHistory)
    {
        // Make sure the dates are Carbon instances
        $adminHistory->admin_start_date = Carbon::parse($adminHistory->admin_start_date);
        $adminHistory->admin_end_date = $adminHistory->admin_end_date ? Carbon::parse($adminHistory->admin_end_date) : null;
    
        $users = User::all();
        return view('admin.admin_histories.edit', compact('adminHistory', 'users'));
    }
    

public function update(Request $request, AdminHistory $adminHistory)
{
    // Validate the input data
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'admin_start_date' => 'required|date',
        'admin_end_date' => 'nullable|date|after_or_equal:admin_start_date',
    ]);

    // Update the admin history with new data
    $adminHistory->update([
        'user_id' => $validated['user_id'],
        'admin_start_date' => $validated['admin_start_date'],
        'admin_end_date' => $validated['admin_end_date'] ?? null,
    ]);

    // Redirect to the index page with a success message
    return redirect()->route('admin_histories.index')->with('success', 'Admin history updated successfully.');
}
public function destroy(AdminHistory $adminHistory)
{
    // Delete the specified admin history
    $adminHistory->delete();

    // Redirect back with success message
    return redirect()->route('admin_histories.index')->with('success', 'Admin history deleted successfully.');
}

}
