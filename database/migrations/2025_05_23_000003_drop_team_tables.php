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
        // Drop foreign keys from all tables that reference teams
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'current_team_id')) {
                    $table->dropForeign(['current_team_id']);
                    $table->dropColumn('current_team_id');
                }
            });
        }

        if (Schema::hasTable('sender_names')) {
            Schema::table('sender_names', function (Blueprint $table) {
                if (Schema::hasColumn('sender_names', 'team_id')) {
                    $table->dropForeign(['team_id']);
                    $table->dropColumn('team_id');
                }
            });
        }

        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (Schema::hasColumn('contacts', 'team_id')) {
                    $table->dropForeign(['team_id']);
                    $table->dropColumn('team_id');
                }
            });
        }

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
        // This is a cleanup migration, no down() needed
    }
};