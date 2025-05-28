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
            // Website and social media fields
            $table->string('website')->nullable()->after('email');
            $table->string('linkedin_profile')->nullable()->after('website');
            $table->string('twitter_handle')->nullable()->after('linkedin_profile');
            $table->string('facebook_profile')->nullable()->after('twitter_handle');
            $table->string('instagram_handle')->nullable()->after('facebook_profile');
            
            // Alternative contact information
            $table->string('alternative_phone')->nullable()->after('phone_number');
            $table->string('alternative_email')->nullable()->after('email');
            $table->string('whatsapp_number')->nullable()->after('alternative_phone');
            
            // Business/Professional fields
            $table->string('job_title')->nullable()->after('company');
            $table->string('department')->nullable()->after('job_title');
            $table->string('industry')->nullable()->after('department');
            $table->decimal('annual_revenue', 15, 2)->nullable()->after('industry');
            $table->integer('company_size')->nullable()->after('annual_revenue');
            
            // Address fields
            $table->text('address')->nullable()->after('company_size');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->default('Ghana')->after('postal_code');
            
            // CRM specific fields
            $table->enum('lead_status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost'])->default('new')->after('country');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('lead_status');
            $table->string('lead_source')->nullable()->after('priority'); // referral, website, social_media, etc.
            $table->decimal('potential_value', 10, 2)->nullable()->after('lead_source');
            $table->date('last_contact_date')->nullable()->after('potential_value');
            $table->date('next_follow_up_date')->nullable()->after('last_contact_date');
            
            // Tags and categorization
            $table->json('tags')->nullable()->after('next_follow_up_date');
            $table->text('internal_notes')->nullable()->after('notes');
            
            // WhatsApp availability flag
            $table->boolean('has_whatsapp')->default(false)->after('whatsapp_number');
            $table->timestamp('whatsapp_checked_at')->nullable()->after('has_whatsapp');
            
            // Timezone for the contact
            $table->string('timezone')->default('Africa/Accra')->after('country');
            
            // Preferred contact method
            $table->enum('preferred_contact_method', ['phone', 'email', 'whatsapp', 'sms'])->default('phone')->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn([
                'website',
                'linkedin_profile',
                'twitter_handle',
                'facebook_profile',
                'instagram_handle',
                'alternative_phone',
                'alternative_email',
                'whatsapp_number',
                'job_title',
                'department',
                'industry',
                'annual_revenue',
                'company_size',
                'address',
                'city',
                'state',
                'postal_code',
                'country',
                'lead_status',
                'priority',
                'lead_source',
                'potential_value',
                'last_contact_date',
                'next_follow_up_date',
                'tags',
                'internal_notes',
                'has_whatsapp',
                'whatsapp_checked_at',
                'timezone',
                'preferred_contact_method'
            ]);
        });
    }
};
