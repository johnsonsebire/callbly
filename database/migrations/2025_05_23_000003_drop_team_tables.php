<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations in the correct order to avoid foreign key constraint issues.
     */
    public function up(): void
    {
        // First, drop the foreign key constraint from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_team_id']);
            $table->dropColumn('current_team_id');
        });

        // Now we can safely drop the team-related tables in the correct order
        Schema::dropIfExists('team_resources');
        Schema::dropIfExists('team_invitations');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse as this is a cleanup migration
    }
};