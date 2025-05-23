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
        if (!Schema::hasColumn('roles', 'team_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->unsignedBigInteger('team_id')->nullable()->after('id');
                
                // Add index for performance
                $table->index(['team_id', 'name']);
                
                // Add foreign key constraint if the teams table exists
                if (Schema::hasTable('teams')) {
                    $table->foreign('team_id')
                        ->references('id')
                        ->on('teams')
                        ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('roles', 'team_id')) {
            Schema::table('roles', function (Blueprint $table) {
                // Drop the index
                $table->dropIndex(['team_id', 'name']);
                
                // Drop the foreign key if it exists
                if (Schema::hasTable('teams')) {
                    $table->dropForeign(['team_id']);
                }
                
                // Drop the column
                $table->dropColumn('team_id');
            });
        }
    }
};