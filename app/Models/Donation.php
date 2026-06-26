<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = ['donor', 'phone', 'items', 'tracking_no', 'location'];
}
