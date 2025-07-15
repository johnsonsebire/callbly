<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('system_settings')->insert([
            [
                'key' => 'new_user_free_sms_credits_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable free SMS credits for newly registered users',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'new_user_free_sms_credits_amount',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Number of free SMS credits to give new users',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'system_sender_name',
                'value' => 'callbly',
                'type' => 'string',
                'description' => 'Default sender name for system messages',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'welcome_email_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Send welcome email when free credits are awarded',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
