<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Activity;

/**
 * Retrieves recent audit and interaction activity stream across CRM entities.
 */
class RecentActivityTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'recent_activity';
    }

    public function getDescription(): string
    {
        return 'Get recent CRM audit logs and activity updates across leads, clients, tasks, and projects.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'type' => [
                    'type' => 'string',
                    'description' => 'Filter by activity type: call, email, whatsapp, meeting, note, status_change, task_update, payment, deployment.'
                ],
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Max records (default: 15, max: 30).'
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
        $query = Activity::with(['user', 'subject']);

        if (!empty($arguments['type'])) {
            $query->where('type', $arguments['type']);
        }

        $limit = min(max((int) ($arguments['limit'] ?? 15), 1), 30);
        $activities = $query->latest('created_at')->limit($limit)->get();

        $formatted = $activities->map(function (Activity $a) {
            $subjectName = null;
            if ($a->subject) {
                $subjectName = $a->subject->name ?? $a->subject->company_name ?? $a->subject->title ?? null;
            }

            return [
                'id'           => (string) $a->id,
                'user'         => $a->user?->name ?? 'System',
                'type'         => $a->type,
                'description'  => $a->description,
                'entity'       => class_basename($a->subject_type ?? ''),
                'entity_name'  => $subjectName,
                'time_ago'     => $a->created_at?->diffForHumans(),
                'created_at'   => $a->created_at?->toIso8601String(),
            ];
        })->toArray();

        return [
            'status'     => 'success',
            'count'      => count($formatted),
            'activities' => $formatted,
        ];
    }
}
