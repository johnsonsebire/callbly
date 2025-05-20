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
            $table->string('from_number');
            $table->string('to_number');
            $table->integer('duration')->default(0); // in seconds
            $table->enum('status', ['queued', 'ringing', 'in-progress', 'completed', 'failed'])->default('queued');
            $table->string('direction')->default('outbound');
            $table->string('reference_id')->unique();
            $table->boolean('recording_enabled')->default(false);
            $table->string('callback_url')->nullable();
            $table->integer('call_timeout')->default(60);
            $table->json('metadata')->nullable();
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
