<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\FoodPreference;
use App\Models\FoodSchedule;
use Carbon\Carbon;
class UserController extends Controller
{
    public function test(){
        $user = Auth::user();
       
      echo  $user->roles; // See the roles assigned to the first user
// Check permissions for the user
//echo $user->hasPermissionTo('role-create'); // Returns true or false
if ($user->hasPermissionTo('role-create')) {
    echo 'You have permission to create roles';
}else{
    echo 'You do not have permission to create roles';
}


    }
    /**
     * Show the users list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    // Get the current month in 'Y-m' format (e.g., '2025-01')
    $currentMonth = now()->format('Y-m');

    // Get all users with the count of meals they ate in the current month
    $users = User::withCount(['foodPreferences' => function ($query) use ($currentMonth) {
        $query->where('month', $currentMonth)  // Filter for the current month
              ->where('will_eat', 'yes');  // Only count meals they will eat
    }])->with('roles')->with('userInfo')
    ->leftJoin('users_info', 'users.id', '=', 'users_info.user_id')  // Join with userInfo
    ->orderBy('users_info.room_number', 'asc')
    ->paginate(10);
    
    // Pass the users with meal counts to the view
    return view('user.index', compact('users'));
    }

    public function create()
    {
        $user = auth()->user();
        $roles = [];
        $defaultRole = null;
    
        if ($user->hasRole('super-admin')) {
            $roles = Role::all();
            $defaultRole = Role::where('name', 'user')->first();
        } elseif ($user->hasRole('admin')) {
            $roles = Role::whereIn('name', ['admin', 'user'])->get();
            $defaultRole = Role::where('name', 'user')->first();
        } elseif ($user->hasRole('user')) {
            $roles = Role::where('name', 'user')->get();
            $defaultRole = $roles->first();  // Only 'user' role available
        }
    
        return view('user.create', compact('roles', 'defaultRole'));
    }
    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'nullable|string|unique:users,username|max:255',
        'email' => 'nullable|email|unique:users,email|max:255',
        'phone' => 'required|regex:/^\+?[0-9]\d{0,14}$/|unique:users,phone|max:15,',
        'password' => 'required|string|min:6',
        'role' => 'required|exists:roles,id',
        'room_number' => 'nullable|string',
    ]);

    // Get the currently authenticated user
    $authenticatedUser = Auth::user();

    // Get the selected role ID from the request
    $selectedRole = Role::find($request->role);

    // Check if the authenticated user has the capacity to assign the selected role
    if ($authenticatedUser->hasRole('super-admin')) {
        // Super admin can assign any role
    } elseif ($authenticatedUser->hasRole('admin')) {
        // Admin can only assign 'admin' or 'user' roles
        if (!in_array($selectedRole->name, ['admin', 'user'])) {
            return redirect()->back()->withErrors(['role' => 'You do not have permission to assign this role.']);
        }
    } elseif ($authenticatedUser->hasRole('user')) {
        // A regular user cannot assign any roles
        return redirect()->back()->withErrors(['role' => 'You do not have permission to assign a role.']);
    }

    // Create the new user
    $user = new User();
    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->password = Hash::make($request->password);
   
    $user->save();
// Create or update the associated UserInfo
$user->userInfo()->create([
    'room_number' => $request->room_number,
]);
    // Assign the selected role to the new user
    $user->assignRole($selectedRole);
// Sync the permissions for the assigned role (optional, if you need to ensure role permissions are synced)
    // This will ensure the user gets all permissions associated with the assigned role
    $user->syncPermissions($selectedRole->permissions);

    // Redirect to the users index or another page with a success message
    return redirect()->route('users.index')->with('success', 'User created successfully!');
}
    public function show(User $user)
    {
         // Eager load roles and permissions
         $permissions = $user->getPermissionsViaRoles();
        // get the names of the user's roles
    $roles = $user->getRoleNames(); // Returns a collection

        return view('user.show', compact('user','permissions','roles'));
    }
    public function edit($id)
    {
        $user = User::with('userInfo')->findOrFail($id);
        // Get the roles the current authenticated user can assign
    $authenticatedUser = auth()->user();
     // Get the authenticated user
    
    $roles = [];

    // Determine which roles the logged-in user can assign
    if ($authenticatedUser->hasRole('super-admin')) {
        $roles = Role::all(); // Super admin can assign any role
    } elseif ($authenticatedUser->hasRole('admin')) {
        $roles = Role::whereIn('name', ['admin', 'user'])->get(); // Admin can assign admin and user
    } elseif ($authenticatedUser->hasRole('user')) {
        $roles = Role::where('name', 'user')->get(); // Regular users can only assign 'user' role
    }
      // If the authenticated user is trying to edit their own profile, allow it
    if ($authenticatedUser->id === $user->id) {
       // Return the view with user data, roles, and the current user's role
    return view('user.edit', compact('user', 'roles'));
    }
     // Prevent admin from editing super-admin or other admins
     if ($authenticatedUser->hasRole('admin') && ($user->hasRole('super-admin') || $user->hasRole('admin'))) {
        return redirect()->route('users.index')->with('error', 'You do not have permission to edit super-admin or other admin users.');
    }
    return view('user.edit', compact('user', 'roles'));
    }   
    public function update(Request $request, User $user)
{
// Initialize validation rules
$validationRules = [
    'name' => 'required|string|max:255',
    'role' => 'required|exists:roles,id', // Ensure the role exists
    'password' => 'nullable|string|min:6', // Password validation (optional)
    'room_number' => 'nullable|string',
];

// Only validate username and email uniqueness if they are changed
if ($request->input('username') != $user->username) {
    $validationRules['username'] = 'required|string|max:255|unique:users,username';
}

if ($request->input('email') != $user->email) {
    $validationRules['email'] = 'required|string|email|max:255|unique:users,email';
}

if ($request->input('phone') != $user->phone) {
    $validationRules['phone'] = 'required|regex:/^\+?[0-9]\d{1,14}$/|max:15|unique:users,phone';
}

// Validate the request data with the dynamically set rules
$request->validate($validationRules);
$authenticatedUser = auth()->user();

// Prevent admin from editing super-admin or other admin users
if ($authenticatedUser->hasRole('admin') && 
($user->hasRole('super-admin') || ($user->hasRole('admin') && !$authenticatedUser->is($user)))) {
    return redirect()->route('users.index')->with('error', 'You do not have permission to edit super-admin or other admin users.');
}
// Track if any changes were made
$isUpdated = false;

// Check if the user data is different from the input and update only if necessary
if ($request->input('name') != $user->name) {
    $user->name = $request->input('name');
    $isUpdated = true;
}

if ($request->input('username') != $user->username) {
    $user->username = $request->input('username');
    $isUpdated = true;
}

if ($request->input('email') != $user->email) {
    $user->email = $request->input('email');
    $isUpdated = true;
}

if ($request->input('phone') != $user->phone) {
    $user->phone = $request->input('phone');
    $isUpdated = true;
}

// if ($request->input('room_number') != $user->userInfo->room_number) {
//     $user->userInfo->room_number = $request->input('room_number');
//     $isUpdated = true;
// }
if ($request->input('room_number') != optional($user->userInfo)->room_number) {
    // Check if the room_number exists in the userInfo record and update it
    if ($user->userInfo) {
        $user->userInfo->room_number = $request->input('room_number');
        $user->userInfo->save();
    } else {
        // If no userInfo exists, create a new one
        $user->userInfo()->create([
            'room_number' => $request->input('room_number'),
        ]);
    }
    $isUpdated = true;
}


// Check if password is provided and update it (only if changed)
if ($request->has('password') && !empty($request->input('password'))) {
    if ($request->input('password') != $user->password) {
        $user->password = bcrypt($request->input('password'));
        $isUpdated = true;
    }
}

// Update the user's role if it has changed
if ($request->input('role') != $user->roles->first()->id) {
    // Find the role by ID
    
    $role = Role::findOrFail($request->input('role'));
    // Prevent non-super-admin users from assigning super-admin role
    if ($role->name === 'super-admin' && !$authenticatedUser->hasRole('super-admin')) {
        return redirect()->route('users.edit', $user->id)->with('error', 'You do not have permission to assign the super-admin role.');
    }

    // Prevent non-admin users from assigning admin roles
    if ($role->name === 'admin' && !$authenticatedUser->hasRole('super-admin') && !$authenticatedUser->hasRole('admin')) {
        return redirect()->route('users.edit', $user->id)->with('error', 'You do not have permission to assign the admin role.');
    }

    // Prevent users from changing their own role to admin or super-admin
    if ($authenticatedUser->is($user) && ($role->name === 'admin' || $role->name === 'super-admin')) {
        return redirect()->route('users.edit', $user->id)->with('error', 'You cannot assign yourself the admin or super-admin role.');
    }
    $user->syncRoles([$role->name]);
    
    $user->syncPermissions($role->permissions);
    $isUpdated = true;
}

// If anything was updated, save the user
if ($isUpdated) {
    $user->save();
    return redirect()->route('users.edit', $user->id)->with('success', 'User updated successfully!');
}

// If nothing was changed, return with a message
return redirect()->route('users.edit', $user->id)->with('info', 'No changes made.');
}

public function usermeals(User $user)
{

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



public function destroy(User $user)
{
    // Get the authenticated user
    $authenticatedUser = auth()->user();

    // Check if the authenticated user is trying to delete themselves
    if ($authenticatedUser->is($user)) {
        return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
    }

    // Super-admin can delete any user (admin, user, super-admin)
    if ($authenticatedUser->hasRole('super-admin')) {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // Admin cannot delete super-admin users
    if ($authenticatedUser->hasRole('admin') && $user->hasRole('super-admin')) {
        return redirect()->route('users.index')->with('error', 'You do not have permission to delete a super-admin.');
    }

    // Admin can delete only user roles (not other admins or super-admins)
    if ($authenticatedUser->hasRole('admin') && $user->hasRole('admin')) {
        return redirect()->route('users.index')->with('error', 'You do not have permission to delete another admin.');
    }

    // Regular users cannot delete any user, including admins or super-admins
    if ($authenticatedUser->hasRole('user')) {
        return redirect()->route('users.index')->with('error', 'You do not have permission to delete any users.');
    }

    // If none of the above conditions matched, allow the deletion (fallback)
    $user->delete();
    return redirect()->route('users.index')->with('success', 'User deleted successfully.');
}

}
