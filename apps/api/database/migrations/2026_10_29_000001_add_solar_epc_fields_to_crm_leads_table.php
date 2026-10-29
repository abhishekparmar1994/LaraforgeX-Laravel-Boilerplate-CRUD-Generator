<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to add Solar EPC & Rooftop specific fields to crm_leads table.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('consumer_number', 100)->nullable()->after('whatsapp');
            $table->string('discom', 100)->nullable()->after('consumer_number');
            $table->decimal('sanctioned_load', 8, 2)->nullable()->after('discom');
            $table->decimal('monthly_bill', 10, 2)->nullable()->after('sanctioned_load');
            $table->decimal('solar_capacity_kw', 8, 2)->nullable()->after('monthly_bill');
            $table->decimal('subsidy_amount', 10, 2)->nullable()->after('solar_capacity_kw');
            $table->string('product_group', 100)->nullable()->after('industry');
            $table->string('customer_group', 100)->nullable()->after('product_group');
            $table->string('lead_potential', 50)->nullable()->after('customer_group');
            $table->string('lead_type', 100)->nullable()->after('lead_potential');
            $table->string('society_name', 255)->nullable()->after('lead_type');
            $table->string('approval_status', 100)->nullable()->after('society_name');
            $table->string('franchise', 100)->nullable()->after('approval_status');
            $table->string('pincode', 20)->nullable()->after('full_address');
            $table->string('city', 100)->nullable()->after('pincode');
            $table->string('state', 100)->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn([
                'consumer_number',
                'discom',
                'sanctioned_load',
                'monthly_bill',
                'solar_capacity_kw',
                'subsidy_amount',
                'product_group',
                'customer_group',
                'lead_potential',
                'lead_type',
                'society_name',
                'approval_status',
                'franchise',
                'pincode',
                'city',
                'state',
            ]);
        });
    }
};

