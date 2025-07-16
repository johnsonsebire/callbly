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
            'sender_name_auto_send_enabled' => SystemSetting::get('sender_name_auto_send_enabled', false),
            'sender_name_notification_emails' => SystemSetting::get('sender_name_notification_emails', ''),
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
            'sender_name_auto_send_enabled' => 'required|boolean',
            'sender_name_notification_emails' => 'nullable|string|max:1000',
        ], [
            'system_sender_name.regex' => 'The sender name must contain only letters and numbers.',
            'system_sender_name.max' => 'The sender name must not exceed 11 characters.',
            'sender_name_notification_emails.max' => 'The notification emails field is too long.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        // Convert checkbox values to proper booleans
        $validated['new_user_free_sms_credits_enabled'] = (bool) $validated['new_user_free_sms_credits_enabled'];
        $validated['welcome_email_enabled'] = (bool) $validated['welcome_email_enabled'];
        $validated['sender_name_auto_send_enabled'] = (bool) $validated['sender_name_auto_send_enabled'];

        // Validate email addresses format if provided
        if (!empty($validated['sender_name_notification_emails'])) {
            $emails = array_filter(array_map('trim', explode(',', $validated['sender_name_notification_emails'])));
            foreach ($emails as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return redirect()->back()
                        ->withErrors(['sender_name_notification_emails' => "Invalid email address: {$email}"])
                        ->withInput();
                }
            }
        }

        // Update each setting with proper types
        SystemSetting::set('new_user_free_sms_credits_enabled', $validated['new_user_free_sms_credits_enabled'], 'boolean');
        SystemSetting::set('new_user_free_sms_credits_amount', $validated['new_user_free_sms_credits_amount'], 'integer');
        SystemSetting::set('system_sender_name', $validated['system_sender_name'], 'string');
        SystemSetting::set('welcome_email_enabled', $validated['welcome_email_enabled'], 'boolean');
        SystemSetting::set('sender_name_auto_send_enabled', $validated['sender_name_auto_send_enabled'], 'boolean');
        SystemSetting::set('sender_name_notification_emails', $validated['sender_name_notification_emails'] ?? '', 'string');

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    /**
     * Reset settings to default values.
     */
    public function reset()
    {
        SystemSetting::set('new_user_free_sms_credits_enabled', true, 'boolean');
        SystemSetting::set('new_user_free_sms_credits_amount', 5, 'integer');
        SystemSetting::set('system_sender_name', 'callbly', 'string');
        SystemSetting::set('welcome_email_enabled', true, 'boolean');
        SystemSetting::set('sender_name_auto_send_enabled', false, 'boolean');
        SystemSetting::set('sender_name_notification_emails', '', 'string');

        return redirect()->back()->with('success', 'System settings reset to default values.');
    }
}
