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
        Schema::create('meeting_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Meeting host
            $table->foreignId('event_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('scheduling_page_id')->nullable()->constrained()->onDelete('set null');
            
            // Booker information
            $table->string('booker_name');
            $table->string('booker_email');
            $table->string('booker_phone')->nullable();
            $table->json('custom_responses')->nullable(); // Responses to custom questions
            
            // Meeting details
            $table->timestamp('scheduled_at');
            $table->timestamp('scheduled_end_at');
            $table->string('timezone');
            $table->string('status')->default('confirmed'); // confirmed, cancelled, completed, no_show
            $table->text('meeting_notes')->nullable();
            
            // Google Meet integration
            $table->string('google_meet_link')->nullable();
            $table->string('google_event_id')->nullable();
            $table->json('google_calendar_data')->nullable();
            
            // Cancellation/Rescheduling
            $table->string('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->json('reschedule_history')->nullable(); // Track rescheduling history
            
            // Unique booking reference for public links
            $table->string('booking_reference')->unique();
            
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['scheduled_at', 'status']);
            $table->index('booking_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_bookings');
    }
};