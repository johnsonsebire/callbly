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
        Schema::create('contact_center_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('caller_number');
            $table->string('receiver_number');
            $table->integer('duration')->default(0); // in seconds
            $table->enum('status', ['initiated', 'connected', 'completed', 'failed', 'missed'])->default('initiated');
            $table->text('notes')->nullable();
            $table->json('ivr_path')->nullable(); // Store the IVR menu path taken
            $table->string('recording_url')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_center_calls');
    }
};
