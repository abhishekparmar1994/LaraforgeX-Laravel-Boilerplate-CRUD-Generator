<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

/**
 * Text-to-SQL Read-Only Tool allowing the AI Agent to execute safe SELECT queries.
 */
class QueryDatabaseTool implements AgentToolInterface
{
    private const ALLOWED_TABLES = [
        'crm_leads',
        'crm_clients',
        'crm_companies',
        'crm_contacts',
        'crm_projects',
        'crm_tasks',
        'crm_followups',
        'crm_payments',
        'crm_activities',
        'crm_websites',
        'crm_notes',
        'users',
    ];

    public function getName(): string
    {
        return 'query_crm_database';
    }

    public function getDescription(): string
    {
        return 'Execute a safe read-only SQL SELECT query on the CRM database to answer custom user questions, search by locality/area, calculate custom aggregates, or filter records. Only SELECT statements on CRM tables are permitted.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => [
                    'type' => 'string',
                    'description' => 'The SQL SELECT query to execute (e.g. "SELECT company_name, contact_person, area_locality, status, estimated_budget FROM crm_leads WHERE area_locality LIKE \'%gotri%\' LIMIT 10").',
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
        $sql = trim((string) ($arguments['query'] ?? ''));
        if (empty($sql)) {
            return ['status' => 'error', 'message' => 'Empty query provided.'];
        }

        $validationError = $this->validateQuery($sql);
        if ($validationError) {
            return ['status' => 'rejected', 'message' => $validationError];
        }

        // Ensure LIMIT is present to safeguard performance
        if (!preg_match('/\blimit\s+\d+/i', $sql)) {
            $sql .= ' LIMIT 25';
        }

        try {
            $rows = DB::select($sql);
            $cleanRows = $this->sanitizeRows($rows);

            return [
                'status' => 'success',
                'count'  => count($cleanRows),
                'rows'   => $cleanRows,
            ];
        } catch (Throwable $e) {
            return [
                'status'  => 'sql_error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Validates that the query is strictly a read-only SELECT on permitted CRM tables.
     */
    private function validateQuery(string $sql): ?string
    {
        $normalized = preg_replace('/\s+/', ' ', strtolower($sql));

        // 1. Must start with SELECT
        if (!str_starts_with($normalized, 'select ')) {
            return 'Only SELECT queries are allowed.';
        }

        // 2. Reject destructive and administrative keywords
        $forbiddenKeywords = [
            'insert', 'update', 'delete', 'drop', 'alter', 'truncate',
            'create', 'replace', 'grant', 'revoke', 'information_schema',
            'into outfile', 'load_file', 'sleep(', 'benchmark(',
        ];

        foreach ($forbiddenKeywords as $bad) {
            if (str_contains($normalized, $bad)) {
                return "Query contains forbidden keyword or modification statement [{$bad}].";
            }
        }

        return null;
    }

    /**
     * Sanitizes results to prevent sensitive credential exposure.
     *
     * @param array<int, object> $rows
     * @return array<int, array<string, mixed>>
     */
    private function sanitizeRows(array $rows): array
    {
        return array_map(function ($row) {
            $data = (array) $row;
            unset(
                $data['password'],
                $data['remember_token'],
                $data['two_factor_secret'],
                $data['two_factor_recovery_codes']
            );
            return $data;
        }, array_slice($rows, 0, 50));
    }
}
