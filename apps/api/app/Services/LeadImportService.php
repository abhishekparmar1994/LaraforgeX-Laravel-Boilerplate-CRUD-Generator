<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Lead;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use ZipArchive;
use SimpleXMLElement;

/**
 * Service for parsing and importing business leads from Excel (.xlsx) and CSV files.
 * Automatically detects and skips duplicate leads.
 */
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
        $rows = [];

        if ($extension === 'csv' || $extension === 'txt') {
            $rows = $this->parseCsv($file->getRealPath());
        } elseif ($extension === 'xlsx') {
            $rows = $this->parseXlsx($file->getRealPath());
        } else {
            throw new \InvalidArgumentException('Unsupported file format. Please upload a .xlsx or .csv file.');
        }

        $imported = 0;
        $skipped = 0;
        $total = count($rows);

        foreach ($rows as $row) {
            $companyName = trim($row['company_name'] ?? ($row['company___business_name'] ?? ($row['name'] ?? '')));
            if (empty($companyName) || strtolower($companyName) === 'nan' || strtolower($companyName) === 'null') {
                continue;
            }

            $phone = trim($row['contact_number'] ?? ($row['phone'] ?? ($row['mobile'] ?? '')));
            if ($phone === 'N/A' || $phone === 'nan' || $phone === 'None') {
                $phone = null;
            }

            $rawDigits = $phone ? preg_replace('/\D/', '', $phone) : '';
            $last10 = strlen($rawDigits) >= 10 ? substr($rawDigits, -10) : '';

            $locality = trim($row['area___locality'] ?? ($row['area_locality'] ?? ($row['location'] ?? '')));
            if ($locality === 'nan') $locality = null;

            // ── DUPLICATE CHECK ──
            $isDuplicate = false;
            if (!empty($last10)) {
                $isDuplicate = Lead::where(function ($q) use ($last10, $companyName) {
                    $q->where('phone', 'LIKE', "%{$last10}%")
                      ->orWhere('whatsapp', 'LIKE', "%{$last10}%")
                      ->orWhere('company_name', $companyName);
                })->exists();
            } else {
                $isDuplicate = Lead::where('company_name', $companyName)
                    ->when($locality, function ($q) use ($locality) {
                        $q->where(function ($sub) use ($locality) {
                            $sub->where('location', 'LIKE', "%{$locality}%")
                                ->orWhere('area_locality', 'LIKE', "%{$locality}%");
                        });
                    })
                    ->exists();
            }

            if ($isDuplicate) {
                $skipped++;
                continue;
            }

            // ── CREATE NEW LEAD ──
            $hasWebRaw = strtoupper(trim($row['has_website_'] ?? ($row['has_website'] ?? '')));
            $hasWebsite = str_contains($hasWebRaw, 'YES') ? 'YES' : 'NO';
            $isHot = $hasWebsite === 'NO';

            $website = trim($row['existing_website_url'] ?? ($row['website'] ?? ''));
            if ($website === 'N/A' || $website === 'nan' || $website === 'None' || empty($website)) {
                $website = null;
            }

            $email = trim($row['email_address'] ?? ($row['email'] ?? ''));
            if ($email === 'N/A' || $email === 'nan' || empty($email)) {
                $email = null;
            }

            $waPhone = null;
            if ($rawDigits) {
                $waPhone = strlen($rawDigits) === 10 ? '91' . $rawDigits : $rawDigits;
            }

            $rating = trim($row['rating'] ?? '');
            if ($rating === 'nan') $rating = null;

            $reviews = trim($row['total_reviews'] ?? ($row['reviews'] ?? ''));
            if ($reviews === 'nan') $reviews = '0';

            $prompt = trim($row['website_build_prompt'] ?? ($row['prompt'] ?? ($row['requirement'] ?? '')));
            $address = trim($row['full_address'] ?? ($row['address'] ?? ''));
            $mapsUrl = trim($row['google_maps_link'] ?? ($row['maps_url'] ?? ''));
            $industry = trim($row['industry_category'] ?? ($row['industry'] ?? ($row['category'] ?? 'General Business')));
            $products = trim($row['product___services'] ?? ($row['products_services'] ?? ($row['services'] ?? '')));

            $notesContent = "Rating: ★ {$rating} ({$reviews} reviews)\nAddress: {$address}\nMaps: {$mapsUrl}\nImported By: {$importedBy}";

            Lead::create([
                'id' => (string) Str::uuid(),
                'company_name' => $companyName,
                'contact_person' => $companyName,
                'email' => $email,
                'phone' => $phone,
                'whatsapp' => $waPhone,
                'website' => $website,
                'has_website' => $hasWebsite,
                'lead_priority_tag' => $isHot ? '🔥 Hot Lead (No Website)' : '⚠️ Has Website',
                'industry' => $industry,
                'products_services' => $products,
                'lead_source' => 'Excel Import',
                'scraped_by' => $importedBy,
                'location' => $locality,
                'area_locality' => $locality,
                'full_address' => $address,
                'rating' => $rating,
                'total_reviews' => $reviews,
                'requirement' => $prompt ?: 'Business lead imported via Excel.',
                'website_build_prompt' => $prompt,
                'google_maps_link' => $mapsUrl,
                'estimated_budget' => $isHot ? 500.00 : 0.00,
                'priority' => $isHot ? 'urgent' : 'medium',
                'status' => 'new',
                'notes' => $notesContent,
            ]);

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
     * Parses a CSV file into normalized associative arrays.
     */
    private function parseCsv(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Read headers
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
     * Parses an XLSX file using built-in ZipArchive and SimpleXML (Sheet 1 or 2).
     */
    private function parseXlsx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [];
        }

        // 1. Read shared strings
        $sharedStrings = [];
        $stringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($stringsXml) {
            $xml = new SimpleXMLElement($stringsXml);
            foreach ($xml->si as $si) {
                $sharedStrings[] = (string) ($si->t ?? $si->r->t ?? '');
            }
        }

        // 2. Locate worksheet (check sheet2.xml first for 'All Leads', or fallback to sheet1.xml)
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet2.xml') ?: $zip->getFromName('xl/worksheets/sheet1.xml');
        if (!$sheetXmlContent) {
            $zip->close();
            return [];
        }

        $sheetXml = new SimpleXMLElement($sheetXmlContent);
        $rowsData = [];

        foreach ($sheetXml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $val = (string) $cell->v;
                $type = (string) $cell['t'];

                if ($type === 's' && isset($sharedStrings[(int) $val])) {
                    $val = $sharedStrings[(int) $val];
                }
                $rowData[] = $val;
            }
            if (!empty($rowData)) {
                $rowsData[] = $rowData;
            }
        }

        $zip->close();

        if (empty($rowsData)) {
            return [];
        }

        // Extract header
        $headers = array_map([$this, 'normalizeHeader'], array_shift($rowsData));
        $normalizedRows = [];

        foreach ($rowsData as $row) {
            $padded = array_pad($row, count($headers), '');
            $normalizedRows[] = array_combine($headers, array_slice($padded, 0, count($headers)));
        }

        return $normalizedRows;
    }

    /**
     * Normalizes spreadsheet headers to lowercase snake_case.
     */
    private function normalizeHeader(string $header): string
    {
        // Strip BOM and special characters
        $clean = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header);
        $clean = strtolower(trim($clean));
        $clean = preg_replace('/[^a-z0-9]+/', '_', $clean);
        return trim($clean, '_');
    }
}
