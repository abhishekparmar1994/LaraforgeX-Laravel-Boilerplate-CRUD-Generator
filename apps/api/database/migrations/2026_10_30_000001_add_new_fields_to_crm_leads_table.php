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
        Schema::table('crm_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_leads', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('contact_person');
            }
            if (!Schema::hasColumn('crm_leads', 'last_name')) {
                $table->string('last_name', 100)->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('crm_leads', 'alternate_phone')) {
                $table->string('alternate_phone', 50)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('crm_leads', 'address_line_1')) {
                $table->string('address_line_1', 255)->nullable()->after('location');
            }
            if (!Schema::hasColumn('crm_leads', 'address_line_2')) {
                $table->string('address_line_2', 255)->nullable()->after('address_line_1');
            }
            if (!Schema::hasColumn('crm_leads', 'country')) {
                $table->string('country', 100)->nullable()->default('India')->after('state');
            }
            if (!Schema::hasColumn('crm_leads', 'salutation')) {
                $table->string('salutation', 20)->nullable()->default('Mr.')->after('customer_group');
            }
            if (!Schema::hasColumn('crm_leads', 'designation')) {
                $table->string('designation', 100)->nullable()->after('salutation');
            }
            if (!Schema::hasColumn('crm_leads', 'roof_type')) {
                $table->string('roof_type', 100)->nullable()->after('lead_type');
            }
            if (!Schema::hasColumn('crm_leads', 'follow_up_type')) {
                $table->string('follow_up_type', 100)->nullable()->after('next_followup_date');
            }
            if (!Schema::hasColumn('crm_leads', 'followup_notes')) {
                $table->text('followup_notes')->nullable()->after('follow_up_type');
            }
            if (!Schema::hasColumn('crm_leads', 'lead_list')) {
                $table->string('lead_list', 100)->nullable()->after('franchise');
            }
            if (!Schema::hasColumn('crm_leads', 'stage')) {
                $table->string('stage', 50)->nullable()->default('New Lead')->after('status');
            }
            if (!Schema::hasColumn('crm_leads', 'tags')) {
                $table->text('tags')->nullable()->after('lead_priority_tag');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'alternate_phone', 'address_line_1', 'address_line_2',
                'country', 'salutation', 'designation', 'roof_type', 'follow_up_type',
                'followup_notes', 'lead_list', 'stage', 'tags'
            ]);
        });
    }
};

