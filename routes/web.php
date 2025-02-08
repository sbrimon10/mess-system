<?php
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FoodScheduleController;
use App\Http\Controllers\FoodPreferenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\DepositController as UserDepositController;
use App\Http\Controllers\Admin\AdminHistoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\MealsClosingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\RolePermissionController;


// Livewire
use App\Livewire\CategoryCrud;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/mess-system/public/livewire/livewire.js', $handle);
});
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/mess-system/public/livewire/update', $handle);
        
});



Route::get('/', function () {
    return view('welcome');
})->middleware(['auth']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->middleware('role:admin|super-admin')->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->middleware('role:admin|super-admin')->name('users.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/user/{user}/view/meals', [UserController::class, 'usermeals'])->name('users.view.meals');
   Route::get('/users/{id}/edit',[UserController::class, 'edit'])->middleware('role:admin|super-admin')->name('users.edit');
   Route::put('/users/{user}',[UserController::class, 'update'])->middleware('role:admin|super-admin')->name('users.update');
   Route::delete('/users/{user}',[UserController::class, 'destroy'])->middleware('role:admin|super-admin')->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



///test routes
Route::get('/test', [UserController::class, 'test'])->name('users.test');

Route::get('/generate-pdf', [TestController::class, 'generatePDF']);
Route::get('/test/notifications', [TestController::class, 'sendtestnotification'])->name('sendtestnotification');
Route::get('test/role',[TestController::class,'role']);


Route::get('/admin', function () {
    echo 'You are admin';
})->middleware('role:super-admin');


Route::resource('food_schedules', FoodScheduleController::class)->middleware('auth','role:super-admin|admin');
Route::resource('food_preferences', FoodPreferenceController::class);
Route::patch('/food-preferences/{id}/auto', [FoodPreferenceController::class, 'updateAutoPreference'])->name('food_preferences.updateAutoPreference');

 Route::get('/meals/today', [DashboardController::class, 'showTodaysMealsForAuthUser'])->name('meals.today');


 Route::patch('notifications/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
 Route::middleware('auth')->group(function () {
    Route::post('admin/send-notification', [DashboardController::class, 'sendSystemNotification'])
         ->name('admin.sendSystemNotification');
Route::get('admin/send-notification', [DashboardController::class, 'viewSystemNotification'])->name('admin.viewSystemNotification');
});
Route::get('admin/send-notification', [DashboardController::class, 'viewSystemNotification'])->name('admin.viewSystemNotification');
Route::get('notifications/', [NotificationController::class, 'index'])->name('notifications.index');

/// All Admin Routes listed
Route::middleware(['auth', 'role:super-admin|admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/meals/today', [DashboardController::class, 'showTodaysMeals'])->name('admin.showTodaysMeals');
});

//Super Admin Routes
Route::middleware(['auth', 'role:super-admin'])->group(function () {
    Route::resource('admin_histories', AdminHistoryController::class);
Route::resource('admin/permissions', PermissionController::class);
Route::get('/admin/roles', [RolePermissionController::class, 'index'])->name('admin.roles');
Route::get('/admin/roles/{role}/permissions', [RolePermissionController::class, 'edit'])->name('admin.roles.permissions.edit');
Route::post('/admin/roles/{role}/permissions', [RolePermissionController::class, 'update'])->name('admin.roles.permissions.update');
});



// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('deposits', [DepositController::class, 'index'])->name('deposits.index');

    Route::get('deposits/create', [DepositController::class, 'create'])->name('deposits.create');
    Route::post('deposits', [DepositController::class, 'store'])->name('deposits.store');
    
    Route::get('deposits/{deposit}/review', [DepositController::class, 'review'])->name('deposits.review'); // Add review route
    Route::post('deposits/{deposit}/approve', [DepositController::class, 'approve'])->name('deposits.approve');
    Route::post('deposits/{deposit}/reject', [DepositController::class, 'reject'])->name('deposits.reject');
});

Route::middleware('auth')->group(function () {
    Route::resource('expenses', ExpenseController::class);
    //Route::resource('categories', CategoryController::class);
    
Route::get('/categories', CategoryCrud::class);
});
// User Routes
Route::get('/deposits', [UserDepositController::class, 'index'])->name('deposits.index');
Route::get('/deposits/{deposit}/view', [UserDepositController::class, 'show'])->name('deposits.show');
Route::get('/deposits/create', [UserDepositController::class, 'create'])->name('deposits.create');
Route::post('/deposits', [UserDepositController::class, 'store'])->name('deposits.store');

Route::get('/meals/{meal_date}/edit', [FoodPreferenceController::class, 'editmealsbydate'])->name('meals.editmealsbydate');



Route::get('/meals', [MealsClosingController::class, 'index'])->name('meals.index');
Route::get('/reports', [MealsClosingController::class, 'reports'])->name('meals.reports');


// Route::prefix('user/')->group(function () {
//     Route::resource('food_preferences', FoodPreferenceController::class);
// });

//Route examples
// use App\Http\Controllers\FoodScheduleController;
// use App\Http\Controllers\FoodPreferenceController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\DepositController;

// // Food Schedules (Admin and Super Admin can manage)
// Route::prefix('food-schedules')->group(function () {
//     // List food schedules (All authenticated users can view)
//     Route::get('/', [FoodScheduleController::class, 'index'])->middleware('role:super-admin|admin|user')->name('food-schedules.index');

//     // Only Super Admin and Admin can create, edit, or delete food schedules
//     Route::middleware('role:super-admin|admin')->group(function () {
//         Route::get('/create', [FoodScheduleController::class, 'create'])->name('food-schedules.create');
//         Route::post('/', [FoodScheduleController::class, 'store'])->name('food-schedules.store');
//         Route::get('/{foodSchedule}/edit', [FoodScheduleController::class, 'edit'])->name('food-schedules.edit');
//         Route::put('/{foodSchedule}', [FoodScheduleController::class, 'update'])->name('food-schedules.update');
//         Route::delete('/{foodSchedule}', [FoodScheduleController::class, 'destroy'])->name('food-schedules.destroy');
//     });
// });

// // Food Preferences (Admin and Super Admin can manage)
// Route::prefix('food-preferences')->group(function () {
//     Route::get('/', [FoodPreferenceController::class, 'index'])->middleware('role:super-admin|admin|user')->name('food-preferences.index');

//     // Only Super Admin and Admin can create, edit, or delete food preferences
//     Route::middleware('role:super-admin|admin')->group(function () {
//         Route::get('/create', [FoodPreferenceController::class, 'create'])->name('food-preferences.create');
//         Route::post('/', [FoodPreferenceController::class, 'store'])->name('food-preferences.store');
//         Route::get('/{foodPreference}/edit', [FoodPreferenceController::class, 'edit'])->name('food-preferences.edit');
//         Route::put('/{foodPreference}', [FoodPreferenceController::class, 'update'])->name('food-preferences.update');
//         Route::delete('/{foodPreference}', [FoodPreferenceController::class, 'destroy'])->name('food-preferences.destroy');
//     });
// });

// // Users (Super Admin and Admin can manage users)
// Route::prefix('users')->group(function () {
//     Route::get('/', [UserController::class, 'index'])->middleware('role:super-admin|admin')->name('users.index');

//     // Only Super Admin can create, edit, or delete users
//     Route::middleware('role:super-admin')->group(function () {
//         Route::get('/create', [UserController::class, 'create'])->name('users.create');
//         Route::post('/', [UserController::class, 'store'])->name('users.store');
//         Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
//         Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
//         Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
//     });
// });

// // Deposits (Admin and Super Admin can manage)
// Route::prefix('deposits')->group(function () {
//     Route::get('/', [DepositController::class, 'index'])->middleware('role:super-admin|admin|user')->name('deposits.index');

//     // Only Super Admin and Admin can create, edit, or delete deposits
//     Route::middleware('role:super-admin|admin')->group(function () {
//         Route::get('/create', [DepositController::class, 'create'])->name('deposits.create');
//         Route::post('/', [DepositController::class, 'store'])->name('deposits.store');
//         Route::get('/{deposit}/edit', [DepositController::class, 'edit'])->name('deposits.edit');
//         Route::put('/{deposit}', [DepositController::class, 'update'])->name('deposits.update');
//         Route::delete('/{deposit}', [DepositController::class, 'destroy'])->name('deposits.destroy');
//     });
// });
