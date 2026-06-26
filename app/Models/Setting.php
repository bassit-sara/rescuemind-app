<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['group', 'key', 'value', 'type', 'label'];

    /**
     * Helper to get a setting value.
     */
    public static function getVal($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) return $default;

        if ($setting->type === 'boolean') {
            return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
        }
        if ($setting->type === 'integer') {
            return (int) $setting->value;
        }
        if ($setting->type === 'json') {
            return json_decode($setting->value, true);
        }
        
        return $setting->value;
    }
}
