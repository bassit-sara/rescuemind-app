<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mt3Livelihood extends Model
{
    protected $fillable = [
        'user_id', 'business_type', 'business_type_other', 
        'location', 'damage_details', 'damage_value', 'needs', 'status'
    ];

    protected $casts = [
        'needs' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
