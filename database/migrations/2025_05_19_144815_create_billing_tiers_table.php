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
        Schema::create('billing_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Plus, Premium, Gold
            $table->string('description')->nullable();
            $table->decimal('price_per_sms', 8, 6); // Price per SMS in the base currency (GHS)
            $table->decimal('min_purchase', 10, 2)->default(0); // Minimum purchase amount to qualify for this tier
            $table->decimal('max_purchase', 10, 2)->nullable(); // Maximum purchase amount for this tier (null for unlimited)
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_tiers');
    }
};
