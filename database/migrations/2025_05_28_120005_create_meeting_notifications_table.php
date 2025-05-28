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
        Schema::create('meeting_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // confirmation, reminder, cancellation, reschedule
            $table->string('channel'); // email, sms, both
            $table->string('recipient_type'); // host, booker
            $table->string('recipient_email')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->integer('minutes_before')->nullable(); // For reminders
            $table->text('custom_message')->nullable();
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('sms_credits_used')->default(0);
            $table->timestamps();

            $table->index(['meeting_booking_id', 'type']);
            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_notifications');
    }
};