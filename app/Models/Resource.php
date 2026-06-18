<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'name', 'type', 'province', 'location',
        'quantity', 'available_quantity', 'unit', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'boat'     => 'เรือ',
            'truck'    => 'รถ',
            'medicine' => 'ยา',
            'food'     => 'อาหาร',
            'water'    => 'น้ำดื่ม',
            default    => 'อื่นๆ',
        };
    }
}
