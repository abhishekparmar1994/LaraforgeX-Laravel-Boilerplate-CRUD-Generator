<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create crm_solar_net_metering table for tracking DISCOM & PM Surya Ghar applications.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_solar_net_metering', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lead_id')->nullable()->index();
            $table->uuid('client_id')->nullable()->index();
            $table->string('application_number', 100)->nullable();
            $table->string('consumer_number', 100)->nullable();
            $table->string('discom', 100)->nullable();
            $table->decimal('sanctioned_load', 8, 2)->nullable();
            $table->string('stage', 100)->default('document_verification');
            $table->string('electricity_bill_doc', 255)->nullable();
            $table->string('aadhaar_doc', 255)->nullable();
            $table->string('pan_doc', 255)->nullable();
            $table->string('cancel_cheque_doc', 255)->nullable();
            $table->string('tax_receipt_doc', 255)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('commissioned_at')->nullable();
            $table->timestamp('subsidy_credited_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_solar_net_metering');
    }
};

