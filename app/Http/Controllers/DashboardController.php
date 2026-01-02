<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // User dashboard
    public function index()
    {
        $userId = auth()->id();
        if (! $userId) {
            return redirect()->route('login');
        }
        
        // Get user's upcoming appointments
        $upcomingAppointments = Appointment::where('client_id', $userId)
            ->with(['staff', 'service'])
            ->where('date', '>=', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();
        
        // Get appointment statistics
        $stats = [
            'total' => Appointment::where('client_id', $userId)->count(),
            'upcoming' => Appointment::where('client_id', $userId)
                ->where('date', '>=', Carbon::today())
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
            'completed' => Appointment::where('client_id', $userId)
                ->where('status', 'completed')
                ->count(),
            'cancelled' => Appointment::where('client_id', $userId)
                ->where('status', 'cancelled')
                ->count(),
        ];
        
        return view('dashboard.index', compact('upcomingAppointments', 'stats'));
    }
    
    // User's appointments page
    public function appointments()
    {
        $userId = auth()->id();
        if (! $userId) {
            return redirect()->route('login');
        }
        
        $appointments = Appointment::where('client_id', $userId)
            ->with(['staff', 'service'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);
        
        return view('dashboard.appointments', compact('appointments'));
    }
    
    // Cancel an appointment
   // Cancel an appointment
public function cancelAppointment(Request $request, Appointment $appointment)
{
    // Check if user owns this appointment
    if ($appointment->client_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
  // Check if appointment can be cancelled (not in the past)
// Simple check: if date is today or in the future
if ($appointment->date->isPast() && $appointment->date->isSameDay(now())) {
    // If same day, check time
    if (now()->format('H:i') > $appointment->start_time) {
        return back()->with('error', 'Cannot cancel past appointments.');
    }
} elseif ($appointment->date->isPast()) {
    return back()->with('error', 'Cannot cancel past appointments.');
}
    
    // Update appointment status
    $appointment->update([
        'status' => 'cancelled',
        'cancelled_at' => now(),
    ]);
    
    return back()->with('success', 'Appointment cancelled successfully.');
}
}