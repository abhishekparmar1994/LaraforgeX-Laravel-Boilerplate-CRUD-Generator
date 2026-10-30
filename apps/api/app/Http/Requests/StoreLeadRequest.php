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
     */
    protected function prepareForValidation(): void
    {
        $contactData = $this->synthesizeContactAndCompany();
        $statusData = $this->resolvePriorityAndStatus();
        $addressData = $this->synthesizeAddressDetails();

        $consumerNum = $this->consumer_number ?? $this->consumer_no ?? $this->consumer_account_no;
        $assignedTo = $this->assigned_to_user_id ?: ($this->assigned_consultant_id ?: null);
        $leadList = $this->lead_list ?? $this->list ?? 'solar sales';
        $budget = $this->deal_size ?? $this->estimated_budget ?? 0;

        $this->merge(array_merge($contactData, $statusData, $addressData, [
            'lead_source' => $this->lead_source ?? 'PM Surya Ghar Portal',
            'estimated_budget' => $budget,
            'consumer_number' => $consumerNum,
            'discom' => $this->discom,
            'sanctioned_load' => $this->sanctioned_load,
            'monthly_bill' => $this->monthly_bill,
            'solar_capacity_kw' => $this->solar_capacity_kw ?? $this->sanctioned_load,
            'product_group' => $this->product_group,
            'product_group_id' => $this->product_group_id,
            'customer_group' => $this->customer_group,
            'customer_group_id' => $this->customer_group_id,
            'lead_potential' => $this->lead_potential,
            'lead_type' => $this->lead_type,
            'roof_type' => $this->roof_type,
            'follow_up_type' => $this->follow_up_type,
            'followup_notes' => $this->followup_notes,
            'society_name' => $this->society_name,
            'approval_status' => $this->approval_status,
            'franchise' => $this->franchise,
            'lead_list' => $leadList,
            'tags' => $this->tags ?? $this->lead_tags,
            'assigned_to_user_id' => $assignedTo,
        ]));
    }

    /**
     * Synthesize full contact person name and fallback company name.
     */
    private function synthesizeContactAndCompany(): array
    {
        $contactPerson = $this->contact_person;
        if (empty($contactPerson) && ($this->filled('first_name') || $this->filled('last_name'))) {
            $contactPerson = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        }

        $companyName = $this->company_name;
        if (empty($companyName)) {
            $companyName = !empty($contactPerson) ? $contactPerson . ' Residence' : 'Solar Project Inquiry';
        }

        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'alternate_phone' => $this->alternate_phone,
            'contact_person' => $contactPerson ?: 'Valued Customer',
            'company_name' => $companyName,
            'salutation' => $this->salutation ?? 'Mr.',
            'designation' => $this->designation,
        ];
    }

    /**
     * Resolve lead priority, pipeline stage, and system status.
     */
    private function resolvePriorityAndStatus(): array
    {
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

        return [
            'priority' => $priority,
            'status' => $status,
            'stage' => $this->stage ?? 'New Lead',
        ];
    }

    /**
     * Synthesize location, address lines, and full postal address.
     */
    private function synthesizeAddressDetails(): array
    {
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
                $this->country ?? 'India',
            ]);
            $fullAddress = implode(', ', $parts);
        }

        return [
            'location' => $location,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'pincode' => $this->pincode,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country ?? 'India',
            'full_address' => $fullAddress,
        ];
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'salutation' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'consumer_number' => 'nullable|string|max:100',
            'discom' => 'nullable|string|max:100',
            'sanctioned_load' => 'nullable|numeric|min:0',
            'monthly_bill' => 'nullable|numeric|min:0',
            'solar_capacity_kw' => 'nullable|numeric|min:0',
            'subsidy_amount' => 'nullable|numeric|min:0',
            'product_group' => 'nullable|string|max:100',
            'product_group_id' => 'nullable|exists:crm_solar_product_groups,id',
            'customer_group' => 'nullable|string|max:100',
            'customer_group_id' => 'nullable|exists:crm_solar_customer_groups,id',
            'lead_potential' => 'nullable|string|max:50',
            'lead_type' => 'nullable|string|max:100',
            'roof_type' => 'nullable|string|max:100',
            'follow_up_type' => 'nullable|string|max:100',
            'followup_notes' => 'nullable|string',
            'society_name' => 'nullable|string|max:255',
            'approval_status' => 'nullable|string|max:100',
            'franchise' => 'nullable|string|max:100',
            'lead_list' => 'nullable|string|max:100',
            'stage' => 'nullable|string|max:50',
            'tags' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'lead_source' => 'required|string|max:100',
            'location' => 'nullable|string|max:150',
            'area_locality' => 'nullable|string|max:150',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'pincode' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'requirement' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiation,won,lost',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'next_followup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required to create a solar lead.',
            'first_name.max' => 'First name cannot exceed 100 characters.',
            'email.email' => 'Please enter a valid email address.',
            'deal_size.numeric' => 'Deal size must be a valid numeric amount.',
            'monthly_bill.numeric' => 'Monthly bill amount must be a number.',
            'sanctioned_load.numeric' => 'Sanctioned load must be a valid number.',
            'pincode.max' => 'Pincode cannot exceed 20 characters.',
            'website.url' => 'Please enter a valid URL starting with http:// or https://',
        ];
    }
}
