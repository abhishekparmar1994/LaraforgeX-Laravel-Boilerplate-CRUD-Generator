<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->nullable()->constrained('crm_projects')->onDelete('cascade');
            $table->foreignUuid('website_id')->nullable()->constrained('crm_websites')->onDelete('set null');
            $table->foreignUuid('client_id')->nullable()->constrained('crm_clients')->onDelete('set null');
            $table->foreignUuid('assigned_to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignUuid('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('todo'); // todo, in_progress, review, blocked, completed
            $table->date('due_date')->nullable();
            $table->decimal('estimated_hours', 8, 1)->default(0.0);
            $table->decimal('actual_hours', 8, 1)->default(0.0);
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_followups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lead_id')->nullable()->constrained('crm_leads')->onDelete('cascade');
            $table->foreignUuid('client_id')->nullable()->constrained('crm_clients')->onDelete('cascade');
            $table->foreignUuid('contact_id')->nullable()->constrained('crm_contacts')->onDelete('set null');
            $table->foreignUuid('assigned_to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('scheduled_at');
            $table->string('follow_up_type')->default('phone_call'); // phone_call, email, whatsapp, meeting, demo, proposal_followup, payment_followup
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, rescheduled, cancelled
            $table->timestamps();
        });

        Schema::create('crm_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('subject_type');
            $table->uuid('subject_id');
            $table->string('type')->default('note'); // call, email, whatsapp, meeting, note, status_change, task_update, payment, deployment
            $table->text('description');
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);
        });

        Schema::create('crm_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('notable_type');
            $table->uuid('notable_id');
            $table->string('title')->nullable();
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            $table->index(['notable_type', 'notable_id']);
        });

        Schema::create('crm_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained('crm_clients')->onDelete('cascade');
            $table->foreignUuid('project_id')->nullable()->constrained('crm_projects')->onDelete('set null');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->date('payment_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('payment_method')->default('Bank Transfer'); // Bank Transfer, Stripe, PayPal, Razorpay, Cash, Check
            $table->string('status')->default('pending'); // pending, partial, paid, overdue, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_payments');
        Schema::dropIfExists('crm_notes');
        Schema::dropIfExists('crm_activities');
        Schema::dropIfExists('crm_followups');
        Schema::dropIfExists('crm_tasks');
    }
};
