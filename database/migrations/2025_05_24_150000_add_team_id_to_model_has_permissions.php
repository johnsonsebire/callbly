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
        // Get all existing permissions
        $existingPermissions = DB::table('model_has_permissions')->get();
        
        // Drop and recreate the model_has_permissions table with team_id
        Schema::dropIfExists('model_has_permissions');
        
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('team_id')->nullable();

            // Create primary key without team_id
            $table->primary(['permission_id', 'model_id', 'model_type'], 
                'model_has_permissions_permission_model_type_primary');

            // Add unique constraint including team_id
            $table->unique(['team_id', 'permission_id', 'model_id', 'model_type'], 
                'model_has_permissions_team_unique');

            $table->foreign('permission_id')
                  ->references('id')
                  ->on('permissions')
                  ->onDelete('cascade');
                  
            if (Schema::hasTable('teams')) {
                $table->foreign('team_id')
                      ->references('id')
                      ->on('teams')
                      ->onDelete('cascade');
            }

            $table->index(['model_id', 'model_type']);
        });

        // Restore the existing permissions
        foreach ($existingPermissions as $permission) {
            DB::table('model_has_permissions')->insert([
                'permission_id' => $permission->permission_id,
                'model_type' => $permission->model_type,
                'model_id' => $permission->model_id,
                'team_id' => null // Set default team_id as null for existing permissions
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get existing permission assignments
        $existingPermissions = DB::table('model_has_permissions')->get();
        
        // Drop and recreate the table without team_id
        Schema::dropIfExists('model_has_permissions');
        
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->foreign('permission_id')
                  ->references('id')
                  ->on('permissions')
                  ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        // Restore permission assignments
        foreach ($existingPermissions as $permission) {
            DB::table('model_has_permissions')->insert([
                'permission_id' => $permission->permission_id,
                'model_type' => $permission->model_type,
                'model_id' => $permission->model_id
            ]);
        }
    }
};