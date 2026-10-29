<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create crm_solar_quotations table for formal Solar EPC proposals.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_solar_quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lead_id')->nullable()->index();
            $table->uuid('client_id')->nullable()->index();
            $table->string('quotation_number', 100)->unique();
            $table->decimal('system_capacity_kw', 8, 2)->default(3.00);
            $table->string('panel_type', 150)->default('Monocrystalline Bifacial 550W');
            $table->string('inverter_type', 150)->default('On-Grid String Inverter');
            $table->decimal('total_cost', 12, 2)->default(0.00);
            $table->decimal('subsidy_amount', 10, 2)->default(0.00);
            $table->decimal('net_payable', 12, 2)->default(0.00);
            $table->integer('annual_generation_units')->default(4200);
            $table->decimal('annual_savings', 10, 2)->default(31500.00);
            $table->string('status', 50)->default('draft');
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_solar_quotations');
    }
};

