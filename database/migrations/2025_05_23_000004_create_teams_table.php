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
        // Create teams table
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('personal_team')->default(false);
            $table->boolean('share_sms_credits')->default(false);
            $table->boolean('share_contacts')->default(false);
            $table->boolean('share_sender_names')->default(false);
            $table->timestamps();
        });

        // Create team_user pivot table
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role');
            $table->json('permissions')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
        });

        // Create team_invitations table
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('role');
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique(['team_id', 'email']);
        });

        // Create team_resources table
        Schema::create('team_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('resource_type');
            $table->unsignedBigInteger('resource_id');
            $table->boolean('is_shared')->default(true);
            $table->timestamps();

            $table->unique(['team_id', 'resource_type', 'resource_id'], 'team_resources_unique');
            $table->index(['resource_type', 'resource_id']);
        });

        // Add current_team_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_team_id')->nullable()->after('remember_token')
                  ->constrained('teams')->nullOnDelete();
        });

        // Add team_id to contacts table
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->after('user_id')
                  ->constrained()->nullOnDelete();
            $table->index(['team_id', 'user_id']);
        });

        // Add team_id to sender_names table
        Schema::table('sender_names', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->after('user_id')
                  ->constrained()->nullOnDelete();
            $table->index(['team_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop team_id from sender_names
        Schema::table('sender_names', function (Blueprint $table) {
            $table->dropIndex(['team_id', 'status']);
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        // Drop team_id from contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['team_id', 'user_id']);
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        // Drop current_team_id from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_team_id']);
            $table->dropColumn('current_team_id');
        });

        // Drop team-related tables
        Schema::dropIfExists('team_resources');
        Schema::dropIfExists('team_invitations');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
    }
};