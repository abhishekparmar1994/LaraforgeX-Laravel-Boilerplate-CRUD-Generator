<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Client;

/**
 * Retrieves comprehensive details for a specific client account.
 */
class GetClientTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'get_client';
    }

    public function getDescription(): string
    {
        return 'Get full details for a client account by ID, client code, or name, including revenue, pending balance, and project count.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'client_id' => [
                    'type' => 'string',
                    'description' => 'UUID of the client.'
                ],
                'client_code' => [
                    'type' => 'string',
                    'description' => 'Client code (e.g. CLI-2026-001).'
                ],
                'name' => [
                    'type' => 'string',
                    'description' => 'Full or partial name of the client.'
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
        $client = $this->resolveClient($arguments);

        if (!$client) {
            return [
                'status' => 'not_found',
                'message' => 'Client could not be found with the provided criteria.',
            ];
        }

        return [
            'status' => 'success',
            'client' => $this->formatClientDetails($client),
        ];
    }

    /**
     * Resolves a client by id, code, or name.
     */
    private function resolveClient(array $arguments): ?Client
    {
        $query = Client::with(['company', 'primaryContact', 'projects', 'websites']);

        if (!empty($arguments['client_id'])) {
            return $query->where('id', $arguments['client_id'])->first();
        }

        if (!empty($arguments['client_code'])) {
            return $query->where('client_code', $arguments['client_code'])->first();
        }

        if (!empty($arguments['name'])) {
            return $query->where('name', 'like', '%' . trim($arguments['name']) . '%')->first();
        }

        return null;
    }

    /**
     * Formats client details for agent context.
     */
    private function formatClientDetails(Client $client): array
    {
        return [
            'id'              => (string) $client->id,
            'client_code'     => $client->client_code,
            'name'            => $client->name,
            'status'          => $client->status,
            'industry'        => $client->industry,
            'email'           => $client->email,
            'phone'           => $client->phone,
            'company'         => $client->company?->name ?? 'None',
            'primary_contact' => $client->primaryContact ? [
                'name'  => $client->primaryContact->first_name . ' ' . $client->primaryContact->last_name,
                'email' => $client->primaryContact->email,
                'phone' => $client->primaryContact->phone,
            ] : null,
            'client_since'    => $client->client_since?->toDateString(),
            'total_revenue'   => round((float) $client->total_revenue, 2),
            'pending_payment' => round((float) $client->pending_payment, 2),
            'projects_count'  => $client->projects->count(),
            'websites_count'  => $client->websites->count(),
            'view_url'        => "/admin/clients/{$client->id}",
        ];
    }
}
