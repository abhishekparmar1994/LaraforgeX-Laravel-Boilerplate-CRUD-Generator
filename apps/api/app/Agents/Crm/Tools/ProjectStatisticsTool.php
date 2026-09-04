<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Project;
use Carbon\Carbon;

/**
 * Computes portfolio metrics and status distributions for projects.
 */
class ProjectStatisticsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'project_statistics';
    }

    public function getDescription(): string
    {
        return 'Get high-level project portfolio statistics: active project counts, total portfolio budget, overdue counts, and average completion rate.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'client_id' => [
                    'type' => 'string',
                    'description' => 'Optional client UUID to scope metrics.'
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
        $query = Project::query();

        if (!empty($arguments['client_id'])) {
            $query->where('client_id', $arguments['client_id']);
        }

        $total = (clone $query)->count();
        $completed = (clone $query)->whereIn('status', ['completed', 'launch'])->count();
        $onHold = (clone $query)->where('status', 'on_hold')->count();
        $active = (clone $query)->whereNotIn('status', ['completed', 'launch', 'on_hold'])->count();

        $overdue = (clone $query)->whereNotIn('status', ['completed', 'launch'])
            ->whereNotNull('deadline')
            ->where('deadline', '<', Carbon::today())
            ->count();

        $totalBudget = (float) (clone $query)->sum('budget');
        $avgProgress = $total > 0 ? round((float) (clone $query)->avg('progress'), 1) : 0.0;

        return [
            'status' => 'success',
            'total_projects' => $total,
            'active_projects' => $active,
            'completed_projects' => $completed,
            'on_hold_projects' => $onHold,
            'overdue_projects' => $overdue,
            'total_portfolio_budget' => round($totalBudget, 2),
            'average_progress_percentage' => $avgProgress,
        ];
    }
}
