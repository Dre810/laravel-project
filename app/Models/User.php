<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all appointments for this user (as a client).
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    /**
     * Get upcoming appointments.
     */
    public function upcomingAppointments()
    {
        return $this->appointments()
            ->where('date', '>=', now()->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('date')
            ->orderBy('start_time');
    }

    /**
     * Get completed appointments.
     */
    public function completedAppointments()
    {
        return $this->appointments()
            ->where('status', 'completed');
    }

    /**
     * Get cancelled appointments.
     */
    public function cancelledAppointments()
    {
        return $this->appointments()
            ->where('status', 'cancelled');
    }

    /**
     * Check if user has any appointments.
     */
    public function hasAppointments(): bool
    {
        return $this->appointments()->exists();
    }

    /**
     * Get total appointments count.
     */
    public function totalAppointments(): int
    {
        return $this->appointments()->count();
    }

    /**
     * Get upcoming appointments count.
     */
    public function upcomingAppointmentsCount(): int
    {
        return $this->upcomingAppointments()->count();
    }

    /**
     * Get the user's initials (for avatars).
     */
    public function getInitialsAttribute(): string
    {
        $name = trim($this->name);
        $initials = '';
        
        $names = explode(' ', $name);
        foreach ($names as $n) {
            if (!empty($n)) {
                $initials .= strtoupper($n[0]);
            }
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Get the user's first name.
     */
    public function getFirstNameAttribute(): string
    {
        $names = explode(' ', $this->name);
        return $names[0] ?? $this->name;
    }
}