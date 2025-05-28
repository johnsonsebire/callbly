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
        Schema::create('scheduling_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained()->onDelete('cascade');
            $table->string('slug'); // Custom URL slug (e.g., "consultation")
            $table->string('title'); // Page title
            $table->text('description')->nullable();
            $table->json('event_type_ids'); // Array of event type IDs available on this page
            $table->json('custom_branding')->nullable(); // Custom colors, fonts, etc.
            $table->boolean('is_active')->default(true);
            $table->integer('advance_booking_days')->default(30); // How far in advance bookings are allowed
            $table->integer('minimum_notice_hours')->default(24); // Minimum notice for bookings
            $table->timestamps();

            $table->unique(['company_profile_id', 'slug']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduling_pages');
    }
};