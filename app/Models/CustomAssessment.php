<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomAssessment extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'description',
        'time_estimate',
        'icon',
        'color_theme',
        'questions',
        'is_active',
    ];

    protected $casts = [
        'questions' => 'array',
        'is_active' => 'boolean',
    ];
}
