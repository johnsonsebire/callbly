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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->after('email')->nullable();
            $table->integer('sms_credits')->default(0)->after('account_balance');
            $table->integer('call_credits')->default(0)->after('sms_credits');
            $table->integer('ussd_credits')->default(0)->after('call_credits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'sms_credits', 'call_credits', 'ussd_credits']);
        });
    }
};
