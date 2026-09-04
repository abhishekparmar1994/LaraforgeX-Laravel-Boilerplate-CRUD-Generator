<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CrmDashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\FollowUpController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstallController;

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

Route::get('/install', [InstallController::class, 'index']);

// ── MAINTENANCE ROUTES (Protected: Local/Admin Only) ────────────────
Route::middleware(['admin.auth'])->group(function () {
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        return "All caches cleared successfully";
    });

    Route::get('/storage-link', function () {
        Artisan::call('storage:link');
        return "Storage link created successfully";
    });

    Route::get('/migrate-db', function () {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return "<h2 style='color:green'>✅ Database migrations executed successfully!</h2><pre>" . e(Artisan::output()) . "</pre>";
        } catch (\Throwable $e) {
            return "<h2 style='color:red'>❌ Migration Error:</h2><pre>" . e($e->getMessage()) . "\n\n" . e($e->getTraceAsString()) . "</pre>";
        }
    });

    Route::get('/seed-db', function () {
        try {
            Artisan::call('db:seed', ['--force' => true]);
            return "<h2 style='color:green'>✅ Database seeders executed successfully!</h2><pre>" . e(Artisan::output()) . "</pre>";
        } catch (\Throwable $e) {
            return "<h2 style='color:red'>❌ Seeder Error:</h2><pre>" . e($e->getMessage()) . "\n\n" . e($e->getTraceAsString()) . "</pre>";
        }
    });
});

Route::get('/landing', function () {
    return file_get_contents(public_path('codecanyon_landing.html'));
});

// ── AUTHENTICATION BRIDGE ROUTES (Web Portal AJAX) ─────────────────
Route::prefix('auth')->group(function () {
    Route::get('/captcha-config', [\App\Domains\User\Controllers\AuthController::class, 'captchaConfig']);
    Route::post('/login', [\App\Domains\User\Controllers\AuthController::class, 'login']);
    Route::post('/magic-link', [\App\Domains\User\Controllers\AuthController::class, 'magicLink']);
    Route::post('/2fa/verify', [\App\Domains\User\Controllers\AuthController::class, 'twoFactorVerify']);
    Route::post('/logout', [\App\Domains\User\Controllers\AuthController::class, 'logout']);
});

// ── ADMIN GUEST ROUTES ──────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::get('/forgot-password', [AdminController::class, 'forgotPassword']);
    Route::get('/reset-password', [AdminController::class, 'resetPassword']);
});

// ── ADMIN & CRM PROTECTED ROUTES ────────────────────────────────────
Route::prefix('admin')->middleware(['admin.auth'])->group(function () {
    // Auth & Dashboard
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/dashboard', [CrmDashboardController::class, 'index']);
    Route::get('/ai-agent', [\App\Http\Controllers\Admin\AiAgentController::class, 'index']);
    Route::get('/crm/analytics', [CrmDashboardController::class, 'analytics']);
    Route::get('/global-search', [SearchController::class, 'search']);

    // CRM — Leads & Pipeline
    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/leads/kanban', [LeadController::class, 'kanban']);
    Route::post('/leads', [LeadController::class, 'store']);
    Route::post('/leads/import', [LeadController::class, 'import']);
    Route::get('/leads/{lead}', [LeadController::class, 'show']);
    Route::put('/leads/{lead}', [LeadController::class, 'update']);
    Route::post('/leads/{lead}/stage', [LeadController::class, 'updateStage']);
    Route::post('/leads/{lead}/convert', [LeadController::class, 'convert']);
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy']);

    // CRM — Companies
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::post('/companies', [CompanyController::class, 'store']);
    Route::get('/companies/{company}', [CompanyController::class, 'show']);
    Route::put('/companies/{company}', [CompanyController::class, 'update']);
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);

    // CRM — Contacts
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/contacts/{contact}', [ContactController::class, 'show']);
    Route::put('/contacts/{contact}', [ContactController::class, 'update']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);

    // CRM — Clients
    Route::get('/clients', [ClientController::class, 'index']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::put('/clients/{client}', [ClientController::class, 'update']);
    Route::delete('/clients/{client}', [ClientController::class, 'destroy']);

    // Projects — Websites & Credentials Vault
    Route::get('/websites', [WebsiteController::class, 'index']);
    Route::post('/websites', [WebsiteController::class, 'store']);
    Route::get('/websites/{website}', [WebsiteController::class, 'show']);
    Route::put('/websites/{website}', [WebsiteController::class, 'update']);
    Route::post('/websites/{website}/credentials', [WebsiteController::class, 'storeCredential']);
    Route::post('/credentials/{credential}/reveal', [WebsiteController::class, 'revealCredential']);
    Route::delete('/credentials/{credential}', [WebsiteController::class, 'destroyCredential']);
    Route::delete('/websites/{website}', [WebsiteController::class, 'destroy']);

    // Projects — Projects & Milestones
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::post('/projects/{project}/milestones', [ProjectController::class, 'storeMilestone']);
    Route::post('/milestones/{milestone}/toggle', [ProjectController::class, 'toggleMilestone']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    // Projects — Tasks, Kanban & Todo
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/kanban', [TaskController::class, 'kanban']);
    Route::get('/tasks/todo', [TaskController::class, 'todo']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
    Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // Projects — Calendar
    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::get('/calendar/events', [CalendarController::class, 'events']);

    // Communication — Follow-ups
    Route::get('/followups', [FollowUpController::class, 'index']);
    Route::post('/followups', [FollowUpController::class, 'store']);
    Route::put('/followups/{followup}', [FollowUpController::class, 'update']);
    Route::post('/followups/{followup}/complete', [FollowUpController::class, 'complete']);
    Route::post('/followups/{followup}/reschedule', [FollowUpController::class, 'reschedule']);
    Route::delete('/followups/{followup}', [FollowUpController::class, 'destroy']);

    // Communication — Activities & Notes
    Route::get('/activities', [ActivityController::class, 'index']);
    Route::post('/activities', [ActivityController::class, 'store']);
    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::post('/notes/{note}/pin', [NoteController::class, 'togglePin']);
    Route::delete('/notes/{note}', [NoteController::class, 'destroy']);

    // Finance — Payments & Invoices
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::put('/payments/{payment}', [PaymentController::class, 'update']);
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);

    // Team — Developers & Performance
    Route::get('/team', [TeamController::class, 'index']);
    Route::get('/team/{user}', [TeamController::class, 'show']);
    Route::get('/team-performance/data', [TeamController::class, 'performanceData']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index']);

    // Core System Routes
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/roles', [AdminController::class, 'roles']);
    Route::get('/permissions', [AdminController::class, 'permissions']);
    Route::get('/media', [AdminController::class, 'media']);
    Route::get('/settings', [AdminController::class, 'settings']);
    Route::get('/profile', [AdminController::class, 'profile']);
    Route::get('/crud-generator', [AdminController::class, 'crudGenerator']);
    Route::get('/backups', [AdminController::class, 'backups']);
    Route::get('/health', [AdminController::class, 'health']);
    Route::get('/webhooks', [AdminController::class, 'webhooks']);
    Route::get('/docs', [AdminController::class, 'docs']);
    Route::get('/activity-logs', [AdminController::class, 'activityLogs']);
    Route::get('/database-manager', [AdminController::class, 'databaseManager']);
});
