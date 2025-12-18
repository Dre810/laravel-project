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
public function getAvailableSlots($date, $serviceId)
{
    // We'll implement this fully later - placeholder
    return [];
}
}