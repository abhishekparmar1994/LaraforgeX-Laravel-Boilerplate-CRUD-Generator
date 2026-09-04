<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Project;
use Carbon\Carbon;

/**
 * Searches and filters web engineering and client projects.
 */
class SearchProjectsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'search_projects';
    }

    public function getDescription(): string
    {
        return 'Search projects by name, status (planning, design, development, testing, launch, completed), priority, or overdue deadline.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'search' => [
                    'type' => 'string',
                    'description' => 'Keyword search in project name or description.'
                ],
                'status' => [
                    'type' => 'string',
                    'description' => 'planning, design, development, testing, client_review, launch, completed, on_hold.'
                ],
                'priority' => [
                    'type' => 'string',
                    'description' => 'low, medium, high, urgent.'
                ],
                'client_id' => [
                    'type' => 'string',
                    'description' => 'UUID of the client.'
                ],
                'is_overdue' => [
                    'type' => 'boolean',
                    'description' => 'Set to true to find projects past their deadline.'
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
        $query = Project::with(['client', 'manager']);

        if (!empty($arguments['search'])) {
            $s = trim($arguments['search']);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if (!empty($arguments['status'])) {
            $query->where('status', strtolower($arguments['status']));
        }

        if (!empty($arguments['priority'])) {
            $query->where('priority', strtolower($arguments['priority']));
        }

        if (!empty($arguments['client_id'])) {
            $query->where('client_id', $arguments['client_id']);
        }

        if (!empty($arguments['is_overdue'])) {
            $query->whereNotIn('status', ['completed', 'launch'])
                  ->whereNotNull('deadline')
                  ->where('deadline', '<', Carbon::today());
        }

        $totalCount = (clone $query)->count();
        $limit = min(max((int) ($arguments['limit'] ?? 15), 1), 25);
        $records = $query->latest('created_at')->limit($limit)->get();

        $formatted = $records->map(fn (Project $p) => [
            'id'           => (string) $p->id,
            'name'         => $p->name,
            'client'       => $p->client?->name ?? 'None',
            'project_type' => $p->project_type,
            'status'       => $p->status,
            'priority'     => $p->priority,
            'progress'     => $p->progress . '%',
            'budget'       => round((float) $p->budget, 2),
            'deadline'     => $p->deadline?->toDateString(),
            'is_overdue'   => $p->is_overdue,
            'manager'      => $p->manager?->name ?? 'Unassigned',
            'view_url'     => "/admin/projects/{$p->id}",
        ])->toArray();

        return [
            'status'        => 'success',
            'total_matches' => $totalCount,
            'count'         => count($formatted),
            'projects'      => $formatted,
        ];
    }
}
