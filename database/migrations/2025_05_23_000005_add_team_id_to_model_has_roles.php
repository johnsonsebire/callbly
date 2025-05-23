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
        // Add team_id to model_has_roles if it doesn't exist
        if (!Schema::hasColumn('model_has_roles', 'team_id')) {
            Schema::table('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('team_id')->nullable()->after('model_id');
                $table->foreign('team_id')
                      ->references('id')
                      ->on('teams')
                      ->onDelete('cascade');

                // Drop the existing primary key
                $table->dropPrimary();
                
                // Create new primary key including team_id
                $table->primary(['team_id', 'role_id', 'model_id', 'model_type'],
                    'model_has_roles_role_model_type_primary');
            });
        }
        
        // Add team_id to roles if it doesn't exist
        if (!Schema::hasColumn('roles', 'team_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->unsignedBigInteger('team_id')->nullable()->after('id');
                $table->foreign('team_id')
                      ->references('id')
                      ->on('teams')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('model_has_roles', 'team_id')) {
            Schema::table('model_has_roles', function (Blueprint $table) {
                // Drop the current primary key
                $table->dropPrimary();
                
                // Drop the foreign key
                $table->dropForeign(['team_id']);
                
                // Drop the team_id column
                $table->dropColumn('team_id');
                
                // Recreate the original primary key
                $table->primary(['role_id', 'model_id', 'model_type'],
                    'model_has_roles_role_model_type_primary');
            });
        }

        if (Schema::hasColumn('roles', 'team_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            });
        }
    }
};