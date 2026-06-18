<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HazardReport extends Model
{
    protected $fillable = [
        'reporter_id', 'type', 'latitude', 'longitude',
        'province', 'photo', 'description', 'status', 'verified',
    ];

    protected $casts = [
        'verified' => 'boolean',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'flood'          => 'น้ำท่วม',
            'landslide'      => 'ดินถล่ม',
            'road_blocked'   => 'ถนนขาด',
            'bridge_damaged' => 'สะพานพัง',
            'power_outage'   => 'ไฟฟ้าดับ',
            'fire'           => 'เพลิงไหม้',
            default          => 'อื่นๆ',
        };
    }
}
