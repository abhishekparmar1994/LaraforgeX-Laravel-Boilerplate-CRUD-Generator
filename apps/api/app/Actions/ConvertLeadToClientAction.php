<?php

namespace App\Actions;

use App\Models\Lead;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Client;
use App\Models\Project;
use App\Models\Website;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Action to convert a Won Lead into a Company, Primary Contact, Client, and optional starter Website/Project.
 */
class ConvertLeadToClientAction
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Execute lead conversion.
     *
     * @param Lead $lead
     * @param array $options
     * @return Client
     */
    public function execute(Lead $lead, array $options = []): Client
    {
        return DB::transaction(function () use ($lead, $options) {
            // 1. Create or Find Company
            $company = Company::create([
                'name' => $lead->company_name,
                'industry' => $lead->industry,
                'website' => $lead->website,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'status' => 'active',
                'notes' => "Converted from Lead #{$lead->id}. Initial requirement: {$lead->requirement}",
            ]);

            // 2. Parse Contact Person into First and Last Name
            $nameParts = explode(' ', trim($lead->contact_person), 2);
            $firstName = $nameParts[0] ?? $lead->contact_person;
            $lastName = $nameParts[1] ?? '';

            // 3. Create Primary Contact
            $contact = Contact::create([
                'company_id' => $company->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'job_title' => $options['job_title'] ?? 'Decision Maker',
                'email' => $lead->email,
                'phone' => $lead->phone,
                'whatsapp' => $lead->whatsapp,
                'notes' => 'Primary contact created during lead conversion.',
            ]);

            // 4. Generate unique client code (e.g., CLI-1001)
            $count = Client::count() + 1001;
            $clientCode = 'CLI-' . $count;

            // 5. Create Client record
            $client = Client::create([
                'company_id' => $company->id,
                'primary_contact_id' => $contact->id,
                'lead_id' => $lead->id,
                'client_code' => $clientCode,
                'name' => $lead->company_name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'whatsapp' => $lead->whatsapp,
                'industry' => $lead->industry,
                'client_since' => now(),
                'status' => 'active',
                'notes' => $lead->notes,
            ]);

            // 6. Optional: Create initial Solar EPC Project
            if (!empty($options['create_project'])) {
                $projectName = $options['project_name'] ?? ($lead->company_name . ' Solar Rooftop System');
                $project = Project::create([
                    'client_id' => $client->id,
                    'company_id' => $company->id,
                    'name' => $projectName,
                    'project_type' => $options['project_type'] ?? 'Residential Rooftop (1-10 kW)',
                    'description' => $lead->requirement ?? 'Initial solar EPC scope defined during lead qualification.',
                    'start_date' => now(),
                    'deadline' => now()->addMonths(2),
                    'budget' => $lead->estimated_budget,
                    'status' => 'planning',
                    'priority' => $lead->priority ?? 'medium',
                    'project_manager_id' => $lead->assigned_to_user_id,
                    'progress' => 5,
                ]);

                // Create initial Solar Plant record if requested
                if (!empty($options['create_website'])) {
                    Website::create([
                        'client_id' => $client->id,
                        'company_id' => $company->id,
                        'project_id' => $project->id,
                        'name' => $lead->company_name . ' Solar Plant',
                        'domain' => $lead->website ? parse_url($lead->website, PHP_URL_HOST) ?? $lead->website : null,
                        'status' => 'planning',
                        'technology' => 'Monocrystalline Bifacial / On-Grid Inverter',
                    ]);
                }
            }

            // 7. Update Lead status to Won
            $lead->update([
                'status' => 'won',
                'notes' => $lead->notes . "\n[System] Converted to Client ({$clientCode}) on " . now()->format('M d, Y H:i'),
            ]);

            // 8. Log activities
            $this->activityLogger->execute(
                $lead,
                'status_change',
                "Lead converted to Client #{$clientCode} and Company '{$company->name}'."
            );

            $this->activityLogger->execute(
                $client,
                'status_change',
                "Client account created from converted Lead #{$lead->id}."
            );

            return $client;
        });
    }
}
