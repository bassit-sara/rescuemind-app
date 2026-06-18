<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissingPerson extends Model
{
    protected $fillable = [
        'reporter_id', 'name', 'age', 'gender', 'photo',
        'province', 'last_seen_lat', 'last_seen_lng',
        'last_seen_at', 'description', 'status', 'notes',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'missing'   => 'สูญหาย',
            'searching' => 'กำลังค้นหา',
            'found'     => 'พบแล้ว',
            'safe'      => 'ปลอดภัย',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'missing'   => 'red',
            'searching' => 'orange',
            'found'     => 'blue',
            'safe'      => 'green',
            default     => 'gray',
        };
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
}
