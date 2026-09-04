<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Client;

/**
 * Ranks clients by total collected revenue or pending balances.
 */
class TopClientsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'top_clients';
    }

    public function getDescription(): string
    {
        return 'Rank clients by revenue or pending balances. Useful for answering questions like "Who are our top clients?" or "Which clients owe the most money?".';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'by' => [
                    'type' => 'string',
                    'description' => 'Metric to rank by: "revenue" (default, paid invoices) or "pending" (unpaid balance).',
                    'enum' => ['revenue', 'pending'],
                ],
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Number of clients to return (default: 5, max: 20).'
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
        $by = ($arguments['by'] ?? 'revenue') === 'pending' ? 'pending' : 'revenue';
        $limit = min(max((int) ($arguments['limit'] ?? 5), 1), 20);

        $query = Client::with('company');

        if ($by === 'revenue') {
            $query->withSum(['payments as metric_val' => function ($q) {
                $q->where('status', 'paid');
            }], 'amount');
        } else {
            $query->withSum(['payments as metric_val' => function ($q) {
                $q->whereIn('status', ['pending', 'partial', 'overdue']);
            }], 'amount');
        }

        $clients = $query->orderByDesc('metric_val')->limit($limit)->get();

        $formatted = $clients->map(function (Client $client) use ($by) {
            return [
                'id'              => (string) $client->id,
                'client_code'     => $client->client_code,
                'name'            => $client->name,
                'company'         => $client->company?->name ?? 'None',
                'industry'        => $client->industry,
                'status'          => $client->status,
                'ranked_metric'   => $by,
                'metric_amount'   => round((float) ($client->metric_val ?? 0), 2),
                'total_revenue'   => round((float) $client->total_revenue, 2),
                'pending_payment' => round((float) $client->pending_payment, 2),
                'view_url'        => "/admin/clients/{$client->id}",
            ];
        })->toArray();

        return [
            'status'        => 'success',
            'ranked_by'     => $by,
            'count'         => count($formatted),
            'top_clients'   => $formatted,
        ];
    }
}
