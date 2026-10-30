<?php

declare(strict_types=1);

use App\Domains\CrudGenerator\Http\Controllers\CrudGeneratorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // CRM API route aliases (supports web session & admin auth)
    Route::middleware(['web', 'admin.auth'])->prefix('admin')->group(function (): void {
        // Leads
        Route::get('/leads', [\App\Http\Controllers\Admin\LeadController::class, 'index']);
        Route::post('/leads', [\App\Http\Controllers\Admin\LeadController::class, 'store']);
        Route::put('/leads/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'update']);
        Route::post('/leads/{lead}/stage', [\App\Http\Controllers\Admin\LeadController::class, 'updateStage']);
        Route::post('/leads/{lead}/convert', [\App\Http\Controllers\Admin\LeadController::class, 'convert']);
        Route::delete('/leads/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'destroy']);

        // Solar Product & Customer Groups
        Route::get('/solar-product-groups', [\App\Http\Controllers\Admin\SolarGroupController::class, 'indexProductGroups']);
        Route::post('/solar-product-groups', [\App\Http\Controllers\Admin\SolarGroupController::class, 'storeProductGroup']);
        Route::get('/solar-customer-groups', [\App\Http\Controllers\Admin\SolarGroupController::class, 'indexCustomerGroups']);
        Route::post('/solar-customer-groups', [\App\Http\Controllers\Admin\SolarGroupController::class, 'storeCustomerGroup']);

        // Tasks & Kanban
        Route::post('/tasks', [\App\Http\Controllers\Admin\TaskController::class, 'store']);
        Route::post('/tasks/{task}/status', [\App\Http\Controllers\Admin\TaskController::class, 'updateStatus']);
        Route::post('/tasks/{task}/toggle', [\App\Http\Controllers\Admin\TaskController::class, 'toggleComplete']);
        Route::put('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'update']);
        Route::delete('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'destroy']);

        // Milestones & Projects
        Route::post('/milestones/{milestone}/toggle', [\App\Http\Controllers\Admin\ProjectController::class, 'toggleMilestone']);
        Route::post('/projects/{project}/milestones', [\App\Http\Controllers\Admin\ProjectController::class, 'storeMilestone']);
        Route::post('/projects/{project}/bom', [\App\Http\Controllers\Admin\SolarMaterialController::class, 'storeBomItem']);
        Route::put('/bom/{bom}', [\App\Http\Controllers\Admin\SolarMaterialController::class, 'updateBomItem']);
        Route::delete('/bom/{bom}', [\App\Http\Controllers\Admin\SolarMaterialController::class, 'destroyBomItem']);
        Route::get('/materials', [\App\Http\Controllers\Admin\SolarMaterialController::class, 'index']);

        // Follow-ups
        Route::post('/followups', [\App\Http\Controllers\Admin\FollowUpController::class, 'store']);
        Route::post('/followups/{followup}/complete', [\App\Http\Controllers\Admin\FollowUpController::class, 'markComplete']);
        Route::put('/followups/{followup}', [\App\Http\Controllers\Admin\FollowUpController::class, 'update']);
        Route::delete('/followups/{followup}', [\App\Http\Controllers\Admin\FollowUpController::class, 'destroy']);

        // Analytics & Reports
        Route::get('/crm/analytics', [\App\Http\Controllers\Admin\CrmDashboardController::class, 'analytics']);

        // Finance - Payments & Invoices
        Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index']);
        Route::post('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'store']);
        Route::put('/payments/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'update']);
        Route::delete('/payments/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'destroy']);

        // Clients, Companies, Contacts, Websites, Projects
        Route::delete('/clients/{client}', [\App\Http\Controllers\Admin\ClientController::class, 'destroy']);
        Route::delete('/companies/{company}', [\App\Http\Controllers\Admin\CompanyController::class, 'destroy']);
        Route::delete('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy']);
        Route::delete('/websites/{website}', [\App\Http\Controllers\Admin\WebsiteController::class, 'destroy']);
        Route::delete('/projects/{project}', [\App\Http\Controllers\Admin\ProjectController::class, 'destroy']);
    });

    // Web Installer API routes
    Route::prefix('install')->group(function (): void {
        Route::get('/requirements', [\App\Http\Controllers\InstallController::class, 'checkRequirements']);
        Route::get('/permissions', [\App\Http\Controllers\InstallController::class, 'checkPermissions']);
        Route::post('/test-db', [\App\Http\Controllers\InstallController::class, 'testDatabase']);
        Route::post('/process', [\App\Http\Controllers\InstallController::class, 'install']);
    });

    // Authenticated System & Administration APIs
    Route::middleware('auth:sanctum')->group(function (): void {
        // AI CRM Agent API routes
        Route::prefix('agent')->group(function (): void {
            Route::post('/chat', [\App\Http\Controllers\Api\AgentApiController::class, 'chat']);
            Route::get('/conversations', [\App\Http\Controllers\Api\AgentApiController::class, 'conversations']);
            Route::get('/conversations/{id}', [\App\Http\Controllers\Api\AgentApiController::class, 'conversation']);
            Route::delete('/conversations/{id}', [\App\Http\Controllers\Api\AgentApiController::class, 'deleteConversation']);
        });

        // Notification Hub API routes
        Route::prefix('notifications')->group(function (): void {
            Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index']);
            Route::post('/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead']);
        });

        // Activity Audit Logs API routes
        Route::prefix('activity-logs')->group(function (): void {
            Route::get('/', [\App\Http\Controllers\ActivityLogController::class, 'index']);
            Route::delete('/', [\App\Http\Controllers\ActivityLogController::class, 'clear']);
        });

        // System Health & SMTP API routes
        Route::prefix('health')->group(function (): void {
            Route::get('/metrics', [\App\Http\Controllers\HealthController::class, 'metrics']);
            Route::post('/test-mail', [\App\Http\Controllers\HealthController::class, 'testMail']);
        });

        // Outgoing Webhooks API routes
        Route::prefix('webhooks')->group(function (): void {
            Route::get('/', [\App\Http\Controllers\WebhookController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\WebhookController::class, 'store']);
            Route::post('/test', [\App\Http\Controllers\WebhookController::class, 'test']);
            Route::delete('/{id}', [\App\Http\Controllers\WebhookController::class, 'destroy']);
        });

        // Database Backups API routes
        Route::prefix('backups')->group(function (): void {
            Route::get('/', [\App\Http\Controllers\BackupController::class, 'index']);
            Route::post('/generate', [\App\Http\Controllers\BackupController::class, 'generate']);
            Route::get('/download/{filename}', [\App\Http\Controllers\BackupController::class, 'download']);
            Route::delete('/{filename}', [\App\Http\Controllers\BackupController::class, 'destroy']);
        });

        // CRUD Generator API routes
        Route::prefix('crud-generator')->group(function (): void {
            Route::get('/connections', [CrudGeneratorController::class, 'connections']);
            Route::get('/databases', [CrudGeneratorController::class, 'databases']);
            Route::get('/tables', [CrudGeneratorController::class, 'tables']);
            Route::get('/schema', [CrudGeneratorController::class, 'schema']);
            Route::post('/preview', [CrudGeneratorController::class, 'preview']);
            Route::post('/generate', [CrudGeneratorController::class, 'generate']);
            Route::post('/publish', [CrudGeneratorController::class, 'publish']);
            Route::get('/download/{module}', [CrudGeneratorController::class, 'download']);
        });

        // Database Studio / Table Manager API routes
        Route::prefix('database-manager')->group(function (): void {
            Route::get('/', [\App\Http\Controllers\DatabaseManagerController::class, 'index']);
            Route::post('/create', [\App\Http\Controllers\DatabaseManagerController::class, 'store']);
            Route::get('/{table}', [\App\Http\Controllers\DatabaseManagerController::class, 'show']);
            Route::get('/{table}/data', [\App\Http\Controllers\DatabaseManagerController::class, 'data']);
            Route::get('/{table}/export', [\App\Http\Controllers\DatabaseManagerController::class, 'exportData']);
            Route::post('/{table}/indexes', [\App\Http\Controllers\DatabaseManagerController::class, 'addIndex']);
            Route::delete('/{table}/indexes/{indexName}', [\App\Http\Controllers\DatabaseManagerController::class, 'dropIndex']);
            Route::post('/bulk-action', [\App\Http\Controllers\DatabaseManagerController::class, 'bulkAction']);
            Route::post('/{table}/drop-columns', [\App\Http\Controllers\DatabaseManagerController::class, 'dropColumns']);
            Route::put('/{table}/columns/{column}', [\App\Http\Controllers\DatabaseManagerController::class, 'modifyColumn']);
            Route::post('/execute-sql', [\App\Http\Controllers\DatabaseManagerController::class, 'executeSql']);
            Route::post('/{table}/truncate', [\App\Http\Controllers\DatabaseManagerController::class, 'truncate']);
            Route::delete('/{table}', [\App\Http\Controllers\DatabaseManagerController::class, 'destroy']);
        });
    });
});
