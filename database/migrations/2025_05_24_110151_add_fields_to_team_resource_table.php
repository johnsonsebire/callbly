<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only add team_id to contacts if it doesn't exist
        if (!Schema::hasColumn('contacts', 'team_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->after('user_id')
                      ->constrained()->nullOnDelete();
            });
        }

        // Add missing indexes
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasIndex('contacts', 'contacts_team_id_user_id_index')) {
                $table->index(['team_id', 'user_id']);
            }
        });

        Schema::table('sender_names', function (Blueprint $table) {
            if (!Schema::hasIndex('sender_names', 'sender_names_team_id_status_index')) {
                $table->index(['team_id', 'status']);
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('contacts', 'team_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            });
        }

        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasIndex('contacts', 'contacts_team_id_user_id_index')) {
                $table->dropIndex('contacts_team_id_user_id_index');
            }
        });

        Schema::table('sender_names', function (Blueprint $table) {
            if (Schema::hasIndex('sender_names', 'sender_names_team_id_status_index')) {
                $table->dropIndex('sender_names_team_id_status_index');
            }
        });
    }
};