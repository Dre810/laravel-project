@extends('layouts.dashboard')

@section('title', 'Payment Successful - BookMyAppointment')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card text-center border-success">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i> Payment Successful!</h4>
            </div>
            <div class="card-body py-5">
                <div class="mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px;">
                        <i class="fas fa-check fa-3x"></i>
                    </div>
                    <h3>Thank You for Your Payment!</h3>
                    <p class="text-muted">Your appointment has been confirmed.</p>
                </div>
                
                @if(isset($payment))
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Payment Details</h5>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Transaction ID:</strong></p>
                                <code>{{ $payment->stripe_payment_id }}</code>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Amount Paid:</strong></p>
                                <h4 class="text-success">{{ $payment->formatted_amount }}</h4>
                            </div>
                        </div>
                        <p class="mb-0 mt-2">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i> 
                                Paid on {{ $payment->paid_at->format('M j, Y g:i A') }}
                            </small>
                        </p>
                    </div>
                </div>
                @endif
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Appointment Details</h5>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Service:</strong></p>
                                <p>{{ $appointment->service->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Professional:</strong></p>
                                <p>{{ $appointment->staff->full_name }}</p>
                            </div>
                        </div>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Date:</strong></p>
                                <p>{{ $appointment->date->format('l, F j, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Time:</strong></p>
                                <p>{{ $appointment->start_time->format('g:i A') }} - {{ $appointment->end_time->format('g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-envelope me-2"></i>
                    <strong>Confirmation Email Sent!</strong> 
                    We've sent a confirmation email to <strong>{{ auth()->user()->email }}</strong> 
                    with all the details.
                </div>
                
                <div class="d-grid gap-2 col-md-8 mx-auto">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                    </a>
                    <a href="{{ route('appointments') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar me-2"></i> View My Appointments
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i> Print Receipt
                    </button>
                </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-question-circle me-1"></i>
                        Need to make changes? Contact us at support@bookmyappointment.com
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection