@extends('layouts.app')

@section('title', 'Select Staff - BookMyAppointment')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('booking.services') }}">Services</a></li>
                <li class="breadcrumb-item active">Select Staff</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>Select a Professional</h2>
                <p class="text-muted mb-0">
                    For: <strong>{{ $service->name }}</strong> 
                    ({{ $service->duration }} min - ${{ $service->price }})
                </p>
            </div>
            <a href="{{ route('booking.services') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Services
            </a>
        </div>
    </div>
</div>

<div class="row">
    @foreach($staff as $member)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($member->photo_url)
                            <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}" 
                                 class="staff-avatar mb-3">
                        @else
                            <div class="staff-avatar d-inline-flex align-items-center justify-content-center bg-secondary text-white mb-3">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        @endif
                        <div class="rating mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                            <small class="text-muted">(4.5)</small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $member->full_name }}</h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-award me-1"></i> {{ $member->specialty }}
                        </p>
                        
                        <p class="mb-3">{{ $member->bio }}</p>
                        
                        <div class="mb-3">
                            <h6>Availability This Week:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                @endphp
                                @foreach($days as $day)
                                    @php
                                        $working = $member->workingHours->where('day_of_week', $day)->first();
                                    @endphp
                                    <span class="badge {{ $working && $working->is_working ? 'bg-success' : 'bg-secondary' }}">
                                        {{ strtoupper(substr($day, 0, 3)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i> Next available: Today, 2:30 PM
                                </small>
                            </div>
                            <a href="{{ route('booking.slots', ['service' => $service->id, 'staff' => $member->id]) }}" 
                               class="btn btn-primary">
                                Select & Choose Time <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($staff->isEmpty())
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No staff members are currently available for this service. Please check back later.
        </div>
    </div>
</div>
@endif
@endsection