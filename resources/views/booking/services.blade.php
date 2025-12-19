@extends('layouts.app')

@section('title', 'Select Service - BookMyAppointment')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Select Service</li>
            </ol>
        </nav>
        <h2>Select a Service</h2>
        <p class="text-muted">Choose the service you'd like to book</p>
    </div>
</div>

<div class="row">
    @foreach($services as $service)
    <div class="col-md-4 mb-4">
        <div class="card h-100 service-card" onclick="selectService({{ $service->id }})" id="service-{{ $service->id }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <span class="badge bg-secondary">{{ $service->category }}</span>
                    </div>
                    <div class="text-end">
                        <h4 class="text-primary">${{ $service->price }}</h4>
                        <small class="text-muted">{{ $service->duration }} minutes</small>
                    </div>
                </div>
                
                <p class="card-text mt-3">{{ $service->description }}</p>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i> {{ $service->duration }} min
                        <i class="fas fa-users ms-3 me-1"></i> {{ $service->staff->count() }} available staff
                    </small>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 text-end">
                <a href="{{ route('booking.staff', $service) }}" class="btn btn-primary">
                    Select & Continue <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Categories Filter (Optional) -->
@if($services->pluck('category')->unique()->count() > 1)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6>Filter by Category:</h6>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary active" onclick="filterServices('all')">
                        All
                    </button>
                    @foreach($services->pluck('category')->unique() as $category)
                        @if($category)
                        <button type="button" class="btn btn-outline-secondary" onclick="filterServices('{{ $category }}')">
                            {{ $category }}
                        </button>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    function selectService(serviceId) {
        // Remove selection from all cards
        document.querySelectorAll('.service-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selection to clicked card
        document.getElementById('service-' + serviceId).classList.add('selected');
    }
    
    function filterServices(category) {
        const cards = document.querySelectorAll('.service-card');
        const buttons = document.querySelectorAll('.btn-group .btn');
        
        // Update active button
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        
        // Show/hide cards
        cards.forEach(card => {
            if (category === 'all' || card.querySelector('.badge').textContent === category) {
                card.parentElement.style.display = 'block';
            } else {
                card.parentElement.style.display = 'none';
            }
        });
    }
</script>
@endsection