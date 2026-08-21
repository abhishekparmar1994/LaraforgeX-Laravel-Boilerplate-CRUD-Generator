<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained('crm_clients')->onDelete('cascade');
            $table->foreignUuid('company_id')->nullable()->constrained('crm_companies')->onDelete('set null');
            $table->string('name');
            $table->string('project_type')->default('Corporate Website'); // Custom Web App, Corporate Website, E-commerce, SaaS MVP, Landing Page, Redesign
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->decimal('estimated_hours', 8, 1)->default(0.0);
            $table->decimal('actual_hours', 8, 1)->default(0.0);
            $table->decimal('budget', 12, 2)->default(0.00);
            $table->string('status')->default('planning'); // planning, design, development, testing, client_review, launch, completed, on_hold
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->foreignUuid('project_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('progress')->default(0); // 0-100%
            $table->string('repository')->nullable();
            $table->string('staging_url')->nullable();
            $table->string('production_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_websites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained('crm_clients')->onDelete('cascade');
            $table->foreignUuid('company_id')->nullable()->constrained('crm_companies')->onDelete('set null');
            $table->foreignUuid('project_id')->nullable()->constrained('crm_projects')->onDelete('set null');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('domain')->nullable();
            $table->string('domain_provider')->nullable();
            $table->date('domain_expiry_date')->nullable();
            $table->string('hosting_provider')->nullable();
            $table->date('hosting_expiry_date')->nullable();
            $table->string('technology')->nullable();
            $table->string('framework')->nullable();
            $table->string('cms')->nullable();
            $table->string('repository_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('server_ip')->nullable();
            $table->string('deployment_platform')->nullable();
            $table->string('database_type')->nullable();
            $table->string('ssl_status')->default('active'); // active, expiring_soon, expired, not_installed
            $table->date('ssl_expiry_date')->nullable();
            $table->string('status')->default('development'); // planning, design, development, testing, client_review, live, maintenance, suspended, completed
            $table->integer('design_progress')->default(0);
            $table->integer('dev_progress')->default(0);
            $table->integer('testing_progress')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_website_credentials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('website_id')->constrained('crm_websites')->onDelete('cascade');
            $table->string('credential_type')->default('CMS Admin'); // CMS Admin, SSH/SFTP, cPanel, Database, API Key
            $table->string('title')->nullable();
            $table->string('username')->nullable();
            $table->text('password_encrypted')->nullable();
            $table->string('host_or_url')->nullable();
            $table->string('port')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('crm_project_members', function (Blueprint $table) {
            $table->foreignUuid('project_id')->constrained('crm_projects')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role_in_project')->default('Developer');
            $table->timestamps();
            $table->primary(['project_id', 'user_id']);
        });

        Schema::create('crm_project_milestones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->constrained('crm_projects')->onDelete('cascade');
            $table->string('title');
            $table->date('due_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_project_milestones');
        Schema::dropIfExists('crm_project_members');
        Schema::dropIfExists('crm_website_credentials');
        Schema::dropIfExists('crm_websites');
        Schema::dropIfExists('crm_projects');
    }
};
