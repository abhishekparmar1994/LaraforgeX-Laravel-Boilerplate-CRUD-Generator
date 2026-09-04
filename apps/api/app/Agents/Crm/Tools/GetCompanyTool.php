<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Company;
use Illuminate\Support\Str;

/**
 * Retrieves full details for a single company profile.
 */
class GetCompanyTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'get_company';
    }

    public function getDescription(): string
    {
        return 'Retrieve full details for a company by UUID or company name.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => [
                    'type' => 'string',
                    'description' => 'The company UUID or name.'
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
        $q = trim((string) ($arguments['query'] ?? ''));
        if (empty($q)) {
            return ['found' => false, 'message' => 'No search query provided.'];
        }

        $query = Company::with(['contacts', 'projects', 'websites']);

        if (Str::isUuid($q)) {
            $company = $query->where('id', $q)->first();
        } else {
            $company = (clone $query)->where('name', $q)->first()
                ?? $query->where('name', 'like', "%{$q}%")->first();
        }

        if (!$company) {
            return ['found' => false, 'message' => "Company '{$q}' not found."];
        }

        return [
            'found'   => true,
            'company' => [
                'id'             => (string) $company->id,
                'name'           => $company->name,
                'industry'       => $company->industry,
                'type'           => $company->company_type,
                'status'         => $company->status,
                'email'          => $company->email,
                'phone'          => $company->phone,
                'website'        => $company->website,
                'address'        => $company->address,
                'city'           => $company->city,
                'country'        => $company->country,
                'contacts_count' => $company->contacts->count(),
                'projects_count' => $company->projects->count(),
                'websites_count' => $company->websites->count(),
                'view_url'       => "/admin/companies/{$company->id}",
            ],
        ];
    }
}
