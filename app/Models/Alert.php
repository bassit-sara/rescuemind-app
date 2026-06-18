<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'title', 'message', 'level', 'province',
        'disaster_type', 'issued_at', 'expires_at',
        'is_active', 'issued_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function getLevelLabelAttribute(): string
    {
        return match($this->level) {
            1 => 'เฝ้าระวัง',
            2 => 'เตรียมอพยพ',
            3 => 'อพยพทันที',
            default => 'ไม่ระบุ',
        };
    }

    public function getLevelColorAttribute(): string
    {
        return match($this->level) {
            1 => 'yellow',
            2 => 'orange',
            3 => 'red',
            default => 'gray',
        };
    }

    public function getDisasterLabelAttribute(): string
    {
        return match($this->disaster_type) {
            'flood'     => 'น้ำท่วม',
            'landslide' => 'ดินถล่ม',
            'storm'     => 'พายุ',
            'wildfire'  => 'ไฟป่า',
            'pm25'      => 'PM2.5',
            default     => 'อื่นๆ',
        };
    }
}
