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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->string('type')->default('info'); // info, success, warning, error
            $table->string('category')->nullable(); // transactional, promotional, account
            $table->json('data')->nullable(); // Additional data related to the notification
            $table->string('action_url')->nullable(); // URL to navigate to when notification is tapped
            $table->timestamp('read_at')->nullable(); // When the notification was read
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
