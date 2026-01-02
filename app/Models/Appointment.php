<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignment protection
    protected $fillable = [
        'client_id',
        'staff_id',
        'service_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'reminder_sent'
    ];

    // Cast fields
    protected $casts = [
    'date' => 'date',  // This casts to Carbon instance
    'start_time' => 'datetime:H:i:s',
    'end_time' => 'datetime:H:i:s',
    'reminder_sent' => 'boolean'
];

    // Relationship: Appointment belongs to a client (User)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relationship: Appointment belongs to a staff member
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // Relationship: Appointment belongs to a service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Helper: Get appointment status with color (for UI)
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'green',
            'cancelled' => 'red',
            'completed' => 'blue',
            default => 'gray',
        };
    }

 // Helper: Check if appointment is in the past
public function getIsPastAttribute()
{
    // Combine date and start_time properly
    $appointmentDateTime = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->start_time);
    return now() > $appointmentDateTime;
}

    // Relationship: Appointment has one payment
public function payment()
{
    return $this->hasOne(Payment::class);
}

// Check if appointment is paid
public function getIsPaidAttribute()
{
    return $this->payment && $this->payment->isSuccessful();
}
}