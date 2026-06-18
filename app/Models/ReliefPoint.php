<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReliefPoint extends Model
{
    protected $fillable = [
        'name', 'type', 'province', 'district', 'address',
        'latitude', 'longitude', 'capacity', 'current_occupancy',
        'available_beds', 'has_icu', 'ambulance_count',
        'phone', 'is_active',
    ];

    protected $casts = [
        'has_icu' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getAvailableCapacityAttribute(): int
    {
        return max(0, $this->capacity - $this->current_occupancy);
    }

    public function getOccupancyPercentAttribute(): int
    {
        if ($this->capacity === 0) return 0;
        return (int) round(($this->current_occupancy / $this->capacity) * 100);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'shelter'  => 'ศูนย์พักพิง',
            'hospital' => 'โรงพยาบาล',
            'food'     => 'ศูนย์อาหาร',
            'parking'  => 'จุดจอดรถหนีน้ำ',
            default    => $this->type,
        };
    }
}
