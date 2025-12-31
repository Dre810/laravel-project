@extends('layouts.app')

@section('title', 'Select Time - BookMyAppointment')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('booking.services') }}">Services</a></li>
                <li class="breadcrumb-item"><a href="{{ route('booking.staff', $service) }}">Staff</a></li>
                <li class="breadcrumb-item active">Select Time</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>Select Date & Time</h2>
                <p class="text-muted mb-0">
                    Booking <strong>{{ $service->name }}</strong> with 
                    <strong>{{ $staff->full_name }}</strong>
                    ({{ $service->duration }} min - ${{ $service->price }})
                </p>
            </div>
            <a href="{{ route('booking.staff', $service) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Staff
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Date Selection -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Select a Date</h5>
                <p class="text-muted mb-0 small">Available dates for the next 2 weeks</p>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($dates as $dateInfo)
                    <div class="col-6 col-md-3 mb-3">
                        <a href="?date={{ $dateInfo['date'] }}" 
                           class="btn btn-date {{ $dateInfo['is_selected'] ? 'btn-primary' : 'btn-outline-primary' }} w-100">
                            <div class="small">{{ $dateInfo['day_name'] }}</div>
                            <div class="fw-bold">{{ explode(', ', $dateInfo['display'])[1] }}</div>
                        </a>
                    </div>
                    @endforeach
                </div>
                
                <!-- Selected Date Info -->
                <div class="alert alert-info mt-3">
                    <i class="fas fa-calendar-day me-2"></i>
                    <strong>Selected:</strong> 
                    {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}
                    
                    @php
                        $workingHours = $staff->getWorkingHoursForDay(
                            strtolower(\Carbon\Carbon::parse($selectedDate)->englishDayOfWeek)
                        );
                    @endphp
                    
                    @if($workingHours && $workingHours->is_working)
                        | <i class="fas fa-clock me-1"></i> 
                        Working hours: {{ \Carbon\Carbon::parse($workingHours->start_time)->format('g:i A') }} 
                        - {{ \Carbon\Carbon::parse($workingHours->end_time)->format('g:i A') }}
                        
                        @if($workingHours->break_start && $workingHours->break_end)
                            | Break: {{ \Carbon\Carbon::parse($workingHours->break_start)->format('g:i A') }}
                            - {{ \Carbon\Carbon::parse($workingHours->break_end)->format('g:i A') }}
                        @endif
                    @else
                        | <span class="text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i> 
                            Not working this day
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Time Slots -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Available Time Slots</h5>
                <span class="badge bg-primary">{{ count($availableSlots) }} slots available</span>
            </div>
            <div class="card-body">
                @if(empty($availableSlots))
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h4>No Available Slots</h4>
                        <p class="text-muted">
                            {{ $staff->first_name }} is not available on the selected date 
                            or all slots are booked.
                        </p>
                        <p class="text-muted">Please select a different date.</p>
                    </div>
                @else
                    <div class="row" id="timeSlots">
                        @foreach($availableSlots as $slot)
                        <div class="col-md-4 col-sm-6 mb-3">
                            <button type="button" 
                                    class="time-slot btn {{ $slot['is_available'] ? 'btn-outline-primary' : 'btn-secondary' }} w-100"
                                    onclick="{{ $slot['is_available'] ? "selectTimeSlot('{$slot['start']}', '{$slot['display']}')" : '' }}"
                                    {{ $slot['is_available'] ? '' : 'disabled' }}>
                                <div class="fw-bold">{{ $slot['display'] }}</div>
                                <small>
                                    @if($slot['is_available'])
                                        <span class="text-success">
                                            <i class="fas fa-check-circle me-1"></i> Available
                                        </span>
                                    @else
                                        <span class="text-danger">
                                            <i class="fas fa-times-circle me-1"></i> Booked
                                        </span>
                                    @endif
                                </small>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Time slots are in 1-hour increments. Select your preferred time.
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Booking Summary Sidebar -->
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Booking Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Service:</h6>
                    <p class="mb-1">{{ $service->name }}</p>
                    <small class="text-muted">{{ $service->duration }} minutes</small>
                </div>
                
                <div class="mb-3">
                    <h6>Professional:</h6>
                    <p class="mb-0">{{ $staff->full_name }}</p>
                    <small class="text-muted">{{ $staff->specialty }}</small>
                </div>
                
                <div class="mb-3">
                    <h6>Selected Date:</h6>
                    <p id="selectedDateDisplay" class="mb-1">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}
                    </p>
                    
                    <h6 class="mt-3">Selected Time:</h6>
                    <p id="selectedTimeDisplay" class="text-muted mb-0">Not selected yet</p>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Service Fee:</span>
                        <span>${{ $service->price }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tax (10%):</span>
                        <span>${{ number_format($service->price * 0.1, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span>${{ number_format($service->price * 1.1, 2) }}</span>
                    </div>
                </div>
                
                <!-- Booking Form -->

            <!-- Add this after the form opening tag -->
@guest
<div class="alert alert-warning mb-3">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Login Required:</strong> You need to 
    <a href="{{ route('login') }}" class="alert-link">login</a> or 
    <a href="{{ route('register') }}" class="alert-link">register</a> 
    to book an appointment.
</div>
@endguest

                <form action="{{ route('booking.book') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                    <input type="hidden" name="date" id="selectedDate" value="{{ $selectedDate }}">
                    <input type="hidden" name="time" id="selectedTime">
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Special Instructions (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" 
                                  placeholder="Any special requests or notes..."></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                      <button type="submit" class="btn btn-success btn-lg" 
        {{ auth()->check() ? '' : 'disabled' }} id="bookButton">
                            <i class="fas fa-calendar-check me-2"></i> Confirm Booking
                        </button>
                        
                        <a href="{{ route('booking.staff', $service) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Choose Different Staff
                        </a>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-lock me-1"></i> Your information is secure
                        </small>
                        <br>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i> Free cancellation up to 24 hours before
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .btn-date {
        padding: 10px 5px;
        text-align: center;
        transition: all 0.3s;
    }
    
    .btn-date:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .time-slot {
        padding: 15px 10px;
        text-align: center;
        transition: all 0.3s;
    }
    
    .time-slot:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .time-slot.selected {
        background-color: #0d6efd !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }
</style>
@endsection

@section('scripts')
<script>
    let selectedSlot = null;
    
    function selectTimeSlot(time, display) {
        // Update selected slot UI
        document.querySelectorAll('.time-slot').forEach(btn => {
            btn.classList.remove('selected', 'btn-primary');
            btn.classList.add('btn-outline-primary');
        });
        
        event.target.classList.remove('btn-outline-primary');
        event.target.classList.add('selected', 'btn-primary');
        
        // Update form fields
        document.getElementById('selectedTime').value = time;
        document.getElementById('selectedTimeDisplay').textContent = display;
        
        // Enable booking button
        document.getElementById('bookButton').disabled = false;
        document.getElementById('bookButton').innerHTML = 
            '<i class="fas fa-calendar-check me-2"></i> Book Appointment at ' + display;
        
        selectedSlot = { time, display };
    }
    
    // Form validation
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        if (!document.getElementById('selectedTime').value) {
            e.preventDefault();
            alert('Please select a time slot first.');
            return;
        }
        
        // Optional: Add loading animation
        const bookButton = document.getElementById('bookButton');
        bookButton.disabled = true;
        bookButton.innerHTML = 
            '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
    });
    
    // Initialize with selected date
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('selectedDate').value = '{{ $selectedDate }}';
    });
</script>
@endsection