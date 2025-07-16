<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SystemSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add default sender name whitelist automation settings
        SystemSetting::firstOrCreate(
            ['key' => 'sender_name_auto_send_enabled'],
            ['value' => 'false', 'type' => 'boolean']
        );
        
        SystemSetting::firstOrCreate(
            ['key' => 'sender_name_notification_emails'],
            ['value' => '', 'type' => 'string']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SystemSetting::where('key', 'sender_name_auto_send_enabled')->delete();
        SystemSetting::where('key', 'sender_name_notification_emails')->delete();
    }
};
