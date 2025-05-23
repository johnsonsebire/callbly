<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, get all existing role assignments to preserve them
        $existingRoles = DB::table('model_has_roles')->get();
        
        // Drop the existing model_has_roles table and recreate it with team_id
        Schema::dropIfExists('model_has_roles');
        
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('team_id')->nullable();

            // Create primary key without team_id
            $table->primary(['role_id', 'model_id', 'model_type']);

            // Add unique constraint including team_id
            $table->unique(['team_id', 'role_id', 'model_id', 'model_type'], 
                'model_has_roles_team_role_unique');

            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('cascade');
                  
            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('cascade');

            $table->index(['model_id', 'model_type']);
        });

        // Restore the existing role assignments
        foreach ($existingRoles as $role) {
            DB::table('model_has_roles')->insert([
                'role_id' => $role->role_id,
                'model_type' => $role->model_type,
                'model_id' => $role->model_id,
                'team_id' => null // Set default team_id as null for existing roles
            ]);
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
        // Get existing role assignments
        $existingRoles = DB::table('model_has_roles')->get();
        
        // Drop and recreate the table without team_id
        Schema::dropIfExists('model_has_roles');
        
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // Restore role assignments
        foreach ($existingRoles as $role) {
            DB::table('model_has_roles')->insert([
                'role_id' => $role->role_id,
                'model_type' => $role->model_type,
                'model_id' => $role->model_id
            ]);
        }

        if (Schema::hasColumn('roles', 'team_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            });
        }
    }
};