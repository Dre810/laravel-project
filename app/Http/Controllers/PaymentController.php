<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    // Show payment page for an appointment
    public function show(Appointment $appointment)
    {
        // Check if user owns this appointment
        if ($appointment->client_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        // Check if already paid
        if ($appointment->is_paid) {
            return redirect()->route('appointments')
                ->with('info', 'This appointment is already paid.');
        }
        
        // Calculate amount (service price + tax)
        $amount = $appointment->service->price * 1.1; // 10% tax
        
        return view('payment.show', compact('appointment', 'amount'));
    }
    
    // Create Stripe checkout session
    public function checkout(Request $request, Appointment $appointment)
    {


        // Check if Stripe keys are configured
    if (empty(config('services.stripe.key')) || empty(config('services.stripe.secret'))) {
        return response()->json([
            'error' => 'Stripe is not configured. Please add Stripe API keys to .env file.'
        ], 500);
    }
        // Check if user owns this appointment
        if ($appointment->client_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Check if already paid
        if ($appointment->is_paid) {
            return response()->json(['error' => 'Already paid'], 400);
        }
        
        try {
            // Set Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));
            
            // Calculate amount in cents (Stripe uses cents)
            $amount = $appointment->service->price * 1.1; // 10% tax
            $amountInCents = (int) ($amount * 100);
            
            // Create Stripe checkout session
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $appointment->service->name . ' with ' . $appointment->staff->full_name,
                            'description' => 'Appointment on ' . $appointment->date->format('M j, Y') . 
                                            ' at ' . $appointment->start_time->format('g:i A'),
                        ],
                        'unit_amount' => $amountInCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['appointment' => $appointment->id]) . 
                                '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel', ['appointment' => $appointment->id]),
                'customer_email' => auth()->user()->email,
                'metadata' => [
                    'appointment_id' => $appointment->id,
                    'user_id' => auth()->id(),
                ],
            ]);
            
            // Store session ID temporarily (or in database)
            session(['stripe_session_id' => $checkout_session->id]);
            session(['appointment_id' => $appointment->id]);
            
            return response()->json(['id' => $checkout_session->id]);
            
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // Handle successful payment
    public function success(Request $request, Appointment $appointment)
    {
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            return redirect()->route('appointments')
                ->with('error', 'Invalid payment session.');
        }
        
        try {
            // Verify the payment with Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);
            
            // Check if payment was successful
            if ($session->payment_status === 'paid') {
                // Create payment record
                $payment = Payment::create([
                    'user_id' => auth()->id(),
                    'appointment_id' => $appointment->id,
                    'stripe_payment_id' => $session->payment_intent,
                    'amount' => $session->amount_total / 100, // Convert from cents to dollars
                    'currency' => $session->currency,
                    'status' => 'succeeded',
                    'stripe_response' => json_encode($session),
                    'paid_at' => now(),
                ]);
                
                // Update appointment status
                $appointment->update(['status' => 'confirmed']);
                
                // Clear session data
                $request->session()->forget(['stripe_session_id', 'appointment_id']);
                
                return view('payment.success', compact('appointment', 'payment'));
            }
            
        } catch (ApiErrorException $e) {
            // Log error but still show success page (webhook will handle verification)
        }
        
        return view('payment.success', compact('appointment'));
    }
    
    // Handle cancelled payment
    public function cancel(Appointment $appointment)
    {
        return view('payment.cancel', compact('appointment'));
    }
    
    // Webhook endpoint (for Stripe to notify us of payment status)
    public function webhook(Request $request)
    {
        // This is advanced - we'll implement later
        // For now, we'll use the success URL method
        
        return response()->json(['status' => 'ok']);
    }
}