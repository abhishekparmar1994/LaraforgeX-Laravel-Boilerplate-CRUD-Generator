<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreLeadRequest
 * FormRequest gatekeeper for validating Solar EPC & Rooftop lead creation payloads.
 */
class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * Auto-synthesizes company_name, contact_person, and maps solar fields.
     */
    protected function prepareForValidation(): void
    {
        $contactPerson = $this->contact_person;
        if (empty($contactPerson) && ($this->filled('first_name') || $this->filled('last_name'))) {
            $contactPerson = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        }

        $companyName = $this->company_name;
        if (empty($companyName)) {
            $companyName = !empty($contactPerson) ? $contactPerson . ' Residence' : 'Solar Project Inquiry';
        }

        $priority = $this->priority ?? 'medium';
        if ($this->lead_potential === 'Very High') {
            $priority = 'urgent';
        } elseif ($this->lead_potential === 'High') {
            $priority = 'high';
        }

        $status = $this->status ?? 'new';
        $statusMap = [
            'Qualified' => 'qualified',
            'New Lead' => 'new',
            'In Process' => 'contacted',
            'Site Visited' => 'contacted',
            'Estimate Send' => 'proposal_sent',
            'Document' => 'negotiation',
            'Won' => 'won',
            'Lost' => 'lost',
            'Not Connect' => 'new',
            'On Hold' => 'new',
        ];
        if (isset($statusMap[$this->stage])) {
            $status = $statusMap[$this->stage];
        }

        $location = $this->location;
        if (empty($location) && $this->filled('city')) {
            $location = trim(($this->area_locality ?? '') . ', ' . $this->city);
        }

        $fullAddress = $this->full_address;
        if (empty($fullAddress) && ($this->filled('address_line_1') || $this->filled('pincode'))) {
            $parts = array_filter([
                $this->address_line_1,
                $this->address_line_2,
                $this->area_locality,
                $this->city,
                $this->state,
                $this->pincode ? 'PIN: ' . $this->pincode : null,
                'India'
            ]);
            $fullAddress = implode(', ', $parts);
        }

        $this->merge([
            'company_name' => $companyName,
            'contact_person' => $contactPerson ?: 'Valued Customer',
            'priority' => $priority,
            'status' => $status,
            'location' => $location,
            'full_address' => $fullAddress,
            'lead_source' => $this->lead_source ?? 'PM Surya Ghar Portal',
            'estimated_budget' => $this->deal_size ?? $this->estimated_budget ?? 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'lead_source' => 'required|string|max:100',
            'location' => 'nullable|string|max:150',
            'area_locality' => 'nullable|string|max:150',
            'full_address' => 'nullable|string',
            'requirement' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiation,won,lost',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'next_followup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}
