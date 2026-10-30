<?php

declare(strict_types=1);

/**
 * Service: LeadImportService
 * Purpose: Parses and imports business and solar EPC leads from Excel (.xlsx) and CSV files with duplicate checking.
 * Dependencies: App\Models\Lead, Illuminate\Http\UploadedFile, ZipArchive, SimpleXMLElement
 */

namespace App\Services;

use App\Models\Lead;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use ZipArchive;
use SimpleXMLElement;

class LeadImportService
{
    /**
     * Imports leads from an uploaded file (XLSX or CSV).
     *
     * @param UploadedFile $file
     * @param string $importedBy
     * @return array{imported: int, skipped: int, total: int, message: string}
     */
    public function import(UploadedFile $file, string $importedBy = 'Excel Import'): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $rows = match ($extension) {
            'csv', 'txt' => $this->parseCsv($file->getRealPath()),
            'xlsx' => $this->parseXlsx($file->getRealPath()),
            default => throw new \InvalidArgumentException('Unsupported file format. Please upload a .xlsx or .csv file.'),
        };

        $imported = 0;
        $skipped = 0;
        $total = count($rows);

        foreach ($rows as $row) {
            $data = $this->mapRowToLeadData($row, $importedBy);
            if ($data === null) {
                continue;
            }

            if ($this->isDuplicateLead($data['_last10'], $data['company_name'], $data['area_locality'])) {
                $skipped++;
                continue;
            }

            unset($data['_last10']);
            Lead::create($data);
            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'total' => $total,
            'message' => "Import completed successfully! {$imported} new leads added, {$skipped} duplicate leads skipped.",
        ];
    }

    /**
     * Check if a lead already exists by phone or company name + location.
     */
    private function isDuplicateLead(string $last10, string $companyName, ?string $locality): bool
    {
        if (!empty($last10)) {
            return Lead::where(function ($q) use ($last10, $companyName) {
                $q->where('phone', 'LIKE', "%{$last10}%")
                  ->orWhere('whatsapp', 'LIKE', "%{$last10}%")
                  ->orWhere('company_name', $companyName);
            })->exists();
        }

        return Lead::where('company_name', $companyName)
            ->when($locality, function ($q) use ($locality) {
                $q->where(function ($sub) use ($locality) {
                    $sub->where('location', 'LIKE', "%{$locality}%")
                        ->orWhere('area_locality', 'LIKE', "%{$locality}%");
                });
            })
            ->exists();
    }

    /**
     * Map a raw spreadsheet row to the Lead model attributes.
     */
    private function mapRowToLeadData(array $row, string $importedBy): ?array
    {
        $companyName = trim(
            $row['customer___company_name']
            ?? ($row['customer_company_name']
            ?? ($row['customer_name']
            ?? ($row['company_name']
            ?? ($row['company___business_name']
            ?? ($row['name'] ?? '')))))
        );

        if (empty($companyName) || in_array(strtolower($companyName), ['nan', 'null', 'n/a'], true)) {
            return null;
        }

        $phone = trim($row['phone'] ?? ($row['contact_phone'] ?? ($row['contact_number'] ?? ($row['mobile'] ?? ''))));
        $phone = in_array(strtolower($phone), ['n/a', 'nan', 'none', 'null', ''], true) ? null : $phone;
        $rawDigits = $phone ? preg_replace('/\D/', '', $phone) : '';
        $last10 = strlen($rawDigits) >= 10 ? substr($rawDigits, -10) : '';

        $contactPerson = trim($row['contact_person'] ?? ($row['person_name'] ?? $companyName));
        $locality = trim($row['area_locality'] ?? ($row['area___locality'] ?? ($row['location'] ?? '')));
        $locality = ($locality === 'nan' || empty($locality)) ? null : $locality;

        return $this->buildLeadAttributes($row, $companyName, $contactPerson, $phone, $last10, $rawDigits, $locality, $importedBy);
    }

    /**
     * Build normalized Lead model attributes including Solar EPC columns.
     */
    private function buildLeadAttributes(
        array $row,
        string $companyName,
        string $contactPerson,
        ?string $phone,
        string $last10,
        string $rawDigits,
        ?string $locality,
        string $importedBy
    ): array {
        $hasWebRaw = strtoupper(trim($row['has_website_'] ?? ($row['has_website'] ?? '')));
        $hasWebsite = str_contains($hasWebRaw, 'YES') ? 'YES' : 'NO';
        $isHot = $hasWebsite === 'NO';

        $notes = trim($row['notes_requirement'] ?? ($row['notes'] ?? ($row['requirement'] ?? '')));
        $sanctionedLoad = is_numeric($row['sanctioned_load_kw'] ?? ($row['sanctioned_load'] ?? null))
            ? (float) ($row['sanctioned_load_kw'] ?? $row['sanctioned_load'])
            : null;
        $monthlyBill = is_numeric($row['monthly_electricity_bill_inr'] ?? ($row['monthly_electricity_bill'] ?? ($row['monthly_bill'] ?? null)))
            ? (float) ($row['monthly_electricity_bill_inr'] ?? $row['monthly_electricity_bill'] ?? $row['monthly_bill'])
            : null;
        $solarCapacity = is_numeric($row['proposed_solar_capacity_kw'] ?? ($row['solar_capacity_kw'] ?? ($row['solar_capacity'] ?? null)))
            ? (float) ($row['proposed_solar_capacity_kw'] ?? $row['solar_capacity_kw'] ?? $row['solar_capacity'])
            : null;

        return [
            'id' => (string) Str::uuid(),
            '_last10' => $last10,
            'company_name' => $companyName,
            'contact_person' => $contactPerson ?: $companyName,
            'email' => $this->cleanValue($row['email'] ?? ($row['email_address'] ?? null)),
            'phone' => $phone,
            'whatsapp' => $rawDigits ? (strlen($rawDigits) === 10 ? '91' . $rawDigits : $rawDigits) : null,
            'website' => $this->cleanValue($row['website'] ?? ($row['existing_website_url'] ?? null)),
            'has_website' => $hasWebsite,
            'lead_priority_tag' => $isHot ? '🔥 Hot Lead' : 'Solar Prospect',
            'industry' => trim($row['industry'] ?? ($row['industry_category'] ?? 'Solar Energy')),
            'products_services' => trim($row['product_group'] ?? ($row['products_services'] ?? 'Rooftop Solar')),
            'lead_source' => 'Excel Import',
            'scraped_by' => $importedBy,
            'location' => $locality,
            'area_locality' => $locality,
            'full_address' => $this->cleanValue($row['full_address'] ?? ($row['address'] ?? null)),
            'rating' => $this->cleanValue($row['rating'] ?? null),
            'total_reviews' => $this->cleanValue($row['total_reviews'] ?? ($row['reviews'] ?? '0')),
            'requirement' => $notes ?: 'Solar inquiry imported via spreadsheet.',
            'estimated_budget' => $monthlyBill ? round($monthlyBill * 12 * 0.25, 2) : 0.00,
            'priority' => ($solarCapacity && $solarCapacity >= 10.0) ? 'urgent' : 'medium',
            'status' => 'new',
            'notes' => $notes ? "Notes: {$notes}\nImported By: {$importedBy}" : "Imported By: {$importedBy}",
            // Solar EPC Specific Fields
            'consumer_number' => $this->cleanValue($row['consumer_number'] ?? ($row['consumer_no'] ?? null)),
            'discom' => $this->cleanValue($row['discom'] ?? ($row['electricity_board'] ?? null)),
            'sanctioned_load' => $sanctionedLoad,
            'monthly_bill' => $monthlyBill,
            'solar_capacity_kw' => $solarCapacity,
            'product_group' => $this->cleanValue($row['product_group'] ?? null),
            'customer_group' => $this->cleanValue($row['customer_group'] ?? null),
            'lead_potential' => $this->cleanValue($row['lead_potential'] ?? null),
            'lead_type' => $this->cleanValue($row['lead_type'] ?? null),
            'society_name' => $this->cleanValue($row['society_apartment'] ?? ($row['society_name'] ?? null)),
            'city' => $this->cleanValue($row['city'] ?? 'Vadodara'),
            'state' => $this->cleanValue($row['state'] ?? 'Gujarat'),
            'pincode' => $this->cleanValue($row['pincode'] ?? ($row['pin_code'] ?? null)),
        ];
    }

    /**
     * Clean null/nan/empty cell values.
     */
    private function cleanValue(?string $val): ?string
    {
        if ($val === null) return null;
        $trimmed = trim($val);
        if (in_array(strtolower($trimmed), ['nan', 'none', 'null', 'n/a', ''], true)) {
            return null;
        }
        return $trimmed;
    }

    /**
     * Parses a CSV file into normalized associative arrays.
     */
    private function parseCsv(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $rawHeaders = fgetcsv($handle);
            if (!$rawHeaders) {
                fclose($handle);
                return [];
            }

            $headers = array_map([$this, 'normalizeHeader'], $rawHeaders);
            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) === count($headers)) {
                    $rows[] = array_combine($headers, $data);
                }
            }
            fclose($handle);
        }
        return $rows;
    }

    /**
     * Parses an XLSX file using built-in ZipArchive and SimpleXML.
     */
    private function parseXlsx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [];
        }

        $sharedStrings = $this->extractSharedStrings($zip);
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet2.xml') ?: $zip->getFromName('xl/worksheets/sheet1.xml');
        if (!$sheetXmlContent) {
            $zip->close();
            return [];
        }

        $sheetXml = new SimpleXMLElement($sheetXmlContent);
        $rowsData = $this->extractRowsFromSheet($sheetXml, $sharedStrings);
        $zip->close();

        if (empty($rowsData)) {
            return [];
        }

        $headers = array_map([$this, 'normalizeHeader'], array_shift($rowsData));
        $normalizedRows = [];
        foreach ($rowsData as $row) {
            $padded = array_pad($row, count($headers), '');
            $normalizedRows[] = array_combine($headers, array_slice($padded, 0, count($headers)));
        }

        return $normalizedRows;
    }

    /**
     * Extract shared strings table from XLSX.
     */
    private function extractSharedStrings(ZipArchive $zip): array
    {
        $strings = [];
        $xmlContent = $zip->getFromName('xl/sharedStrings.xml');
        if ($xmlContent) {
            $xml = new SimpleXMLElement($xmlContent);
            foreach ($xml->si as $si) {
                $strings[] = (string) ($si->t ?? $si->r->t ?? '');
            }
        }
        return $strings;
    }

    /**
     * Extract row cells from worksheet XML.
     */
    private function extractRowsFromSheet(SimpleXMLElement $sheetXml, array $sharedStrings): array
    {
        $rowsData = [];
        foreach ($sheetXml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $val = (string) $cell->v;
                if ((string) $cell['t'] === 's' && isset($sharedStrings[(int) $val])) {
                    $val = $sharedStrings[(int) $val];
                }
                $rowData[] = $val;
            }
            if (!empty($rowData)) {
                $rowsData[] = $rowData;
            }
        }
        return $rowsData;
    }

    /**
     * Normalizes spreadsheet headers to lowercase snake_case.
     */
    private function normalizeHeader(string $header): string
    {
        $clean = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header);
        $clean = strtolower(trim($clean));
        $clean = preg_replace('/[^a-z0-9]+/', '_', $clean);
        return trim($clean, '_');
    }
}
