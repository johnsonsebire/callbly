<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create team resources table
        Schema::create('team_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('resource_type'); // sender_name, contact, sms_credit
            $table->unsignedBigInteger('resource_id');
            $table->boolean('is_shared')->default(false);
            $table->timestamps();

            $table->unique(['team_id', 'resource_type', 'resource_id']);
            $table->index(['resource_type', 'resource_id']);
        });

        // Add description field to sender_names if it doesn't exist
        if (!Schema::hasColumn('sender_names', 'description')) {
            Schema::table('sender_names', function (Blueprint $table) {
                $table->text('description')->nullable()->after('name');
            });
        }

        // Add status index to sender_names if it doesn't exist
        if (!Schema::hasIndex('sender_names', 'sender_names_team_id_status_index')) {
            Schema::table('sender_names', function (Blueprint $table) {
                $table->index(['team_id', 'status'], 'sender_names_team_id_status_index');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('team_resources');

        if (Schema::hasColumn('sender_names', 'description')) {
            Schema::table('sender_names', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }

        if (Schema::hasIndex('sender_names', 'sender_names_team_id_status_index')) {
            Schema::table('sender_names', function (Blueprint $table) {
                $table->dropIndex('sender_names_team_id_status_index');
            });
        }
    }
};