<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_id')->index();
            $table->string('device_name');
            $table->string('device_token')->nullable(); // FCM token for push notifications
            $table->string('platform')->nullable(); // ios, android
            $table->boolean('biometric_enabled')->default(false);
            $table->boolean('notification_enabled')->default(true);
            $table->json('notification_settings')->nullable(); // Settings for different notification types
            $table->timestamp('last_authenticated_at')->nullable();
            $table->timestamps();
            
            // Unique constraint to ensure one record per device per user
            $table->unique(['user_id', 'device_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
