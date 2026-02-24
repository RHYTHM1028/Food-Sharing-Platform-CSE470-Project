<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Marketplace routes
Route::get('/marketplace', [ListingController::class, 'index'])->name('marketplace');
Route::get('/marketplace/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Donations routes
Route::get('/donations', [DonationController::class, 'index'])->name('donations');
Route::get('/donations/{listing}', [DonationController::class, 'show'])->name('donations.show');

// Volunteer Task Routes
Route::get('/volunteer', [App\Http\Controllers\TaskController::class, 'index'])->name('volunteer');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [App\Http\Controllers\AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::get('login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
});

// Protected routes requiring authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/listings', [DashboardController::class, 'listings'])->name('dashboard.listings');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/donations', [DashboardController::class, 'donations'])->name('dashboard.donations');
    Route::get('/dashboard/tasks', [DashboardController::class, 'tasks'])->name('dashboard.tasks');
    Route::get('/dashboard/deliveries', [DashboardController::class, 'deliveries'])->name('dashboard.deliveries');
    
    // Listings Routes
    Route::get('/dashboard/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/dashboard/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/dashboard/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/dashboard/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/dashboard/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::post('/dashboard/listings/{listing}/donate', [ListingController::class, 'donate'])->name('listings.donate');
    
    // Orders Routes
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/create-delivery', [App\Http\Controllers\DeliveryTaskController::class, 'createForOrder'])->name('deliveries.create-for-order');
    
    // Donations Routes
    Route::post('/donations/{listing}/reserve', [DonationController::class, 'reserve'])->name('donations.reserve');
    
    // Volunteer Tasks Routes
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/volunteer', [TaskController::class, 'volunteer'])->name('tasks.volunteer');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/{task}/cancel', [TaskController::class, 'cancel'])->name('tasks.cancel');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.delete');
    
    // Delivery Task Routes
    Route::get('/deliveries', [App\Http\Controllers\DeliveryTaskController::class, 'index'])->name('deliveries');
    Route::get('/deliveries/{deliveryTask}', [App\Http\Controllers\DeliveryTaskController::class, 'show'])->name('deliveries.show');
    Route::post('/deliveries/{deliveryTask}/accept', [App\Http\Controllers\DeliveryTaskController::class, 'acceptDelivery'])->name('deliveries.accept');
    Route::post('/deliveries/{deliveryTask}/complete', [App\Http\Controllers\DeliveryTaskController::class, 'complete'])->name('deliveries.complete');
    Route::post('/deliveries/{deliveryTask}/cancel', [App\Http\Controllers\DeliveryTaskController::class, 'cancel'])->name('deliveries.cancel');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Reservation Routes
    Route::post('/donations/{id}/reserve', [App\Http\Controllers\DonationController::class, 'reserve'])
        ->name('donations.reserve');
    Route::get('/dashboard/reservations', [App\Http\Controllers\ReservationController::class, 'index'])
        ->name('dashboard.reservations');
    Route::get('/dashboard/reservations/{id}', [App\Http\Controllers\ReservationController::class, 'show'])
        ->name('reservations.show');
    Route::post('/reservations/{id}/cancel', [App\Http\Controllers\ReservationController::class, 'cancel'])
        ->name('reservations.cancel');
    
    // Volunteer routes
    Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer');
    Route::get('/volunteer/tasks/{id}', [VolunteerController::class, 'show'])->name('volunteer.show');
    Route::get('/volunteer/tasks/create', [VolunteerController::class, 'create'])->name('volunteer.create');
    
    // Task management routes
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/{id}/volunteer', [TaskController::class, 'volunteer'])->name('tasks.volunteer');
    Route::post('/tasks/{id}/cancel', [TaskController::class, 'cancel'])->name('tasks.cancel');
    Route::post('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::delete('/tasks/{id}', [TaskController::class, 'delete'])->name('tasks.delete');
    
    // Dashboard volunteer routes
    Route::get('/dashboard/volunteer', [VolunteerController::class, 'dashboard'])->name('dashboard.volunteer');
});

// Password reset routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');
