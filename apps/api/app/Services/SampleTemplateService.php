<?php

declare(strict_types=1);

/**
 * Service: SampleTemplateService
 * Purpose: Generates standardized CSV/Excel sample templates with realistic Solar EPC data for bulk imports.
 * Dependencies: None (built-in PHP streams and formatting)
 */

namespace App\Services;

class SampleTemplateService
{
    /**
     * Generate sample CSV for Solar EPC Leads import with UTF-8 BOM.
     *
     * Purpose: Provides a downloadable, pre-structured spreadsheet template matching the solar CRM schema.
     * Parameters: None
     * Return: string (UTF-8 encoded CSV content with BOM)
     * Errors: None
     * Side Effects: None (pure string generation in memory)
     *
     * @return string
     */
    public function getSolarLeadsSampleCsv(): string
    {
        $headers = [
            'Customer / Company Name',
            'Contact Person',
            'Phone',
            'WhatsApp',
            'Email',
            'Consumer Number',
            'DISCOM',
            'Sanctioned Load (kW)',
            'Monthly Electricity Bill (INR)',
            'Proposed Solar Capacity (kW)',
            'Product Group',
            'Customer Group',
            'Lead Potential',
            'Lead Type',
            'Society / Apartment',
            'Area / Locality',
            'City',
            'State',
            'Pincode',
            'Notes / Requirement',
        ];

        $rows = $this->getSolarSampleData();

        return $this->buildCsv($headers, $rows);
    }

    /**
     * Provide realistic solar EPC inquiry data rows for the sample file.
     *
     * @return array<int, array<int, string>>
     */
    private function getSolarSampleData(): array
    {
        return [
            [
                'Sharma Residence',
                'Rajesh Sharma',
                '9825012345',
                '9825012345',
                'rajesh.sharma@example.com',
                '05102/12345/8',
                'MGVCL',
                '4.5',
                '3800',
                '3.3',
                'Residential Rooftop (1-10 kW)',
                'Individual Homeowner',
                'High',
                'Direct Inquiry',
                'Shreeji Heights',
                'Alkapuri',
                'Vadodara',
                'Gujarat',
                '390007',
                'Interested in PM Surya Ghar Muft Bijli Yojana subsidy.'
            ],
            [
                'Apex Engineering Works',
                'Kirit Patel',
                '9879054321',
                '9879054321',
                'kirit@apexengineering.in',
                '05101/98765/2',
                'DGVCL',
                '25.0',
                '28000',
                '20.0',
                'Commercial & Industrial (C&I)',
                'Commercial Entity',
                'High',
                'Site Survey Requested',
                'Apex Industrial Park',
                'Makarpura GIDC',
                'Vadodara',
                'Gujarat',
                '390010',
                'Factory rooftop shed with approx 2,500 sq ft shadow-free area.'
            ],
            [
                'Gokul Residency CHS Ltd',
                'Sanjay Desai',
                '9426011223',
                '9426011223',
                'secretary@gokulresidency.org',
                '05103/44556/1',
                'UGVCL',
                '15.0',
                '14500',
                '10.0',
                'Society Common Meter',
                'Residential Society (CHS/RWA)',
                'Medium',
                'Referral',
                'Gokul Residency Phase 2',
                'Vasna Road',
                'Vadodara',
                'Gujarat',
                '390015',
                'Common meters for lifts, water pump, and compound solar lighting.'
            ],
        ];
    }

    /**
     * Build CSV string with UTF-8 BOM for Microsoft Excel compatibility.
     *
     * @param array<int, string> $headers
     * @param array<int, array<int, string>> $rows
     * @return string
     */
    private function buildCsv(array $headers, array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        if ($handle === false) {
            return '';
        }

        // Write UTF-8 BOM so Excel opens with proper encoding
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, $headers);

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv !== false ? $csv : '';
    }
}
