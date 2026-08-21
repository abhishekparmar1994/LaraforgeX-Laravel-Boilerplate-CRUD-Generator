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
            if (!Schema::hasColumn('crm_leads', 'has_website')) {
                $table->string('has_website')->default('NO')->after('website');
            }
            if (!Schema::hasColumn('crm_leads', 'lead_priority_tag')) {
                $table->string('lead_priority_tag')->nullable()->after('has_website');
            }
            if (!Schema::hasColumn('crm_leads', 'products_services')) {
                $table->text('products_services')->nullable()->after('industry');
            }
            if (!Schema::hasColumn('crm_leads', 'area_locality')) {
                $table->string('area_locality')->nullable()->after('location');
            }
            if (!Schema::hasColumn('crm_leads', 'full_address')) {
                $table->text('full_address')->nullable()->after('area_locality');
            }
            if (!Schema::hasColumn('crm_leads', 'rating')) {
                $table->string('rating')->nullable()->after('full_address');
            }
            if (!Schema::hasColumn('crm_leads', 'total_reviews')) {
                $table->string('total_reviews')->nullable()->after('rating');
            }
            if (!Schema::hasColumn('crm_leads', 'website_build_prompt')) {
                $table->longText('website_build_prompt')->nullable()->after('requirement');
            }
            if (!Schema::hasColumn('crm_leads', 'google_maps_link')) {
                $table->text('google_maps_link')->nullable()->after('website_build_prompt');
            }
            if (!Schema::hasColumn('crm_leads', 'scraped_by')) {
                $table->string('scraped_by')->nullable()->after('lead_source');
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
                'has_website',
                'lead_priority_tag',
                'products_services',
                'area_locality',
                'full_address',
                'rating',
                'total_reviews',
                'website_build_prompt',
                'google_maps_link',
                'scraped_by',
            ]);
        });
    }
};
