<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemSettingsController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index()
    {
        $settings = [
            'new_user_free_sms_credits_enabled' => SystemSetting::get('new_user_free_sms_credits_enabled', true),
            'new_user_free_sms_credits_amount' => SystemSetting::get('new_user_free_sms_credits_amount', 5),
            'system_sender_name' => SystemSetting::get('system_sender_name', 'callbly'),
            'welcome_email_enabled' => SystemSetting::get('welcome_email_enabled', true),
        ];

        return view('admin.system-settings.index', compact('settings'));
    }

    /**
     * Update the system settings.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_user_free_sms_credits_enabled' => 'required|boolean',
            'new_user_free_sms_credits_amount' => 'required|integer|min:0|max:100',
            'system_sender_name' => 'required|string|max:11|regex:/^[a-zA-Z0-9]+$/',
            'welcome_email_enabled' => 'required|boolean',
        ], [
            'system_sender_name.regex' => 'The sender name must contain only letters and numbers.',
            'system_sender_name.max' => 'The sender name must not exceed 11 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        // Update each setting
        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value);
        }

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    /**
     * Reset settings to default values.
     */
    public function reset()
    {
        $defaultSettings = [
            'new_user_free_sms_credits_enabled' => true,
            'new_user_free_sms_credits_amount' => 5,
            'system_sender_name' => 'callbly',
            'welcome_email_enabled' => true,
        ];

        foreach ($defaultSettings as $key => $value) {
            SystemSetting::set($key, $value);
        }

        return redirect()->back()->with('success', 'System settings reset to default values.');
    }
}
