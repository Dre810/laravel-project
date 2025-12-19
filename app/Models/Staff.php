<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialty',
        'bio',
        'photo_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // ADD THIS METHOD (Many-to-Many relationship)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_staff');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Relationship: A staff member has many working hours
public function workingHours()
{
    return $this->hasMany(WorkingHour::class);
}

// Check if staff is working on a specific day
public function isWorkingOnDay($day)
{
    $workingHour = $this->workingHours()
        ->where('day_of_week', strtolower($day))
        ->first();
    
    return $workingHour && $workingHour->is_working;
}

// Get working hours for a specific day
public function getWorkingHoursForDay($day)
{
    return $this->workingHours()
        ->where('day_of_week', strtolower($day))
        ->where('is_working', true)
        ->first();
}

// Get available time slots for a specific day and service
// Get available time slots for a specific day and service
public function getAvailableSlots($date, $service)
{
    // Convert date to Carbon instance
    $date = \Carbon\Carbon::parse($date);
    $dayOfWeek = strtolower($date->englishDayOfWeek); // 'monday', 'tuesday', etc.
    
    // Get working hours for this day
    $workingHour = $this->workingHours()
        ->where('day_of_week', $dayOfWeek)
        ->where('is_working', true)
        ->first();
    
    // If not working this day, return empty array
    if (!$workingHour || !$workingHour->start_time || !$workingHour->end_time) {
        return [];
    }
    
    // Parse working hours
    $startTime = \Carbon\Carbon::parse($workingHour->start_time);
    $endTime = \Carbon\Carbon::parse($workingHour->end_time);
    
    // Get existing appointments for this staff on this date
    $existingAppointments = $this->appointments()
        ->where('date', $date->format('Y-m-d'))
        ->whereIn('status', ['confirmed', 'pending'])
        ->get();
    
    // Generate time slots
    $slots = [];
    $currentTime = $startTime->copy();
    $serviceDuration = $service->duration; // in minutes
    
    while ($currentTime->addMinutes($serviceDuration) <= $endTime) {
        $slotStart = $currentTime->copy()->subMinutes($serviceDuration);
        $slotEnd = $currentTime->copy();
        
        // Skip if in break time
        if ($workingHour->break_start && $workingHour->break_end) {
            $breakStart = \Carbon\Carbon::parse($workingHour->break_start);
            $breakEnd = \Carbon\Carbon::parse($workingHour->break_end);
            
            if ($slotStart->between($breakStart, $breakEnd->subMinute()) || 
                $slotEnd->between($breakStart->addMinute(), $breakEnd)) {
                continue;
            }
        }
        
        // Check if slot is available (not booked)
        $isAvailable = true;
        foreach ($existingAppointments as $appointment) {
            $appointmentStart = \Carbon\Carbon::parse($appointment->start_time);
            $appointmentEnd = \Carbon\Carbon::parse($appointment->end_time);
            
            // Check for overlap
            if ($slotStart < $appointmentEnd && $slotEnd > $appointmentStart) {
                $isAvailable = false;
                break;
            }
        }
        
        // Only show slots in the future (not in the past)
        $slotDateTime = $date->copy()->setTime($slotStart->hour, $slotStart->minute);
        if ($slotDateTime->isPast()) {
            $isAvailable = false;
        }
        
        $slots[] = [
            'start' => $slotStart->format('H:i'),
            'end' => $slotEnd->format('H:i'),
            'is_available' => $isAvailable,
            'display' => $slotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A')
        ];
    }
    
    return $slots;
}
}