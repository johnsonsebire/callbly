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
        Schema::table('contacts', function (Blueprint $table) {
            // Check and add missing basic contact fields
            if (!Schema::hasColumn('contacts', 'team_id')) {
                $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null')->after('user_id');
            }
            if (!Schema::hasColumn('contacts', 'alternative_phone')) {
                $table->string('alternative_phone')->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('contacts', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('alternative_phone');
            }
            if (!Schema::hasColumn('contacts', 'has_whatsapp')) {
                $table->boolean('has_whatsapp')->default(false)->after('whatsapp_number');
            }
            if (!Schema::hasColumn('contacts', 'whatsapp_checked_at')) {
                $table->timestamp('whatsapp_checked_at')->nullable()->after('has_whatsapp');
            }
            if (!Schema::hasColumn('contacts', 'alternative_email')) {
                $table->string('alternative_email')->nullable()->after('email');
            }
            if (!Schema::hasColumn('contacts', 'website')) {
                $table->string('website')->nullable()->after('alternative_email');
            }
            
            // Social media profiles
            if (!Schema::hasColumn('contacts', 'linkedin_profile')) {
                $table->string('linkedin_profile')->nullable()->after('website');
            }
            if (!Schema::hasColumn('contacts', 'twitter_handle')) {
                $table->string('twitter_handle')->nullable()->after('linkedin_profile');
            }
            if (!Schema::hasColumn('contacts', 'facebook_profile')) {
                $table->string('facebook_profile')->nullable()->after('twitter_handle');
            }
            if (!Schema::hasColumn('contacts', 'instagram_handle')) {
                $table->string('instagram_handle')->nullable()->after('facebook_profile');
            }
            
            // Personal information
            if (!Schema::hasColumn('contacts', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('contacts', 'region')) {
                $table->string('region')->nullable()->after('gender');
            }
            
            // Professional information
            if (!Schema::hasColumn('contacts', 'job_title')) {
                $table->string('job_title')->nullable()->after('company');
            }
            if (!Schema::hasColumn('contacts', 'department')) {
                $table->string('department')->nullable()->after('job_title');
            }
            if (!Schema::hasColumn('contacts', 'industry')) {
                $table->string('industry')->nullable()->after('department');
            }
            if (!Schema::hasColumn('contacts', 'annual_revenue')) {
                $table->decimal('annual_revenue', 15, 2)->nullable()->after('industry');
            }
            if (!Schema::hasColumn('contacts', 'company_size')) {
                $table->enum('company_size', ['1-10', '11-50', '51-200', '201-500', '501-1000', '1000+'])->nullable()->after('annual_revenue');
            }
            
            // Address information
            if (!Schema::hasColumn('contacts', 'address')) {
                $table->text('address')->nullable()->after('company_size');
            }
            if (!Schema::hasColumn('contacts', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('contacts', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('contacts', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('state');
            }
            if (!Schema::hasColumn('contacts', 'country')) {
                $table->string('country')->nullable()->after('postal_code');
            }
            if (!Schema::hasColumn('contacts', 'timezone')) {
                $table->string('timezone')->nullable()->after('country');
            }
            
            // Communication preferences
            if (!Schema::hasColumn('contacts', 'preferred_contact_method')) {
                $table->enum('preferred_contact_method', ['phone', 'email', 'whatsapp', 'sms'])->default('phone')->after('timezone');
            }
            
            // Lead management
            if (!Schema::hasColumn('contacts', 'lead_status')) {
                $table->enum('lead_status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'closed-won', 'closed-lost'])->default('new')->after('preferred_contact_method');
            }
            if (!Schema::hasColumn('contacts', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('lead_status');
            }
            if (!Schema::hasColumn('contacts', 'lead_source')) {
                $table->string('lead_source')->nullable()->after('priority');
            }
            if (!Schema::hasColumn('contacts', 'potential_value')) {
                $table->decimal('potential_value', 15, 2)->nullable()->after('lead_source');
            }
            if (!Schema::hasColumn('contacts', 'last_contact_date')) {
                $table->date('last_contact_date')->nullable()->after('potential_value');
            }
            if (!Schema::hasColumn('contacts', 'next_follow_up_date')) {
                $table->date('next_follow_up_date')->nullable()->after('last_contact_date');
            }
            
            // Tags and notes
            if (!Schema::hasColumn('contacts', 'tags')) {
                $table->json('tags')->nullable()->after('next_follow_up_date');
            }
            if (!Schema::hasColumn('contacts', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('notes');
            }
        });
        
        // Add indexes only if they don't exist
        Schema::table('contacts', function (Blueprint $table) {
            try {
                $table->index(['user_id', 'lead_status'], 'contacts_user_lead_status_index');
            } catch (\Exception $e) {
                // Index already exists or error creating it
            }
            
            try {
                $table->index(['user_id', 'priority'], 'contacts_user_priority_index');
            } catch (\Exception $e) {
                // Index already exists or error creating it
            }
            
            try {
                $table->index(['user_id', 'next_follow_up_date'], 'contacts_user_follow_up_index');
            } catch (\Exception $e) {
                // Index already exists or error creating it
            }
            
            try {
                $table->index('city', 'contacts_city_index');
            } catch (\Exception $e) {
                // Index already exists or error creating it
            }
            
            try {
                $table->index('country', 'contacts_country_index');
            } catch (\Exception $e) {
                // Index already exists or error creating it
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Drop indexes first (only if they exist)
            try {
                $table->dropIndex('contacts_user_lead_status_index');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            
            try {
                $table->dropIndex('contacts_user_priority_index');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            
            try {
                $table->dropIndex('contacts_user_follow_up_index');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            
            try {
                $table->dropIndex('contacts_city_index');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            
            try {
                $table->dropIndex('contacts_country_index');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            
            // Drop columns only if they exist (in reverse order of creation)
            $columnsToCheck = [
                'internal_notes',
                'tags',
                'next_follow_up_date',
                'last_contact_date',
                'potential_value',
                'lead_source',
                'priority',
                'lead_status',
                'preferred_contact_method',
                'timezone',
                'country',
                'postal_code',
                'state',
                'city',
                'address',
                'company_size',
                'annual_revenue',
                'industry',
                'department',
                'job_title',
                'region',
                'gender',
                'instagram_handle',
                'facebook_profile',
                'twitter_handle',
                'linkedin_profile',
                'website',
                'alternative_email',
                'whatsapp_checked_at',
                'has_whatsapp',
                'whatsapp_number',
                'alternative_phone',
                'team_id'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('contacts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
