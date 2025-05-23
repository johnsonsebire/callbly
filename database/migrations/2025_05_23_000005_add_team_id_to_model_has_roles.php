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
        $teams = true;
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (!Schema::hasTable($tableNames['roles'])) {
            Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
                $table->bigIncrements('id');
                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                }
                $table->string('name');       
                $table->string('guard_name'); 
                $table->timestamps();
                if ($teams) {
                    $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
                } else {
                    $table->unique(['name', 'guard_name']);
                }
            });
        }

        if (!Schema::hasTable($tableNames['permissions'])) {
            Schema::create($tableNames['permissions'], function (Blueprint $table) use ($teams, $columnNames) {
                $table->bigIncrements('id');
                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                }
                $table->string('name');       
                $table->string('guard_name'); 
                $table->timestamps();
                if ($teams) {
                    $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
                } else {
                    $table->unique(['name', 'guard_name']);
                }
            });
        }

        if (!Schema::hasTable($tableNames['model_has_permissions'])) {
            Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
                $table->unsignedBigInteger('permission_id');

                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                    $table->primary([$columnNames['team_foreign_key'], 'permission_id', $columnNames['model_morph_key'], 'model_type'],
                        'model_has_permissions_permission_model_type_primary');
                } else {
                    $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                        'model_has_permissions_permission_model_type_primary');
                }

                $table->foreign('permission_id')
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable($tableNames['model_has_roles'])) {
            Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
                $table->unsignedBigInteger('role_id');

                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                    $table->unique([$columnNames['team_foreign_key'], 'role_id', $columnNames['model_morph_key'], 'model_type'],
                        'model_has_roles_role_unique');
                } else {
                    $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                        'model_has_roles_role_model_type_primary');
                }

                $table->foreign('role_id')
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');

                $table->foreign('permission_id')
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');

                $table->foreign('role_id')
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');

                $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission 
                       .php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
};