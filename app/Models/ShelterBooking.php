<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShelterBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'relief_point_id',
        'number_of_people',
        'current_address',
        'additional_info',
        'status',
        'room_key',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reliefPoint()
    {
        return $this->belongsTo(ReliefPoint::class);
    }
}
