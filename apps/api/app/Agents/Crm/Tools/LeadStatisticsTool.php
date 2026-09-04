<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

/**
 * Computes high-level CRM lead aggregates directly from the database using SQL aggregations.
 */
class LeadStatisticsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'lead_statistics';
    }

    public function getDescription(): string
    {
        return 'Calculates overall CRM lead counts, breakdown by stage (new, contacted, qualified, proposal_sent, negotiation, won, lost), conversion rate, pipeline budget sum, and leads uncontacted for over 7 days.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'industry' => [
                    'type' => 'string',
                    'description' => 'Optional industry filter to scope statistics.'
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
        $query = Lead::query();

        if (!empty($arguments['industry'])) {
            $query->where('industry', $arguments['industry']);
        }

        $totalLeads = (clone $query)->count();

        // Status counts
        $statusCounts = (clone $query)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Priority counts
        $priorityCounts = (clone $query)
            ->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();

        // Total Pipeline Budget
        $totalBudget = (float) ((clone $query)->sum('estimated_budget') ?? 0);

        // Conversion Rate: won / total
        $wonCount = $statusCounts['won'] ?? 0;
        $conversionRate = $totalLeads > 0 ? round(($wonCount / $totalLeads) * 100, 2) . '%' : '0%';

        // Uncontacted for > 7 days
        $sevenDaysAgo = now()->subDays(7)->toDateString();
        $uncontactedCount = (clone $query)
            ->where(function ($q) use ($sevenDaysAgo) {
                $q->whereNull('last_contact_date')
                  ->orWhere('last_contact_date', '<=', $sevenDaysAgo);
            })
            ->whereNotIn('status', ['won', 'lost'])
            ->count();

        return [
            'total_leads'              => $totalLeads,
            'by_status'                => $statusCounts,
            'by_priority'              => $priorityCounts,
            'total_estimated_budget'   => $totalBudget,
            'conversion_rate'          => $conversionRate,
            'uncontacted_over_7_days'  => $uncontactedCount,
        ];
    }
}
