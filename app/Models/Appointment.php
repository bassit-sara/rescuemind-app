<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id', 'mental_officer_id', 'scheduled_at',
        'type', 'status', 'notes', 'meeting_link',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mentalOfficer()
    {
        return $this->belongsTo(User::class, 'mental_officer_id');
    }
}
