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
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_minutes'); // Meeting duration
            $table->integer('buffer_before')->default(0); // Buffer time before meeting
            $table->integer('buffer_after')->default(0); // Buffer time after meeting
            $table->string('color')->default('#007bff'); // Calendar color
            $table->decimal('price', 8, 2)->default(0.00); // Optional pricing
            $table->integer('max_attendees')->default(1);
            $table->json('custom_questions')->nullable(); // Additional questions for bookers
            $table->json('availability')->nullable(); // Custom availability rules
            $table->string('location_type')->default('google_meet'); // google_meet, zoom, phone, etc.
            $table->string('location_details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_types');
    }
};
