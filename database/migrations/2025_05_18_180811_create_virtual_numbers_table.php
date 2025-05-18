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
        Schema::create('virtual_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Can be null if available for purchase
            $table->string('number')->unique();
            $table->string('country_code');
            $table->enum('status', ['available', 'reserved', 'active', 'expired'])->default('available');
            $table->enum('type', ['local', 'toll-free', 'premium'])->default('local');
            $table->decimal('monthly_fee', 10, 2);
            $table->text('features')->nullable(); // JSON or serialized array of features
            $table->string('forwarding_number')->nullable();
            $table->timestamp('reserved_until')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_numbers');
    }
};
