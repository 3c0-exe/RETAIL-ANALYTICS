<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = AdminSetting::all()->groupBy('group');

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = AdminSetting::where('key', $key)->first();

            if ($setting) {
                // Handle checkboxes (if not checked, they're not in request)
                if ($setting->type === 'boolean' && !isset($validated['settings'][$key])) {
                    $value = '0';
                }

                $setting->update(['value' => $value]);
                Cache::forget("admin_setting_{$key}");
            }
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return response()->json(['success' => true, 'message' => 'Cache cleared successfully!']);
    }

    public function maintenance(Request $request)
    {
        $currentMode = AdminSetting::get('maintenance_mode', false);
        AdminSetting::set('maintenance_mode', !$currentMode ? '1' : '0', 'boolean', 'system');

        return back()->with('success', 'Maintenance mode ' . (!$currentMode ? 'enabled' : 'disabled') . '!');
    }
}
