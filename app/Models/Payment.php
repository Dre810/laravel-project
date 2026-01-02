<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_id',
        'stripe_payment_id',
        'amount',
        'currency',
        'status',
        'stripe_response',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'stripe_response' => 'array',
        'paid_at' => 'datetime'
    ];

    // Relationship: Payment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Payment belongs to an appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Check if payment is successful
    public function isSuccessful()
    {
        return $this->status === 'succeeded';
    }

    // Check if payment is pending
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Get formatted amount (with currency symbol)
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    // Get status with color for UI
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'succeeded' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'refunded' => 'info',
            default => 'secondary',
        };
    }
}