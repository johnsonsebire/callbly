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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            
            // Create index on phone number for faster lookups
            $table->index('phone_number');
            
            // Ensure phone numbers are unique per user
            $table->unique(['user_id', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};