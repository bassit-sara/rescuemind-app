<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = [
        'user_id', 'province', 'skills', 'is_available', 'status', 'approval_status', 'name', 'phone', 'volunteer_type', 'duration_days',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
