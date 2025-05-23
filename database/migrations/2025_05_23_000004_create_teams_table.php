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

        // Add current_team_id to users table if it doesn't exist
        if (!Schema::hasColumn('users', 'current_team_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('current_team_id')->nullable()->after('remember_token')
                      ->constrained('teams')->nullOnDelete();
            });
        }

        // Add team_id to contacts table if it doesn't exist
        if (!Schema::hasColumn('contacts', 'team_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->after('user_id')
                      ->constrained()->nullOnDelete();
            });
        }

        // Check if the index exists before creating it
        $contactIndexExists = collect(DB::select("SHOW INDEXES FROM contacts WHERE Key_name = 'contacts_team_id_user_id_index'"))->isNotEmpty();
        if (!$contactIndexExists) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->index(['team_id', 'user_id']);
            });
        }

        // Add team_id to sender_names table if it doesn't exist
        if (!Schema::hasColumn('sender_names', 'team_id')) {
            Schema::table('sender_names', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->after('user_id')
                      ->constrained()->nullOnDelete();
            });
        }

        // Check if the index exists before creating it
        $senderNameIndexExists = collect(DB::select("SHOW INDEXES FROM sender_names WHERE Key_name = 'sender_names_team_id_status_index'"))->isNotEmpty();
        if (!$senderNameIndexExists) {
            Schema::table('sender_names', function (Blueprint $table) {
                $table->index(['team_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes first
        if (Schema::hasTable('sender_names')) {
            $senderNameIndexExists = collect(DB::select("SHOW INDEXES FROM sender_names WHERE Key_name = 'sender_names_team_id_status_index'"))->isNotEmpty();
            if ($senderNameIndexExists) {
                Schema::table('sender_names', function (Blueprint $table) {
                    $table->dropIndex('sender_names_team_id_status_index');
                });
            }
        }

        if (Schema::hasTable('contacts')) {
            $contactIndexExists = collect(DB::select("SHOW INDEXES FROM contacts WHERE Key_name = 'contacts_team_id_user_id_index'"))->isNotEmpty();
            if ($contactIndexExists) {
                Schema::table('contacts', function (Blueprint $table) {
                    $table->dropIndex('contacts_team_id_user_id_index');
                });
            }
        }

        // Drop foreign keys and columns
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

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'current_team_id')) {
                    $table->dropForeign(['current_team_id']);
                    $table->dropColumn('current_team_id');
                }
            });
        }

        // Drop team-related tables
        Schema::dropIfExists('team_resources');
        Schema::dropIfExists('team_invitations');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
    }
};