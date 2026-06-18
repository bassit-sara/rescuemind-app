<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodLog extends Model
{
    protected $fillable = [
        'user_id', 'mood', 'note', 'logged_date',
    ];

    protected $casts = [
        'logged_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMoodLabelAttribute(): string
    {
        return match($this->mood) {
            1 => 'แย่มาก 😢',
            2 => 'แย่ 😞',
            3 => 'ปกติ 😐',
            4 => 'ดี 😊',
            5 => 'ดีมาก 😄',
            default => '-',
        };
    }

    public function getMoodColorAttribute(): string
    {
        return match($this->mood) {
            1 => 'red',
            2 => 'orange',
            3 => 'yellow',
            4 => 'lime',
            5 => 'green',
            default => 'gray',
        };
    }
}
