<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Lead;

/**
 * Searches CRM leads with multi-field filtering, matching actual database records.
 */
class SearchLeadsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'search_leads';
    }

    public function getDescription(): string
    {
        return 'Searches and filters CRM leads by stage/status (new, contacted, qualified, proposal_sent, negotiation, won, lost), priority, search keyword, or inactivity duration.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'status' => [
                    'type' => 'string',
                    'description' => 'Lead pipeline status (new, contacted, qualified, proposal_sent, negotiation, won, lost).'
                ],
                'priority' => [
                    'type' => 'string',
                    'description' => 'Priority level (low, medium, high, urgent).'
                ],
                'search' => [
                    'type' => 'string',
                    'description' => 'Text search query matching company name, contact person, email, or phone.'
                ],
                'industry' => [
                    'type' => 'string',
                    'description' => 'Filter by company industry.'
                ],
                'uncontacted_days' => [
                    'type' => 'integer',
                    'description' => 'Find leads that have not been contacted for at least this number of days.'
                ],
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Maximum number of results to return (default: 15, max: 25).'
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
        $query = Lead::with('assignedDeveloper');

        if (!empty($arguments['status'])) {
            $query->where('status', strtolower($arguments['status']));
        }

        if (!empty($arguments['priority'])) {
            $query->where('priority', strtolower($arguments['priority']));
        }

        if (!empty($arguments['industry'])) {
            $query->where('industry', $arguments['industry']);
        }

        if (!empty($arguments['search'])) {
            $s = trim($arguments['search']);
            $query->where(function ($q) use ($s) {
                $q->where('company_name', 'like', "%{$s}%")
                  ->orWhere('contact_person', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('area_locality', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%")
                  ->orWhere('full_address', 'like', "%{$s}%");
            });
        }

        if (!empty($arguments['uncontacted_days'])) {
            $threshold = now()->subDays((int) $arguments['uncontacted_days'])->toDateString();
            $query->where(function ($q) use ($threshold) {
                $q->whereNull('last_contact_date')
                  ->orWhere('last_contact_date', '<=', $threshold);
            });
        }

        $totalCount = (clone $query)->count();
        $limit = min(max((int) ($arguments['limit'] ?? 15), 1), 25);
        $records = $query->latest('created_at')->limit($limit)->get();

        $formatted = $records->map(fn (Lead $lead) => [
            'id'                  => (string) $lead->id,
            'company_name'        => $lead->company_name,
            'contact_person'      => $lead->contact_person,
            'email'               => $lead->email,
            'phone'               => $lead->phone,
            'status'              => $lead->status,
            'priority'            => $lead->priority,
            'estimated_budget'    => (float) $lead->estimated_budget,
            'assigned_developer'  => $lead->assignedDeveloper?->name ?? 'Unassigned',
            'last_contact_date'   => $lead->last_contact_date?->format('Y-m-d'),
            'next_followup_date'  => $lead->next_followup_date?->format('Y-m-d'),
            'days_in_pipeline'    => $lead->days_in_pipeline,
            'view_url'            => "/admin/leads/{$lead->id}",
        ])->toArray();

        return [
            'count'    => $totalCount,
            'showing'  => count($formatted),
            'records'  => $formatted,
            'view_all' => '/admin/leads',
        ];
    }
}
