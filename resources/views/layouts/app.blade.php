<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Our custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @yield('styles')
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-calendar-check me-2"></i>BookMyAppointment
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('booking.services') }}">
                            <i class="fas fa-calendar-alt me-1"></i> Book Now
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-question-circle me-1"></i> How it Works
                        </a>
                    </li>
                    @if(auth()->check())
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-user me-1"></i> My Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>BookMyAppointment</h5>
                    <p>Easy online booking for all your service needs.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Book Appointment</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i> info@bookmyappointment.com</p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} BookMyAppointment. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Our custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @yield('scripts')
</body>
</html>