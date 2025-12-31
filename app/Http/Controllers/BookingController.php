<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Staff;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Step 1: Show all available services
    public function showServices()
    {
        $services = Service::where('is_active', true)->get();
        
        return view('booking.services', compact('services'));
    }
    
    // Step 2: Show staff for selected service
    public function showStaff(Service $service)
    {
        $staff = $service->staff()->where('is_active', true)->get();
        
        return view('booking.staff', compact('service', 'staff'));
    }
    
    // Step 3: Show available time slots
   // Step 3: Show available time slots
public function showSlots(Service $service, Staff $staff, Request $request)
{
    // Get selected date from request or default to tomorrow
    $selectedDate = $request->input('date', \Carbon\Carbon::tomorrow()->format('Y-m-d'));
    
    // Get available slots for the selected date
    $availableSlots = $staff->getAvailableSlots($selectedDate, $service);
    
    // Generate dates for the next 14 days
    $dates = [];
    for ($i = 0; $i < 14; $i++) {
        $date = \Carbon\Carbon::tomorrow()->addDays($i);
        $dates[] = [
            'date' => $date->format('Y-m-d'),
            'display' => $date->format('D, M j'),
            'is_selected' => $date->format('Y-m-d') === $selectedDate,
            'day_name' => $date->format('l')
        ];
    }
    
    return view('booking.slots', compact('service', 'staff', 'availableSlots', 'dates', 'selectedDate'));
}
    
    // Step 4: Book the appointment
    public function bookAppointment(Request $request)
{
    // Make sure user is logged in
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please login to book an appointment.');
    }
    
    // Validate the request
    $validated = $request->validate([
        'service_id' => 'required|exists:services,id',
        'staff_id' => 'required|exists:staff,id',
        'date' => 'required|date',
        'time' => 'required|date_format:H:i',
        'notes' => 'nullable|string|max:500',
    ]);
    
    // Get service to calculate end time
    $service = Service::find($validated['service_id']);
    
    // Create appointment
    $appointment = Appointment::create([
        'client_id' => Auth::id(), // Use logged-in user's ID
        'service_id' => $validated['service_id'],
        'staff_id' => $validated['staff_id'],
        'date' => $validated['date'],
        'start_time' => $validated['time'],
        'end_time' => \Carbon\Carbon::parse($validated['time'])
            ->addMinutes($service->duration),
        'status' => 'pending',
        'notes' => $validated['notes'] ?? null,
    ]);
    
    return redirect()->route('dashboard')->with('success', 'Appointment booked successfully!');
}
}