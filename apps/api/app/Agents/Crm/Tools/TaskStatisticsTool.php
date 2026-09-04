<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Task;
use Carbon\Carbon;

/**
 * Computes high-level task metrics and productivity statistics.
 */
class TaskStatisticsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'task_statistics';
    }

    public function getDescription(): string
    {
        return 'Get task distribution metrics, completion rates, overdue task counts, and priority breakdown.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'project_id' => [
                    'type' => 'string',
                    'description' => 'Filter task metrics by specific project UUID.'
                ],
                'assigned_to' => [
                    'type' => 'string',
                    'description' => 'Filter task metrics by assigned user UUID.'
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
        $query = Task::query();

        if (!empty($arguments['project_id'])) {
            $query->where('project_id', $arguments['project_id']);
        }

        if (!empty($arguments['assigned_to'])) {
            $query->where('assigned_to_user_id', $arguments['assigned_to']);
        }

        $totalTasks = (clone $query)->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $todo = (clone $query)->where('status', 'todo')->count();
        $review = (clone $query)->where('status', 'review')->count();
        $blocked = (clone $query)->where('status', 'blocked')->count();

        $overdue = (clone $query)->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->count();

        $urgent = (clone $query)->where('priority', 'urgent')->where('status', '!=', 'completed')->count();
        $high = (clone $query)->where('priority', 'high')->where('status', '!=', 'completed')->count();

        $completionRate = $totalTasks > 0 ? round(($completed / $totalTasks) * 100, 1) : 0.0;

        return [
            'status' => 'success',
            'total_tasks' => $totalTasks,
            'completed' => $completed,
            'completion_rate_percentage' => $completionRate,
            'in_progress' => $inProgress,
            'todo' => $todo,
            'review' => $review,
            'blocked' => $blocked,
            'overdue_count' => $overdue,
            'open_urgent_count' => $urgent,
            'open_high_count' => $high,
        ];
    }
}
