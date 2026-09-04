<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Contact;

/**
 * Searches and filters individual CRM contacts.
 */
class SearchContactsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'search_contacts';
    }

    public function getDescription(): string
    {
        return 'Search individual contacts by name, email, phone number, company, or preferred contact channel.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'search' => [
                    'type' => 'string',
                    'description' => 'Text query matching first name, last name, email, or phone.'
                ],
                'company_id' => [
                    'type' => 'string',
                    'description' => 'Optional UUID of parent company.'
                ],
                'preferred_method' => [
                    'type' => 'string',
                    'description' => 'Filter by channel: Email, Phone, WhatsApp, Meeting.'
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
        $query = Contact::with('company');

        if (!empty($arguments['search'])) {
            $s = trim($arguments['search']);
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if (!empty($arguments['company_id'])) {
            $query->where('company_id', $arguments['company_id']);
        }

        if (!empty($arguments['preferred_method'])) {
            $query->where('preferred_contact_method', $arguments['preferred_method']);
        }

        $totalCount = (clone $query)->count();
        $limit = min(max((int) ($arguments['limit'] ?? 15), 1), 25);
        $records = $query->latest('created_at')->limit($limit)->get();

        $formatted = $records->map(fn (Contact $c) => [
            'id'               => (string) $c->id,
            'name'             => $c->name,
            'job_title'        => $c->job_title ?? 'N/A',
            'company'          => $c->company?->name ?? 'None',
            'email'            => $c->email,
            'phone'            => $c->phone,
            'preferred_method' => $c->preferred_contact_method,
            'view_url'         => "/admin/contacts/{$c->id}",
        ])->toArray();

        return [
            'count'    => $totalCount,
            'showing'  => count($formatted),
            'records'  => $formatted,
            'view_all' => '/admin/contacts',
        ];
    }
}
