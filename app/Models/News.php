<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class News extends Model
{
    protected $fillable = [
        'user_id', 'title', 'category', 'body', 'image_url', 'video_url', 'is_pinned', 'is_published',
    ];

    protected $casts = [
        'is_pinned'     => 'boolean',
        'is_published'  => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(NewsComment::class);
    }

    public static function categoryLabel(string $cat): string
    {
        return match($cat) {
            'disaster'  => 'ภัยพิบัติ',
            'health'    => 'สุขภาพ',
            'alert'     => 'แจ้งเตือน',
            'community' => 'ชุมชน',
            default     => 'ทั่วไป',
        };
    }

    public static function categoryColor(string $cat): string
    {
        return match($cat) {
            'disaster'  => 'red',
            'health'    => 'purple',
            'alert'     => 'orange',
            'community' => 'green',
            default     => 'blue',
        };
    }
}
