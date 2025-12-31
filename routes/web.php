<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes (no login required)
Route::get('/', function () {
    return view('booking.welcome');
})->name('home');

Route::get('/booking/services', [BookingController::class, 'showServices'])
    ->name('booking.services');

Route::get('/booking/staff/{service}', [BookingController::class, 'showStaff'])
    ->name('booking.staff');

Route::get('/booking/slots/{service}/{staff}', [BookingController::class, 'showSlots'])
    ->name('booking.slots');

// Protected routes (login required)
Route::middleware('auth')->group(function () {
    // Booking submission requires login
    Route::post('/booking/book', [BookingController::class, 'bookAppointment'])
        ->name('booking.book');
    
    // User dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    // User appointments
    Route::get('/appointments', [DashboardController::class, 'appointments'])
        ->name('appointments');
    
    // Cancel appointment
    Route::post('/appointments/{appointment}/cancel', [DashboardController::class, 'cancelAppointment'])
        ->name('appointments.cancel');
});

// Laravel Breeze routes (keep these at the end)
require __DIR__.'/auth.php';