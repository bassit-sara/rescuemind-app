<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get a setting value from the database (cached).
     */
    function setting($key, $default = null)
    {
        // Cache settings to avoid database queries on every page load
        $settings = Cache::rememberForever('global_settings', function () {
            try {
                return Setting::all()->keyBy('key');
            } catch (\Exception $e) {
                // Return empty collection if table doesn't exist yet
                return collect();
            }
        });

        if ($settings->has($key)) {
            $setting = $settings->get($key);
            
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

        return $default;
    }
}
