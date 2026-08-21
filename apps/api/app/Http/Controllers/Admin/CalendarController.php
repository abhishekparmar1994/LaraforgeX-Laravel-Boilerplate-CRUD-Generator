<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing the Agency Unified Calendar.
 */
class CalendarController extends Controller
{
    /**
     * Display calendar view.
     */
    public function index(): View
    {
        return view('admin.calendar.index');
    }

    /**
     * Return JSON calendar event items for the interactive calendar.
     */
    public function events(Request $request): JsonResponse
    {
        $events = [];

        // 1. Task Deadlines
        $tasks = Task::with('project')->whereNotNull('due_date')->get();
        foreach ($tasks as $task) {
            $events[] = [
                'id' => 'task_' . $task->id,
                'title' => '📋 Task: ' . $task->title,
                'start' => $task->due_date->format('Y-m-d'),
                'color' => $task->status === 'completed' ? '#10b981' : ($task->is_overdue ? '#ef4444' : '#6366f1'),
                'type' => 'Task',
                'url' => '/admin/tasks',
                'description' => "Project: " . ($task->project ? $task->project->name : 'N/A') . " | Priority: " . ucfirst($task->priority),
            ];
        }

        // 2. Follow-ups & Client Meetings
        $followups = FollowUp::with(['lead', 'client', 'contact'])->whereNotNull('scheduled_at')->get();
        foreach ($followups as $f) {
            $targetName = $f->lead ? $f->lead->company_name : ($f->client ? $f->client->name : 'Contact');
            $events[] = [
                'id' => 'followup_' . $f->id,
                'title' => '📞 ' . ucfirst(str_replace('_', ' ', $f->follow_up_type)) . ': ' . $targetName,
                'start' => $f->scheduled_at->format('Y-m-d\TH:i:s'),
                'color' => '#f59e0b',
                'type' => 'Follow-up',
                'url' => '/admin/followups',
                'description' => "Notes: " . ($f->notes ?? 'Scheduled follow-up'),
            ];
        }

        // 3. Project Deadlines
        $projects = Project::whereNotNull('deadline')->get();
        foreach ($projects as $p) {
            $events[] = [
                'id' => 'project_' . $p->id,
                'title' => '🚀 Launch: ' . $p->name,
                'start' => $p->deadline->format('Y-m-d'),
                'color' => '#8b5cf6',
                'type' => 'Project Deadline',
                'url' => "/admin/projects/{$p->id}",
                'description' => "Project launch target: {$p->name} ({$p->progress}%)",
            ];
        }

        // 4. Domain Expiry Dates
        $websitesWithDomains = Website::whereNotNull('domain_expiry_date')->get();
        foreach ($websitesWithDomains as $w) {
            $events[] = [
                'id' => 'domain_' . $w->id,
                'title' => '🌐 Domain Expiry: ' . ($w->domain ?? $w->name),
                'start' => $w->domain_expiry_date->format('Y-m-d'),
                'color' => '#ec4899',
                'type' => 'Domain Renewal',
                'url' => "/admin/websites/{$w->id}",
                'description' => "Domain renewal due for {$w->name}",
            ];
        }

        // 5. Hosting Expiry Dates
        $websitesWithHosting = Website::whereNotNull('hosting_expiry_date')->get();
        foreach ($websitesWithHosting as $w) {
            $events[] = [
                'id' => 'hosting_' . $w->id,
                'title' => '☁️ Hosting Expiry: ' . $w->name,
                'start' => $w->hosting_expiry_date->format('Y-m-d'),
                'color' => '#06b6d4',
                'type' => 'Hosting Renewal',
                'url' => "/admin/websites/{$w->id}",
                'description' => "Hosting provider renewal due: {$w->hosting_provider}",
            ];
        }

        // 6. Payment Due Dates
        $payments = Payment::with('client')->whereNotNull('due_date')->where('status', '!=', 'paid')->get();
        foreach ($payments as $pay) {
            $events[] = [
                'id' => 'payment_' . $pay->id,
                'title' => '💵 Payment Due: $' . number_format($pay->amount, 0) . ' (' . ($pay->client ? $pay->client->name : 'Client') . ')',
                'start' => $pay->due_date->format('Y-m-d'),
                'color' => '#14b8a6',
                'type' => 'Invoice Due',
                'url' => '/admin/payments',
                'description' => "Invoice #{$pay->invoice_number} due date",
            ];
        }

        return response()->json($events);
    }
}
