<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('crm_solar_material_categories')) {
            Schema::create('crm_solar_material_categories', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('unit', 50)->default('NOS');
                $table->decimal('default_gst_percent', 5, 2)->default(12.00);
                $table->boolean('include_in_solar_kit')->default(true);
                $table->boolean('display_on_invoice')->default(true);
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('crm_solar_material_companies')) {
            Schema::create('crm_solar_material_companies', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('brand_tier', 50)->default('Tier-1 (ALMM)');
                $table->string('website')->nullable();
                $table->string('contact_person')->nullable();
                $table->string('contact_phone', 100)->nullable();
                $table->string('contact_email')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('crm_solar_materials')) {
            Schema::create('crm_solar_materials', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('category_id')->constrained('crm_solar_material_categories')->cascadeOnDelete();
                $table->foreignUuid('company_id')->nullable()->constrained('crm_solar_material_companies')->nullOnDelete();
                $table->string('name');
                $table->string('model_number', 150)->nullable();
                $table->string('specifications')->nullable();
                $table->string('unit', 50)->default('NOS');
                $table->decimal('purchase_price', 12, 2)->default(0.00);
                $table->decimal('selling_price', 12, 2)->default(0.00);
                $table->decimal('gst_percent', 5, 2)->default(12.00);
                $table->integer('warranty_years')->default(10);
                $table->integer('in_stock')->default(50);
                $table->boolean('is_active')->default(true);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('crm_solar_project_bom')) {
            Schema::create('crm_solar_project_bom', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('project_id')->constrained('crm_projects')->cascadeOnDelete();
                $table->foreignUuid('material_id')->constrained('crm_solar_materials')->cascadeOnDelete();
                $table->decimal('quantity', 10, 2)->default(1.00);
                $table->decimal('unit_rate', 12, 2)->default(0.00);
                $table->decimal('gst_percent', 5, 2)->default(12.00);
                $table->decimal('total_cost', 12, 2)->default(0.00);
                $table->string('status', 50)->default('allocated');
                $table->text('serial_numbers')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_solar_project_bom');
        Schema::dropIfExists('crm_solar_materials');
        Schema::dropIfExists('crm_solar_material_companies');
        Schema::dropIfExists('crm_solar_material_categories');
    }
};

