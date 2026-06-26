<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SuperAdminSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('super-admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except('_token', '_method');

        // Checkbox values might not be sent if unchecked, so we handle boolean settings explicitly
        $booleanSettings = Setting::where('type', 'boolean')->get();
        foreach ($booleanSettings as $setting) {
            if (!isset($inputs[$setting->key])) {
                $inputs[$setting->key] = 'false';
            } else {
                $inputs[$setting->key] = 'true';
            }
        }

        foreach ($inputs as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        // Clear the cache so the new settings take effect immediately
        Cache::forget('global_settings');

        return back()->with('success', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
    }
}
