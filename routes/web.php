<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

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

// Public Booking Routes
Route::get('/', function () {
    return view('booking.welcome');
})->name('home');

Route::get('/booking/services', [BookingController::class, 'showServices'])
    ->name('booking.services');

Route::get('/booking/staff/{service}', [BookingController::class, 'showStaff'])
    ->name('booking.staff');

Route::get('/booking/slots/{service}/{staff}', [BookingController::class, 'showSlots'])
    ->name('booking.slots');

Route::post('/booking/book', [BookingController::class, 'bookAppointment'])
    ->name('booking.book');

// Simple login/logout routes (without laravel/ui package)
Route::get('/login', function () {
    return 'Login page - We\'ll build this later';
})->name('login');

Route::get('/register', function () {
    return 'Register page - We\'ll build this later';
})->name('register');