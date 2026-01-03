@extends('layouts.dashboard')

@section('title', 'Payment - BookMyAppointment')

@section('styles')
<style>
    .payment-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s;
    }
    
    .payment-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .payment-card.selected {
        border-color: #0d6efd;
        background-color: #f0f8ff;
    }
    
    .stripe-card-element {
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background: white;
    }
    
    .StripeElement--focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .StripeElement--invalid {
        border-color: #dc3545;
    }
    
    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
@endsection

@section('content')
<!-- Test Mode Warning -->
@if(Str::startsWith(config('services.stripe.key'), 'pk_test_'))
<div class="alert alert-warning">
    <i class="fas fa-vial me-2"></i>
    <strong>TEST MODE:</strong> You're using Stripe test mode. 
    Use test card: <code>4242 4242 4242 4242</code> with any future expiry, CVC, and ZIP.
</div>
@endif
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap 
align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Complete Payment</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('appointments') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Appointments
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Appointment Summary -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Appointment Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Service Details</h6>
                        <p class="mb-1">
                            <strong>Service:</strong> {{ $appointment->service->name }}
                        </p>
                        <p class="mb-1">
                            <strong>Duration:</strong> {{ $appointment->service->duration }} minutes
                        </p>
                        <p class="mb-1">
                            <strong>Professional:</strong> {{ $appointment->staff->full_name }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Appointment Time</h6>
                        <p class="mb-1">
                            <strong>Date:</strong> {{ $appointment->date->format('l, F j, Y') }}
                        </p>
                        <p class="mb-1">
                            <strong>Time:</strong> {{ $appointment->start_time->format('g:i A') }} 
                            - {{ $appointment->end_time->format('g:i A') }}
                        </p>
                        <p class="mb-0">
                            <strong>Status:</strong> 
                            <span class="badge bg-warning">{{ ucfirst($appointment->status) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Payment Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Select Payment Method</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="payment-card p-3 text-center selected" id="cardPayment">
                                    <i class="fas fa-credit-card fa-2x text-primary mb-2"></i>
                                    <h6>Credit/Debit Card</h6>
                                    <small class="text-muted">Visa, Mastercard, Amex</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="payment-card p-3 text-center" id="otherPayment" style="opacity: 0.6;">
                                    <i class="fas fa-university fa-2x text-secondary mb-2"></i>
                                    <h6>Other Methods</h6>
                                    <small class="text-muted">Coming soon</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Amount -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Payment Summary</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td>Service Fee:</td>
                                        <td class="text-end">${{ number_format($appointment->service->price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax (10%):</td>
                                        <td class="text-end">${{ number_format($appointment->service->price * 0.1, 2) }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <th>Total Amount:</th>
                                        <th class="text-end">${{ number_format($amount, 2) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Stripe Payment Form -->
                <div class="row">
                    <div class="col-12">
                        <form id="payment-form">
                            @csrf
                            
                            <!-- Card Element will be inserted here -->
                            <div class="mb-3">
                                <label for="card-element" class="form-label">Card Information</label>
                                <div id="card-element" class="stripe-card-element">
                                    <!-- Stripe Card Element will be inserted here -->
                                </div>
                                <div id="card-errors" role="alert" class="text-danger mt-2 small"></div>
                            </div>
                            
                            <!-- Billing Details -->
                            <div class="mb-3">
                                <label for="name-on-card" class="form-label">Name on Card</label>
                                <input type="text" class="form-control" id="name-on-card" 
                                       placeholder="John Smith" required>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-lock me-2"></i>
                                <strong>Secure Payment:</strong> Your payment information is encrypted and secure. 
                                We never store your card details.
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg" id="submit-button">
                                    <i class="fas fa-lock me-2"></i>
                                    Pay ${{ number_format($amount, 2) }}
                                </button>
                                
                                <button type="button" class="btn btn-outline-secondary" id="cancel-button">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Security Badges -->
        <div class="text-center">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i> PCI DSS Compliant
                <span class="mx-2">•</span>
                <i class="fas fa-lock me-1"></i> 256-bit SSL Encryption
                <span class="mx-2">•</span>
                <i class="fas fa-check-circle me-1"></i> Secure by Stripe
            </small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<script>
  // Check if Stripe key is configured
@if(!empty(config('services.stripe.key')))
    // Get Stripe publishable key
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();
@else
    // Show error if no Stripe key
    document.addEventListener('DOMContentLoaded', function() {
        alert('Stripe is not configured. Please contact administrator.');
    });
@endif
    
    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });
    
    // Mount card element
    cardElement.mount('#card-element');
    
    // Handle real-time validation errors
    cardElement.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const cancelButton = document.getElementById('cancel-button');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Disable submit button to prevent double submission
        submitButton.disabled = true;
        submitButton.innerHTML = 
            '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
        
        try {
            // Create checkout session
            const response = await fetch('{{ route("payment.checkout", $appointment) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const session = await response.json();
            
            if (session.error) {
                throw new Error(session.error);
            }
            
            // Redirect to Stripe Checkout
            const result = await stripe.redirectToCheckout({
                sessionId: session.id
            });
            
            if (result.error) {
                throw new Error(result.error.message);
            }
            
        } catch (error) {
            // Re-enable button
            submitButton.disabled = false;
            submitButton.innerHTML = 
                '<i class="fas fa-lock me-2"></i> Pay ${{ number_format($amount, 2) }}';
            
            // Show error
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            
            console.error('Error:', error);
        }
    });
    
    // Cancel button
    cancelButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to cancel this payment?')) {
            window.location.href = '{{ route("appointments") }}';
        }
    });
    
    // Payment method selection
    document.getElementById('cardPayment').addEventListener('click', function() {
        selectPaymentMethod('card');
    });
    
    document.getElementById('otherPayment').addEventListener('click', function() {
        alert('Other payment methods coming soon!');
    });
    
    function selectPaymentMethod(method) {
        // Remove selection from all
        document.querySelectorAll('.payment-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selection to clicked
        if (method === 'card') {
            document.getElementById('cardPayment').classList.add('selected');
        }
    }
</script>
@endsection