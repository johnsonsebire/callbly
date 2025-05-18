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
        Schema::create('affiliate_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referred_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('referral_code')->unique();
            $table->string('referral_link');
            $table->integer('clicks')->default(0);
            $table->integer('signups')->default(0);
            $table->integer('conversions')->default(0); // Successful purchases
            $table->decimal('earnings', 10, 2)->default(0.00);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->decimal('pending_amount', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_referrals');
    }
};
