<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Company;

/**
 * Searches and filters companies and organizations in the CRM.
 */
class SearchCompaniesTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'search_companies';
    }

    public function getDescription(): string
    {
        return 'Filter companies by industry, country, status (active/inactive), company type (Client, Partner, Vendor), or name keyword.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'search' => [
                    'type' => 'string',
                    'description' => 'Text search matching company name, city, email, or phone.'
                ],
                'industry' => [
                    'type' => 'string',
                    'description' => 'Industry sector filter.'
                ],
                'company_type' => [
                    'type' => 'string',
                    'description' => 'Client, Partner, Vendor.'
                ],
                'status' => [
                    'type' => 'string',
                    'description' => 'active or inactive.'
                ],
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Max records to return (default: 15, max: 25).'
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
        $query = Company::withCount(['contacts', 'projects']);

        if (!empty($arguments['search'])) {
            $s = trim($arguments['search']);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if (!empty($arguments['industry'])) {
            $query->where('industry', $arguments['industry']);
        }

        if (!empty($arguments['company_type'])) {
            $query->where('company_type', $arguments['company_type']);
        }

        if (!empty($arguments['status'])) {
            $query->where('status', strtolower($arguments['status']));
        }

        $totalCount = (clone $query)->count();
        $limit = min(max((int) ($arguments['limit'] ?? 15), 1), 25);
        $records = $query->latest('created_at')->limit($limit)->get();

        $formatted = $records->map(fn (Company $c) => [
            'id'             => (string) $c->id,
            'name'           => $c->name,
            'industry'       => $c->industry ?? 'N/A',
            'city'           => $c->city ?? 'N/A',
            'country'        => $c->country ?? 'N/A',
            'company_type'   => $c->company_type,
            'status'         => $c->status,
            'contacts_count' => $c->contacts_count,
            'projects_count' => $c->projects_count,
            'view_url'       => "/admin/companies/{$c->id}",
        ])->toArray();

        return [
            'count'    => $totalCount,
            'showing'  => count($formatted),
            'records'  => $formatted,
            'view_all' => '/admin/companies',
        ];
    }
}
