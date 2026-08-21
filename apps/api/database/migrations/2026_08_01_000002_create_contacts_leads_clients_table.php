<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable()->constrained('crm_companies')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('linkedin')->nullable();
            $table->date('birthday')->nullable();
            $table->string('preferred_contact_method')->default('Email'); // Email, Phone, WhatsApp, Meeting
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->string('lead_source')->default('Website'); // Website, Google, Facebook, Instagram, LinkedIn, Referral, WhatsApp, Cold Outreach, Fiverr, Upwork, Other
            $table->string('location')->nullable();
            $table->text('requirement')->nullable();
            $table->decimal('estimated_budget', 12, 2)->default(0.00);
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('new'); // new, contacted, qualified, proposal_sent, negotiation, won, lost
            $table->foreignUuid('assigned_to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('last_contact_date')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable()->constrained('crm_companies')->onDelete('cascade');
            $table->foreignUuid('primary_contact_id')->nullable()->constrained('crm_contacts')->onDelete('set null');
            $table->foreignUuid('lead_id')->nullable()->constrained('crm_leads')->onDelete('set null');
            $table->string('client_code')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->string('industry')->nullable();
            $table->date('client_since')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_clients');
        Schema::dropIfExists('crm_leads');
        Schema::dropIfExists('crm_contacts');
    }
};
