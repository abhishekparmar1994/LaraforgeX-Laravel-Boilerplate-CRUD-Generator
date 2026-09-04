<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Lead;
use Illuminate\Support\Str;

/**
 * Retrieves full details for a single CRM lead record.
 */
class GetLeadTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'get_lead';
    }

    public function getDescription(): string
    {
        return 'Retrieves detailed information for a specific lead by UUID or company name.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => [
                    'type' => 'string',
                    'description' => 'The lead UUID or company name to search for.'
                ],
            ],
            'required' => ['query'],
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
        $queryText = trim((string) ($arguments['query'] ?? ''));

        if (empty($queryText)) {
            return ['found' => false, 'message' => 'No search query provided.'];
        }

        $leadQuery = Lead::with(['assignedDeveloper', 'followUps', 'leadNotes']);

        if (Str::isUuid($queryText)) {
            $lead = $leadQuery->where('id', $queryText)->first();
        } else {
            $lead = (clone $leadQuery)->where('company_name', $queryText)->first()
                ?? $leadQuery->where('company_name', 'like', "%{$queryText}%")->first();
        }

        if (!$lead) {
            return [
                'found'   => false,
                'message' => "Lead matching '{$queryText}' was not found in the CRM.",
            ];
        }

        return [
            'found' => true,
            'lead'  => [
                'id'                 => (string) $lead->id,
                'company_name'       => $lead->company_name,
                'contact_person'     => $lead->contact_person,
                'email'              => $lead->email,
                'phone'              => $lead->phone,
                'whatsapp'           => $lead->whatsapp,
                'website'            => $lead->website,
                'status'             => $lead->status,
                'priority'           => $lead->priority,
                'industry'           => $lead->industry,
                'estimated_budget'   => (float) $lead->estimated_budget,
                'assigned_developer' => $lead->assignedDeveloper?->name ?? 'Unassigned',
                'requirement'        => $lead->requirement,
                'last_contact_date'  => $lead->last_contact_date?->format('Y-m-d'),
                'next_followup_date' => $lead->next_followup_date?->format('Y-m-d'),
                'days_in_pipeline'   => $lead->days_in_pipeline,
                'notes_count'        => $lead->leadNotes->count(),
                'followups_count'    => $lead->followUps->count(),
                'view_url'           => "/admin/leads/{$lead->id}",
            ],
        ];
    }
}
