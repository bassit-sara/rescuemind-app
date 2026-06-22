<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'province',
        'district',
        'avatar',
        'is_active',
        'is_safe',
        'safe_at',
        'provider',
        'provider_id',
        'provider_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_safe' => 'boolean',
            'safe_at' => 'datetime',
        ];
    }

    // Relationships
    public function sosRequests()
    {
        return $this->hasMany(SosRequest::class);
    }

    public function assignedSosRequests()
    {
        return $this->hasMany(SosRequest::class, 'officer_id');
    }

    public function mentalAssessments()
    {
        return $this->hasMany(MentalAssessment::class);
    }

    public function moodLogs()
    {
        return $this->hasMany(MoodLog::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function missingPersonReports()
    {
        return $this->hasMany(MissingPerson::class, 'reporter_id');
    }

    public function hazardReports()
    {
        return $this->hasMany(HazardReport::class, 'reporter_id');
    }

    public function volunteer()
    {
        return $this->hasOne(Volunteer::class);
    }
}
