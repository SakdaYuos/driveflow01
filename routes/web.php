<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CarController       as AdminCarController;
use App\Http\Controllers\Admin\BookingController   as AdminBookingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PaymentController   as AdminPaymentController;

// Customer Controllers
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\CarController    as CustomerCarController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\TripController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;

/*
|--------------------------------------------------------------------------
| Root → redirect to login
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN PANEL → /admin/*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cars
    Route::resource('cars', AdminCarController::class);
    Route::patch('/cars/{car}/status', [AdminCarController::class, 'updateStatus'])->name('cars.status');

    // Bookings
    Route::resource('bookings', AdminBookingController::class);
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::patch('/bookings/{booking}/cancel',  [AdminBookingController::class, 'cancel'])->name('bookings.cancel');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Payments
    Route::get('/payments',                    [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{booking}/toggle', [AdminPaymentController::class, 'toggle'])->name('payments.toggle');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER PANEL
|--------------------------------------------------------------------------
*/

// Public pages
Route::get('/home',       [HomeController::class, 'index'])->name('home');
Route::get('/cars',       [CustomerCarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CustomerCarController::class, 'show'])->name('cars.show');

// Authenticated customer routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/cars/{car}/checkout',     [CustomerPaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/cars/{car}/book',        [CustomerBookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/done',  [CustomerBookingController::class, 'confirm'])->name('booking.confirm');

    Route::get('/trips',                   [TripController::class, 'index'])->name('trips.index');
    Route::get('/trips/{booking}',         [TripController::class, 'show'])->name('trips.show');
    Route::post('/trips/{booking}/cancel', [TripController::class, 'cancel'])->name('trips.cancel');
});
