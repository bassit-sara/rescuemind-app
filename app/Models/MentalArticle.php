<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MentalArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'desc',
        'read_time',
        'author',
        'icon',
        'video_url',
        'content',
    ];
}
