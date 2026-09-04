<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\FollowUp;
use Carbon\Carbon;

/**
 * Searches and filters scheduled communications and follow-ups.
 */
class SearchFollowupsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'search_followups';
    }

    public function getDescription(): string
    {
        return 'Search follow-up communications (calls, demos, meetings) by timeframe (today, overdue, upcoming), status, or type.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'timeframe' => [
                    'type' => 'string',
                    'description' => 'Filter by timeframe: "today", "overdue", "upcoming", or "all". Default: "upcoming".',
                    'enum' => ['today', 'overdue', 'upcoming', 'all'],
                ],
                'follow_up_type' => [
                    'type' => 'string',
                    'description' => 'Type: phone_call, email, whatsapp, meeting, demo, proposal_followup, payment_followup.'
                ],
                'status' => [
                    'type' => 'string',
                    'description' => 'Status: scheduled, completed, rescheduled, cancelled.'
                ],
                'assigned_to' => [
                    'type' => 'string',
                    'description' => 'User UUID assigned to this follow-up.'
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
        $query = FollowUp::with(['lead', 'client', 'contact', 'assignedTo']);

        $timeframe = $arguments['timeframe'] ?? 'upcoming';
        $this->applyTimeframeFilter($query, $timeframe);

        if (!empty($arguments['follow_up_type'])) {
            $query->where('follow_up_type', $arguments['follow_up_type']);
        }

        if (!empty($arguments['status'])) {
            $query->where('status', strtolower($arguments['status']));
        }

        if (!empty($arguments['assigned_to'])) {
            $query->where('assigned_to_user_id', $arguments['assigned_to']);
        }

        $totalCount = (clone $query)->count();
        $limit = min(max((int) ($arguments['limit'] ?? 15), 1), 25);
        $records = $query->orderBy('scheduled_at')->limit($limit)->get();

        $formatted = $records->map(fn (FollowUp $f) => [
            'id'             => (string) $f->id,
            'type'           => $f->follow_up_type,
            'status'         => $f->status,
            'scheduled_at'   => $f->scheduled_at?->toIso8601String(),
            'target'         => $f->lead?->company_name ?? $f->client?->name ?? 'None',
            'contact_name'   => $f->contact ? ($f->contact->first_name . ' ' . $f->contact->last_name) : ($f->lead?->contact_person ?? 'N/A'),
            'assigned_to'    => $f->assignedTo?->name ?? 'Unassigned',
            'is_today'       => $f->is_today,
            'is_overdue'     => $f->is_overdue,
            'notes'          => $f->notes,
        ])->toArray();

        return [
            'status'        => 'success',
            'timeframe'     => $timeframe,
            'total_matches' => $totalCount,
            'count'         => count($formatted),
            'followups'     => $formatted,
        ];
    }

    /**
     * Applies timeframe constraints to the query.
     */
    private function applyTimeframeFilter($query, string $timeframe): void
    {
        if ($timeframe === 'today') {
            $query->whereDate('scheduled_at', Carbon::today());
        } elseif ($timeframe === 'overdue') {
            $query->where('status', 'scheduled')
                  ->where('scheduled_at', '<', Carbon::today());
        } elseif ($timeframe === 'upcoming') {
            $query->where('status', 'scheduled')
                  ->where('scheduled_at', '>=', Carbon::now());
        }
    }
}
