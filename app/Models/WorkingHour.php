<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    use HasFactory;

    // Table name (optional)
    protected $table = 'working_hours';

    // Mass assignment protection
    protected $fillable = [
        'staff_id',
        'day_of_week',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'is_working'
    ];

    // Cast fields
    protected $casts = [
        'is_working' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
    ];

    // Relationship: Working hours belong to a staff member
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // Helper: Get day name in proper format
    public function getDayNameAttribute()
    {
        return ucfirst($this->day_of_week);
    }

    // Helper: Check if currently in break time
    public function isBreakTime($time = null)
    {
        if (!$this->break_start || !$this->break_end) {
            return false;
        }

        $time = $time ?: now();
        $currentTime = $time->format('H:i:s');
        
        return $currentTime >= $this->break_start && $currentTime <= $this->break_end;
    }

    // Helper: Get total working hours for the day
    public function getTotalHoursAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        return $start->diffInHours($end);
    }
}