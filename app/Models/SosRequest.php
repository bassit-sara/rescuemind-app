<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SosRequest extends Model
{
    protected $fillable = [
        'user_id', 'latitude', 'longitude', 'address', 'province',
        'num_people', 'water_level', 'has_elderly', 'has_children',
        'has_bedridden', 'has_pregnant', 'description',
        'status', 'priority', 'officer_id', 'volunteer_id',
        'notes', 'assigned_at', 'resolved_at', 'urgent_needs'
    ];

    protected $casts = [
        'has_elderly' => 'boolean',
        'has_children' => 'boolean',
        'has_bedridden' => 'boolean',
        'has_pregnant' => 'boolean',
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
        'urgent_needs' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'critical' => 'red',
            'high'     => 'orange',
            'medium'   => 'yellow',
            'low'      => 'green',
            default    => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'รอดำเนินการ',
            'assigned'    => 'มอบหมายแล้ว',
            'in_progress' => 'กำลังช่วยเหลือ',
            'resolved'    => 'เสร็จสิ้น',
            'safe'        => 'ปลอดภัย',
            default       => $this->status,
        };
    }
}
