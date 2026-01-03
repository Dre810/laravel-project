<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;

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

        // Payment routes
Route::get('/appointments/{appointment}/payment', [PaymentController::class, 'show'])
    ->name('payment.show');
    
Route::post('/appointments/{appointment}/checkout', [PaymentController::class, 'checkout'])
    ->name('payment.checkout');
    
Route::get('/payment/success/{appointment}', [PaymentController::class, 'success'])
    ->name('payment.success');
    
Route::get('/payment/cancel/{appointment}', [PaymentController::class, 'cancel'])
    ->name('payment.cancel');
    
    // Cancel appointment
    Route::post('/appointments/{appointment}/cancel', [DashboardController::class, 'cancelAppointment'])
        ->name('appointments.cancel');

        // Test Stripe connection
Route::get('/test-stripe', function() {
    try {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        // Try to create a test customer
        $customer = \Stripe\Customer::create([
            'email' => 'test@example.com',
            'name' => 'Test Customer',
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Stripe connection successful!',
            'customer_id' => $customer->id,
            'stripe_key' => substr(config('services.stripe.key'), 0, 20) . '...',
            'stripe_secret' => substr(config('services.stripe.secret'), 0, 20) . '...',
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'stripe_key' => config('services.stripe.key') ? 'Set' : 'Not set',
            'stripe_secret' => config('services.stripe.secret') ? 'Set' : 'Not set',
        ], 500);
    }
});
});

// Laravel Breeze routes (keep these at the end)
require __DIR__.'/auth.php';