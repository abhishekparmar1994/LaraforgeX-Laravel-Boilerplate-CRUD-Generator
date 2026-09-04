<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Lead;

/**
 * Calculates detailed sales pipeline analytics and conversion metrics.
 */
class PipelineStatisticsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'pipeline_statistics';
    }

    public function getDescription(): string
    {
        return 'Get deep CRM sales pipeline statistics, stage breakdowns, win/loss rates, and total deal values.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'assigned_to' => [
                    'type' => 'string',
                    'description' => 'User UUID to filter pipeline by sales rep.'
                ],
                'industry' => [
                    'type' => 'string',
                    'description' => 'Filter pipeline by industry sector.'
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
        $baseQuery = Lead::query();

        if (!empty($arguments['assigned_to'])) {
            $baseQuery->where('assigned_to_user_id', $arguments['assigned_to']);
        }

        if (!empty($arguments['industry'])) {
            $baseQuery->where('industry', $arguments['industry']);
        }

        $stages = ['new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won', 'lost'];
        $stageMetrics = [];
        $totalPipelineValue = 0.0;

        foreach ($stages as $stage) {
            $count = (clone $baseQuery)->where('status', $stage)->count();
            $val = (float) (clone $baseQuery)->where('status', $stage)->sum('estimated_budget');
            $stageMetrics[$stage] = [
                'count' => $count,
                'total_value' => round($val, 2),
            ];
            if (!in_array($stage, ['won', 'lost'], true)) {
                $totalPipelineValue += $val;
            }
        }

        $totalLeads = (clone $baseQuery)->count();
        $wonCount = $stageMetrics['won']['count'];
        $lostCount = $stageMetrics['lost']['count'];
        $decidedCount = $wonCount + $lostCount;

        $winRate = $decidedCount > 0 ? round(($wonCount / $decidedCount) * 100, 1) : 0.0;
        $avgDealSize = $wonCount > 0 ? round($stageMetrics['won']['total_value'] / $wonCount, 2) : 0.0;

        return [
            'status' => 'success',
            'total_leads' => $totalLeads,
            'active_pipeline_value' => round($totalPipelineValue, 2),
            'won_deals_value' => $stageMetrics['won']['total_value'],
            'lost_deals_value' => $stageMetrics['lost']['total_value'],
            'win_rate_percentage' => $winRate,
            'average_won_deal_size' => $avgDealSize,
            'stages' => $stageMetrics,
        ];
    }
}
