<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Client;

/**
 * Searches and filters active client accounts.
 */
class SearchClientsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'search_clients';
    }

    public function getDescription(): string
    {
        return 'Search client accounts by name, client code, industry, or status (active/inactive).';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'search' => [
                    'type' => 'string',
                    'description' => 'Text search query for name, client code, email, or phone.'
                ],
                'industry' => [
                    'type' => 'string',
                    'description' => 'Industry sector filter.'
                ],
                'status' => [
                    'type' => 'string',
                    'description' => 'active or inactive.'
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
        $query = Client::with('company');

        if (!empty($arguments['search'])) {
            $s = trim($arguments['search']);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('client_code', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if (!empty($arguments['industry'])) {
            $query->where('industry', $arguments['industry']);
        }

        if (!empty($arguments['status'])) {
            $query->where('status', strtolower($arguments['status']));
        }

        $totalCount = (clone $query)->count();
        $limit = min(max((int) ($arguments['limit'] ?? 15), 1), 25);
        $records = $query->latest('created_at')->limit($limit)->get();

        $formatted = $records->map(fn (Client $cl) => [
            'id'          => (string) $cl->id,
            'client_code' => $cl->client_code,
            'name'        => $cl->name,
            'company'     => $cl->company?->name ?? 'None',
            'email'       => $cl->email,
            'phone'       => $cl->phone,
            'status'      => $cl->status,
            'industry'    => $cl->industry,
            'view_url'    => "/admin/clients/{$cl->id}",
        ])->toArray();

        return [
            'count'    => $totalCount,
            'showing'  => count($formatted),
            'records'  => $formatted,
            'view_all' => '/admin/clients',
        ];
    }
}
