@extends('layouts.app')

@section('title', 'Welcome - BookMyAppointment')

@section('content')
<div class="row align-items-center min-vh-75">
    <div class="col-md-6">
        <h1 class="display-4 fw-bold mb-4">
            Book Appointments <br>
            <span class="text-primary">Made Easy</span>
        </h1>
        <p class="lead mb-4">
            Schedule appointments with professionals in just a few clicks. 
            No more phone calls, no more waiting. Book instantly online!
        </p>
        <div class="d-grid gap-2 d-md-flex">
            <a href="{{ route('booking.services') }}" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-calendar-plus me-2"></i> Book Now
            </a>
            <a href="#" class="btn btn-outline-secondary btn-lg px-4">
                <i class="fas fa-play-circle me-2"></i> How it Works
            </a>
        </div>
        
        <div class="mt-5">
            <h5 class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Why Choose Us?</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="fas fa-bolt text-warning"></i> Instant Booking</h5>
                            <p class="small mb-0">Book appointments 24/7 from any device.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="fas fa-bell text-info"></i> Smart Reminders</h5>
                            <p class="small mb-0">Get email & SMS reminders before appointments.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="fas fa-calendar-alt text-success"></i> Easy Rescheduling</h5>
                            <p class="small mb-0">Change or cancel appointments with one click.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
             alt="Booking System" class="img-fluid rounded shadow-lg">
    </div>
</div>

<!-- Services Preview -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="text-center mb-4">Popular Services</h3>
        <div class="row">
            @php
                $popularServices = \App\Models\Service::where('is_active', true)->limit(4)->get();
            @endphp
            
            @foreach($popularServices as $service)
            <div class="col-md-3 mb-4">
                <div class="card h-100 booking-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-cut fa-lg"></i>
                            </div>
                        </div>
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <p class="card-text small text-muted">{{ Str::limit($service->description, 60) }}</p>
                        <p class="fw-bold text-primary">${{ $service->price }}</p>
                        <a href="{{ route('booking.staff', $service) }}" class="btn btn-outline-primary btn-sm">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection