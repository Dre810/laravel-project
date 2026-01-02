@extends('layouts.dashboard')

@section('title', 'Payment Cancelled - BookMyAppointment')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card text-center border-warning">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-times-circle me-2"></i> Payment Cancelled</h4>
            </div>
            <div class="card-body py-5">
                <div class="mb-4">
                    <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px;">
                        <i class="fas fa-times fa-3x"></i>
                    </div>
                    <h3>Payment Not Completed</h3>
                    <p class="text-muted">Your payment was cancelled or interrupted.</p>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Don't Lose Your Appointment!</h5>
                        <p class="mb-3">
                            Your appointment is still reserved but not confirmed. 
                            You have <strong>24 hours</strong> to complete the payment 
                            before the slot becomes available to others.
                        </p>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Appointment Details:</strong><br>
                            {{ $appointment->service->name }} with {{ $appointment->staff->full_name }}<br>
                            {{ $appointment->date->format('l, F j, Y') }} at {{ $appointment->start_time->format('g:i A') }}
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 col-md-8 mx-auto">
                    <a href="{{ route('payment.show', $appointment) }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-credit-card me-2"></i> Try Payment Again
                    </a>
                    <a href="{{ route('appointments') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Appointments
                    </a>
                </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-phone me-1"></i>
                        Need help? Call us at (123) 456-7890
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection