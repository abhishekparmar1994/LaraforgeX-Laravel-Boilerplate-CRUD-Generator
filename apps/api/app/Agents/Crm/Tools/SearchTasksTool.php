<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Task;
use Carbon\Carbon;

/**
 * Searches and filters tasks across CRM projects and clients.
 */
class SearchTasksTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'search_tasks';
    }

    public function getDescription(): string
    {
        return 'Search and filter tasks by status (todo, in_progress, review, blocked, completed), priority, project, developer, or overdue status.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'search' => [
                    'type' => 'string',
                    'description' => 'Keyword search in task title or description.'
                ],
                'status' => [
                    'type' => 'string',
                    'description' => 'Filter by status: todo, in_progress, review, blocked, completed.'
                ],
                'priority' => [
                    'type' => 'string',
                    'description' => 'Filter by priority: low, medium, high, urgent.'
                ],
                'is_overdue' => [
                    'type' => 'boolean',
                    'description' => 'Set to true to find overdue unfinished tasks.'
                ],
                'project_id' => [
                    'type' => 'string',
                    'description' => 'UUID of the project.'
                ],
                'assigned_to' => [
                    'type' => 'string',
                    'description' => 'UUID of assigned user.'
                ],
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Max records (default: 15, max: 25).'
                ],
            ],
            'required' => [],
        ];
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function getRequiredPermission(): ?string
    {
        return null;
    }

    public function execute(array $arguments, AgentContext $context): array
    {
        $query = Task::with(['project', 'client', 'assignedTo']);

        if (!empty($arguments['search'])) {
            $s = trim($arguments['search']);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if (!empty($arguments['status'])) {
            $query->where('status', strtolower($arguments['status']));
        }

        if (!empty($arguments['priority'])) {
            $query->where('priority', strtolower($arguments['priority']));
        }

        if (!empty($arguments['project_id'])) {
            $query->where('project_id', $arguments['project_id']);
        }

        if (!empty($arguments['assigned_to'])) {
            $query->where('assigned_to_user_id', $arguments['assigned_to']);
        }

        if (!empty($arguments['is_overdue'])) {
            $query->where('status', '!=', 'completed')
                  ->whereNotNull('due_date')
                  ->where('due_date', '<', Carbon::today());
        }

        $totalCount = (clone $query)->count();
        $limit = min(max((int) ($arguments['limit'] ?? 15), 1), 25);
        $tasks = $query->orderBy('due_date')->limit($limit)->get();

        $formatted = $tasks->map(fn (Task $t) => [
            'id'          => (string) $t->id,
            'title'       => $t->title,
            'project'     => $t->project?->name ?? 'None',
            'client'      => $t->client?->name ?? 'None',
            'assigned_to' => $t->assignedTo?->name ?? 'Unassigned',
            'status'      => $t->status,
            'priority'    => $t->priority,
            'due_date'    => $t->due_date?->toDateString(),
            'is_overdue'  => $t->is_overdue,
            'view_url'    => "/admin/tasks/{$t->id}",
        ])->toArray();

        return [
            'status'        => 'success',
            'total_matches' => $totalCount,
            'count'         => count($formatted),
            'tasks'         => $formatted,
        ];
    }
}
