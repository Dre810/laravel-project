@extends('layouts.dashboard')

@section('title', 'My Appointments - BookMyAppointment')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Appointments</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('booking.services') }}" class="btn btn-primary">
            <i class="fas fa-calendar-plus me-2"></i> Book New
        </a>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="btn-group" role="group">
                            <a href="{{ route('appointments') }}" 
                               class="btn {{ request('status') ? 'btn-outline-primary' : 'btn-primary' }}">
                                All ({{ $appointments->total() }})
                            </a>
                            <a href="{{ route('appointments') }}?status=upcoming" 
                               class="btn {{ request('status') == 'upcoming' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Upcoming
                            </a>
                            <a href="{{ route('appointments') }}?status=completed" 
                               class="btn {{ request('status') == 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Completed
                            </a>
                            <a href="{{ route('appointments') }}?status=cancelled" 
                               class="btn {{ request('status') == 'cancelled' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Cancelled
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('appointments') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search appointments..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Appointments Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($appointments->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h4>No Appointments Found</h4>
                        <p class="text-muted">You haven't booked any appointments yet.</p>
                        <a href="{{ route('booking.services') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i> Book Your First Appointment
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service</th>
                                    <th>Professional</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                <tr>
                                    <td>#{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <strong>{{ $appointment->service->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $appointment->service->duration }} min</small>
                                    </td>
                                    <td>
                                        {{ $appointment->staff->full_name }}
                                        <br>
                                        <small class="text-muted">{{ $appointment->staff->specialty }}</small>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($appointment->date)->format('M j, Y') }}
                                        <br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} 
                                            - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status_color }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                        @if($appointment->is_past && $appointment->status == 'confirmed')
                                            <br>
                                            <small class="text-danger">(Past)</small>
                                        @endif
                                    </td>
                                    <td>
                                        ${{ $appointment->service->price }}
                                        <br>
                                        <small class="text-muted">Paid</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#appointmentModal{{ $appointment->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(($appointment->status === 'pending' || $appointment->status === 'confirmed') && !$appointment->is_past)
                                                <form action="{{ route('appointments.cancel', $appointment) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        
                                        <!-- Appointment Details Modal -->
                                        <div class="modal fade" id="appointmentModal{{ $appointment->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Appointment Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
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
                                                                    <strong>Price:</strong> ${{ $appointment->service->price }}
                                                                </p>
                                                                <p class="mb-1">
                                                                    <strong>Category:</strong> {{ $appointment->service->category }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Professional</h6>
                                                                <p class="mb-1">
                                                                    <strong>Name:</strong> {{ $appointment->staff->full_name }}
                                                                </p>
                                                                <p class="mb-1">
                                                                    <strong>Specialty:</strong> {{ $appointment->staff->specialty }}
                                                                </p>
                                                                <p class="mb-1">
                                                                    <strong>Email:</strong> {{ $appointment->staff->email }}
                                                                </p>
                                                                <p class="mb-1">
                                                                    <strong>Phone:</strong> {{ $appointment->staff->phone }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Appointment Time</h6>
                                                                <p class="mb-1">
                                                                    <strong>Date:</strong> 
                                                                    {{ \Carbon\Carbon::parse($appointment->date)->format('l, F j, Y') }}
                                                                </p>
                                                                <p class="mb-1">
                                                                    <strong>Time:</strong> 
                                                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} 
                                                                    - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                                                </p>
                                                                <p class="mb-1">
                                                                    <strong>Booked On:</strong> 
                                                                    {{ $appointment->created_at->format('M j, Y g:i A') }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Status & Notes</h6>
                                                                <p class="mb-1">
                                                                    <strong>Status:</strong> 
                                                                    <span class="badge bg-{{ $appointment->status_color }}">
                                                                        {{ ucfirst($appointment->status) }}
                                                                    </span>
                                                                </p>
                                                                @if($appointment->cancelled_at)
                                                                <p class="mb-1">
                                                                    <strong>Cancelled On:</strong> 
                                                                    {{ \Carbon\Carbon::parse($appointment->cancelled_at)->format('M j, Y g:i A') }}
                                                                </p>
                                                                @endif
                                                                <p class="mb-0">
                                                                    <strong>Your Notes:</strong><br>
                                                                    {{ $appointment->notes ?? 'No notes provided' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        @if(!$appointment->is_past && ($appointment->status === 'pending' || $appointment->status === 'confirmed'))
                                                        <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-danger" 
                                                                    onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                                                Cancel Appointment
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0 text-muted">
                                Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} 
                                of {{ $appointments->total() }} appointments
                            </p>
                        </div>
                        <div>
                            {{ $appointments->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Help Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Need Help?</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <i class="fas fa-phone-alt fa-2x text-primary mb-2"></i>
                        <h6>Call Us</h6>
                        <p class="small text-muted mb-0">(123) 456-7890</p>
                        <p class="small text-muted">Mon-Fri, 9AM-6PM</p>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                        <h6>Email Us</h6>
                        <p class="small text-muted mb-0">support@bookmyappointment.com</p>
                        <p class="small text-muted">Response within 24 hours</p>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <i class="fas fa-question-circle fa-2x text-primary mb-2"></i>
                        <h6>FAQ</h6>
                        <p class="small text-muted mb-0">Find answers to common questions</p>
                        <a href="#" class="small">View FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh page every 60 seconds to update status
    setTimeout(function() {
        window.location.reload();
    }, 60000);
</script>
@endsection