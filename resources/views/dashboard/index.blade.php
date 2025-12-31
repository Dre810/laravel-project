@extends('layouts.dashboard')

@section('title', 'Dashboard - BookMyAppointment')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('booking.services') }}" class="btn btn-primary">
            <i class="fas fa-calendar-plus me-2"></i> Book New Appointment
        </a>
    </div>
</div>

<!-- Welcome Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="card-title">Welcome back, {{ Auth::user()->name }}! 👋</h3>
                        <p class="card-text mb-0">
                            You have <strong>{{ $stats['upcoming'] }}</strong> upcoming appointments.
                            @if($stats['upcoming'] > 0)
                                Your next appointment is coming up soon!
                            @else
                                Ready to book your next appointment?
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-calendar-alt fa-4x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Appointments</h6>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="bg-primary text-white rounded-circle p-3">
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Upcoming</h6>
                        <h3 class="mb-0">{{ $stats['upcoming'] }}</h3>
                    </div>
                    <div class="bg-warning text-white rounded-circle p-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Completed</h6>
                        <h3 class="mb-0">{{ $stats['completed'] }}</h3>
                    </div>
                    <div class="bg-success text-white rounded-circle p-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Cancelled</h6>
                        <h3 class="mb-0">{{ $stats['cancelled'] }}</h3>
                    </div>
                    <div class="bg-danger text-white rounded-circle p-3">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Upcoming Appointments</h5>
            </div>
            <div class="card-body">
                @if($upcomingAppointments->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5>No Upcoming Appointments</h5>
                        <p class="text-muted">You don't have any appointments scheduled.</p>
                        <a href="{{ route('booking.services') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i> Book Your First Appointment
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Staff</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td>
                                        <strong>{{ $appointment->service->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $appointment->service->duration }} min • ${{ $appointment->service->price }}</small>
                                    </td>
                                    <td>{{ $appointment->staff->full_name }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($appointment->date)->format('D, M j, Y') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status_color }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                            <form action="{{ route('appointments.cancel', $appointment) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('appointments') }}" class="btn btn-outline-secondary">
                            View All Appointments <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 70px; height: 70px;">
                    <i class="fas fa-calendar-plus fa-2x"></i>
                </div>
                <h5>Book New</h5>
                <p class="text-muted small">Schedule a new appointment with our professionals</p>
                <a href="{{ route('booking.services') }}" class="btn btn-primary w-100">
                    Book Now
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 70px; height: 70px;">
                    <i class="fas fa-history fa-2x"></i>
                </div>
                <h5>View History</h5>
                <p class="text-muted small">Check your past appointments and receipts</p>
                <a href="{{ route('appointments') }}" class="btn btn-outline-warning w-100">
                    View History
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 70px; height: 70px;">
                    <i class="fas fa-user-edit fa-2x"></i>
                </div>
                <h5>Profile</h5>
                <p class="text-muted small">Update your personal information and preferences</p>
                <a href="#" class="btn btn-outline-success w-100">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection