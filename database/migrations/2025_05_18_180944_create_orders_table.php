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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('virtual_number_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('reference_id')->unique(); // For payment gateway reference
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->enum('payment_method', ['paystack', 'wallet', 'bank_transfer'])->default('paystack');
            $table->json('payment_details')->nullable(); // Store payment gateway response
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->unsignedBigInteger('affiliate_referral_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
