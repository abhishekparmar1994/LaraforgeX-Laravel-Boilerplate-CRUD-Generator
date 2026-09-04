<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Contact;
use Illuminate\Support\Str;

/**
 * Retrieves full details for a single contact record.
 */
class GetContactTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'get_contact';
    }

    public function getDescription(): string
    {
        return 'Retrieve full details for a contact by UUID, email, or full name.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => [
                    'type' => 'string',
                    'description' => 'The contact UUID, full name, or email address.'
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

        $query = Contact::with(['company', 'followUps', 'notes']);

        if (Str::isUuid($q)) {
            $contact = $query->where('id', $q)->first();
        } else {
            $contact = (clone $query)->where('email', $q)->first()
                ?? (clone $query)->where('first_name', 'like', "%{$q}%")
                ?? $query->where('last_name', 'like', "%{$q}%")->first();
        }

        if (!$contact) {
            return ['found' => false, 'message' => "Contact '{$q}' not found."];
        }

        return [
            'found'   => true,
            'contact' => [
                'id'               => (string) $contact->id,
                'name'             => $contact->name,
                'job_title'        => $contact->job_title,
                'company'          => $contact->company?->name ?? 'None',
                'email'            => $contact->email,
                'phone'            => $contact->phone,
                'whatsapp'         => $contact->whatsapp,
                'linkedin'         => $contact->linkedin,
                'preferred_method' => $contact->preferred_contact_method,
                'notes'            => $contact->notes,
                'followups_count'  => $contact->followUps->count(),
                'view_url'         => "/admin/contacts/{$contact->id}",
            ],
        ];
    }
}
