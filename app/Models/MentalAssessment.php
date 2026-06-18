<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentalAssessment extends Model
{
    protected $fillable = [
        'user_id', 'type', 'answers', 'score', 'severity', 'notes',
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSeverityLabelAttribute(): string
    {
        return match($this->severity) {
            'minimal'  => 'น้อยมาก',
            'mild'     => 'น้อย',
            'moderate' => 'ปานกลาง',
            'severe'   => 'รุนแรง',
            default    => $this->severity ?? '-',
        };
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'minimal'  => 'green',
            'mild'     => 'yellow',
            'moderate' => 'orange',
            'severe'   => 'red',
            default    => 'gray',
        };
    }
}
