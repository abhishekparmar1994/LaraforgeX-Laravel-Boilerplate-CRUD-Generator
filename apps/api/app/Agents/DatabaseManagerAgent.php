<?php

declare(strict_types=1);

namespace App\Agents;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;
use Throwable;

/**
 * DatabaseManagerAgent — Domain Orchestrator for Database Schema Introspection,
 * DDL Execution (Create, Alter, Drop, Truncate), Index Management, and Data Browsing.
 */
class DatabaseManagerAgent
{
    /**
     * Get a summary list of all tables in the connected MySQL database with storage metrics.
     *
     * @return array List of table summaries with row count, storage size, engine, and collation.
     */
    public function getTablesSummary(): array
    {
        $dbName = config('database.connections.mysql.database');

        $query = "
            SELECT 
                TABLE_NAME as name,
                ENGINE as engine,
                TABLE_ROWS as table_rows,
                DATA_LENGTH as data_length,
                INDEX_LENGTH as index_length,
                (DATA_LENGTH + INDEX_LENGTH) as total_size,
                TABLE_COLLATION as collation,
                TABLE_COMMENT as comment,
                CREATE_TIME as created_at
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = ?
            ORDER BY TABLE_NAME ASC
        ";

        $results = DB::select($query, [$dbName]);

        $tables = [];
        $totalDatabaseSize = 0;
        $totalRows = 0;

        foreach ($results as $row) {
            $dataSize = (int) ($row->data_length ?? 0);
            $indexSize = (int) ($row->index_length ?? 0);
            $totalSize = (int) ($row->total_size ?? 0);
            $rows = (int) ($row->table_rows ?? 0);

            $totalDatabaseSize += $totalSize;
            $totalRows += $rows;

            $tables[] = [
                'name'         => $row->name,
                'engine'       => $row->engine ?? 'InnoDB',
                'table_rows'   => $rows,
                'data_size'    => $this->formatBytes($dataSize),
                'index_size'   => $this->formatBytes($indexSize),
                'total_size'   => $this->formatBytes($totalSize),
                'size_bytes'   => $totalSize,
                'collation'    => $row->collation ?? 'utf8mb4_unicode_ci',
                'comment'      => $row->comment ?? '',
                'created_at'   => $row->created_at ?? date('Y-m-d H:i:s'),
            ];
        }

        return [
            'database_name' => $dbName,
            'tables_count'  => count($tables),
            'total_rows'    => $totalRows,
            'total_size'    => $this->formatBytes($totalDatabaseSize),
            'tables'        => $tables,
        ];
    }

    /**
     * Inspect full table schema details: Columns, Indexes, and Foreign Keys.
     *
     * @param  string $tableName Table name to inspect
     * @return array  Detailed table schema structure
     */
    public function getTableDetails(string $tableName): array
    {
        $this->validateTableName($tableName);

        $dbName = config('database.connections.mysql.database');

        // 1. Column Definitions
        $columnsRaw = DB::select("SHOW FULL COLUMNS FROM `{$tableName}`");
        $columns = [];
        foreach ($columnsRaw as $col) {
            $columns[] = [
                'name'           => $col->Field,
                'type'           => $col->Type,
                'collation'      => $col->Collation,
                'nullable'       => $col->Null === 'YES',
                'key'            => $col->Key,
                'default'        => $col->Default,
                'extra'          => $col->Extra,
                'auto_increment' => str_contains(strtolower($col->Extra ?? ''), 'auto_increment'),
                'comment'        => $col->Comment,
            ];
        }

        // 2. Indexes
        $indexesRaw = DB::select("SHOW INDEX FROM `{$tableName}`");
        $indexGroup = [];
        foreach ($indexesRaw as $idx) {
            $keyName = $idx->Key_name;
            if (!isset($indexGroup[$keyName])) {
                $indexGroup[$keyName] = [
                    'name'        => $keyName,
                    'type'        => $keyName === 'PRIMARY' ? 'PRIMARY' : ($idx->Non_unique == 0 ? 'UNIQUE' : ($idx->Index_type === 'FULLTEXT' ? 'FULLTEXT' : 'INDEX')),
                    'unique'      => $idx->Non_unique == 0,
                    'index_type'  => $idx->Index_type,
                    'columns'     => [],
                    'cardinality' => $idx->Cardinality ?? 0,
                ];
            }
            $indexGroup[$keyName]['columns'][] = $idx->Column_name;
        }
        $indexes = array_values($indexGroup);

        // 3. Foreign Keys
        $fkQuery = "
            SELECT 
                k.CONSTRAINT_NAME as constraint_name,
                k.COLUMN_NAME as column_name,
                k.REFERENCED_TABLE_NAME as foreign_table,
                k.REFERENCED_COLUMN_NAME as foreign_column,
                r.DELETE_RULE as on_delete,
                r.UPDATE_RULE as on_update
            FROM information_schema.KEY_COLUMN_USAGE k
            JOIN information_schema.REFERENTIAL_CONSTRAINTS r
                ON k.CONSTRAINT_NAME = r.CONSTRAINT_NAME
                AND k.CONSTRAINT_SCHEMA = r.CONSTRAINT_SCHEMA
            WHERE k.TABLE_SCHEMA = ?
              AND k.TABLE_NAME = ?
              AND k.REFERENCED_TABLE_NAME IS NOT NULL
        ";
        $fksRaw = DB::select($fkQuery, [$dbName, $tableName]);
        $foreignKeys = [];
        foreach ($fksRaw as $fk) {
            $foreignKeys[] = [
                'constraint_name' => $fk->constraint_name,
                'column'          => $fk->column_name,
                'foreign_table'   => $fk->foreign_table,
                'foreign_column'  => $fk->foreign_column,
                'on_delete'       => $fk->on_delete,
                'on_update'       => $fk->on_update,
            ];
        }

        // 4. Create Table SQL DDL
        $createSqlRaw = DB::select("SHOW CREATE TABLE `{$tableName}`");
        $createSql = $createSqlRaw[0]->{'Create Table'} ?? '';

        return [
            'name'         => $tableName,
            'columns'      => $columns,
            'indexes'      => $indexes,
            'foreign_keys' => $foreignKeys,
            'create_sql'   => $createSql,
        ];
    }

    /**
     * Fetch paginated records from a specific database table with search capabilities.
     *
     * @param  string $tableName Target table
     * @param  int    $page      Current page
     * @param  int    $perPage   Items per page
     * @param  string|null $search Search term
     * @return array  Paginated dataset
     */
    public function getTableData(string $tableName, int $page = 1, int $perPage = 15, ?string $search = null, ?array $filters = null): array
    {
        $this->validateTableName($tableName);

        $query = DB::table($tableName);
        $validColumns = Schema::getColumnListing($tableName);

        // 1. Process Navicat-Style Advanced Filter Rules
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $rule) {
                if (!is_array($rule)) continue;
                
                $enabled = isset($rule['enabled']) ? (bool) $rule['enabled'] : true;
                if (!$enabled) continue;

                $col = $rule['column'] ?? '';
                if (!in_array($col, $validColumns, true)) continue;

                $op = strtolower($rule['operator'] ?? '=');
                $val = $rule['value'] ?? '';

                switch ($op) {
                    case '=':
                    case 'equals':
                        $query->where($col, '=', $val);
                        break;
                    case '!=':
                    case 'not_equals':
                        $query->where($col, '!=', $val);
                        break;
                    case '<':
                    case 'less_than':
                        $query->where($col, '<', $val);
                        break;
                    case '<=':
                    case 'less_equal':
                        $query->where($col, '<=', $val);
                        break;
                    case '>':
                    case 'greater_than':
                        $query->where($col, '>', $val);
                        break;
                    case '>=':
                    case 'greater_equal':
                        $query->where($col, '>=', $val);
                        break;
                    case 'contains':
                        $query->where($col, 'LIKE', "%{$val}%");
                        break;
                    case 'does_not_contain':
                    case 'does not contain':
                        $query->where($col, 'NOT LIKE', "%{$val}%");
                        break;
                    case 'begins_with':
                    case 'begin with':
                    case 'begins with':
                        $query->where($col, 'LIKE', "{$val}%");
                        break;
                    case 'does_not_begin_with':
                    case 'does not begin with':
                        $query->where($col, 'NOT LIKE', "{$val}%");
                        break;
                    case 'ends_with':
                    case 'end with':
                    case 'ends with':
                        $query->where($col, 'LIKE', "%{$val}");
                        break;
                    case 'does_not_end_with':
                    case 'does not end with':
                        $query->where($col, 'NOT LIKE', "%{$val}");
                        break;
                    case 'is_null':
                    case 'is null':
                        $query->whereNull($col);
                        break;
                    case 'is_not_null':
                    case 'is not null':
                        $query->whereNotNull($col);
                        break;
                    case 'is_empty':
                    case 'is empty':
                        $query->where($col, '=', '');
                        break;
                    case 'is_not_empty':
                    case 'is not empty':
                        $query->where($col, '!=', '');
                        break;
                    default:
                        $query->where($col, '=', $val);
                        break;
                }
            }
        }

        // 2. Global Text Search
        if (!empty($search)) {
            $query->where(function ($q) use ($validColumns, $search) {
                foreach ($validColumns as $index => $col) {
                    if ($index === 0) {
                        $q->where($col, 'LIKE', "%{$search}%");
                    } else {
                        $q->orWhere($col, 'LIKE', "%{$search}%");
                    }
                }
            });
        }

        $total = $query->count();
        $offset = ($page - 1) * $perPage;
        $items = $query->offset($offset)->limit($perPage)->get();

        return [
            'table'        => $tableName,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / max($perPage, 1)),
            'rows'         => $items,
        ];
    }

    /**
     * Create a new database table via dynamic DDL SQL execution.
     *
     * @param  array $data Creation payload including name, engine, collation, columns, and foreign keys
     * @return string Message confirmation
     */
    public function createTable(array $data): string
    {
        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', $data['table_name']);
        if (Schema::hasTable($tableName)) {
            throw new Exception("Table `{$tableName}` already exists.");
        }

        $engine = in_array($data['engine'] ?? 'InnoDB', ['InnoDB', 'MyISAM', 'MEMORY']) ? $data['engine'] : 'InnoDB';
        $collation = !empty($data['collation']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $data['collation']) : 'utf8mb4_unicode_ci';

        $columnLines = [];
        $primaryKeys = [];

        foreach ($data['columns'] as $col) {
            $colName = preg_replace('/[^a-zA-Z0-9_]/', '', $col['name']);
            $type = strtoupper($col['type']);
            $length = !empty($col['length']) ? "({$col['length']})" : '';
            $nullable = !empty($col['nullable']) ? 'NULL' : 'NOT NULL';
            
            $default = '';
            if (isset($col['default']) && $col['default'] !== '') {
                $defVal = $col['default'];
                if (in_array(strtoupper((string)$defVal), ['CURRENT_TIMESTAMP', 'NULL', 'TRUE', 'FALSE'])) {
                    $default = "DEFAULT " . strtoupper((string)$defVal);
                } else {
                    $default = "DEFAULT '" . addslashes((string)$defVal) . "'";
                }
            }

            $autoInc = !empty($col['auto_increment']) ? 'AUTO_INCREMENT' : '';

            $line = "`{$colName}` {$type}{$length} {$nullable} {$default} {$autoInc}";
            $columnLines[] = trim($line);

            if (!empty($col['primary'])) {
                $primaryKeys[] = "`{$colName}`";
            }
        }

        if (!empty($primaryKeys)) {
            $pkCols = implode(', ', $primaryKeys);
            $columnLines[] = "PRIMARY KEY ({$pkCols})";
        }

        // Add foreign keys
        if (!empty($data['foreign_keys']) && is_array($data['foreign_keys'])) {
            foreach ($data['foreign_keys'] as $fk) {
                if (empty($fk['column']) || empty($fk['foreign_table']) || empty($fk['foreign_column'])) continue;

                $col = preg_replace('/[^a-zA-Z0-9_]/', '', $fk['column']);
                $fTable = preg_replace('/[^a-zA-Z0-9_]/', '', $fk['foreign_table']);
                $fCol = preg_replace('/[^a-zA-Z0-9_]/', '', $fk['foreign_column']);
                
                $onDelete = in_array(strtoupper($fk['on_delete'] ?? ''), ['CASCADE', 'SET NULL', 'RESTRICT', 'NO ACTION']) ? strtoupper($fk['on_delete']) : 'CASCADE';
                $onUpdate = in_array(strtoupper($fk['on_update'] ?? ''), ['CASCADE', 'SET NULL', 'RESTRICT', 'NO ACTION']) ? strtoupper($fk['on_update']) : 'CASCADE';

                $fkName = "fk_{$tableName}_{$col}";
                $columnLines[] = "CONSTRAINT `{$fkName}` FOREIGN KEY (`{$col}`) REFERENCES `{$fTable}` (`{$fCol}`) ON DELETE {$onDelete} ON UPDATE {$onUpdate}";
            }
        }

        // Add custom indexes
        if (!empty($data['indexes']) && is_array($data['indexes'])) {
            foreach ($data['indexes'] as $idx) {
                if (empty($idx['name']) || empty($idx['columns'])) continue;
                $idxName = preg_replace('/[^a-zA-Z0-9_]/', '', $idx['name']);
                $idxType = in_array(strtoupper($idx['type'] ?? ''), ['UNIQUE', 'INDEX', 'FULLTEXT']) ? strtoupper($idx['type']) : 'INDEX';
                $cols = array_map(fn($c) => "`" . preg_replace('/[^a-zA-Z0-9_]/', '', $c) . "`", (array)$idx['columns']);
                $colsStr = implode(', ', $cols);

                if ($idxType === 'UNIQUE') {
                    $columnLines[] = "UNIQUE KEY `{$idxName}` ({$colsStr})";
                } elseif ($idxType === 'FULLTEXT') {
                    $columnLines[] = "FULLTEXT KEY `{$idxName}` ({$colsStr})";
                } else {
                    $columnLines[] = "KEY `{$idxName}` ({$colsStr})";
                }
            }
        }

        $definitionsSql = implode(",\n  ", $columnLines);

        $sql = "CREATE TABLE `{$tableName}` (\n  {$definitionsSql}\n) ENGINE={$engine} DEFAULT CHARSET=utf8mb4 COLLATE={$collation};";

        DB::statement($sql);

        return "Table `{$tableName}` successfully created.";
    }

    /**
     * Add an index to an existing database table.
     *
     * @param  string $tableName Target table
     * @param  string $indexName Index constraint name
     * @param  string $indexType PRIMARY, UNIQUE, INDEX, FULLTEXT
     * @param  array  $columns   Columns included in index
     * @return string Message confirmation
     */
    public function addIndex(string $tableName, string $indexName, string $indexType, array $columns): string
    {
        $this->validateTableName($tableName);
        $idxName = preg_replace('/[^a-zA-Z0-9_]/', '', $indexName);
        $idxType = strtoupper($indexType);

        $cols = array_map(fn($c) => "`" . preg_replace('/[^a-zA-Z0-9_]/', '', $c) . "`", $columns);
        $colsStr = implode(', ', $cols);

        if ($idxType === 'PRIMARY') {
            $sql = "ALTER TABLE `{$tableName}` ADD PRIMARY KEY ({$colsStr})";
        } elseif ($idxType === 'UNIQUE') {
            $sql = "ALTER TABLE `{$tableName}` ADD UNIQUE KEY `{$idxName}` ({$colsStr})";
        } elseif ($idxType === 'FULLTEXT') {
            $sql = "ALTER TABLE `{$tableName}` ADD FULLTEXT KEY `{$idxName}` ({$colsStr})";
        } else {
            $sql = "ALTER TABLE `{$tableName}` ADD INDEX `{$idxName}` ({$colsStr})";
        }

        DB::statement($sql);

        return "Index `{$indexName}` successfully added to `{$tableName}`.";
    }

    /**
     * Drop an index from an existing database table.
     *
     * @param  string $tableName Target table
     * @param  string $indexName Index name to drop
     * @return string Message confirmation
     */
    public function dropIndex(string $tableName, string $indexName): string
    {
        $this->validateTableName($tableName);
        $idxName = preg_replace('/[^a-zA-Z0-9_]/', '', $indexName);

        if (strtoupper($idxName) === 'PRIMARY') {
            $sql = "ALTER TABLE `{$tableName}` DROP PRIMARY KEY";
        } else {
            $sql = "ALTER TABLE `{$tableName}` DROP INDEX `{$idxName}`";
        }

        DB::statement($sql);

        return "Index `{$indexName}` dropped from `{$tableName}`.";
    }

    /**
     * Truncate all records from a table safely.
     *
     * @param  string $tableName Table to truncate
     * @return string Message confirmation
     */
    public function truncateTable(string $tableName): string
    {
        $this->validateTableName($tableName);
        
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::statement("TRUNCATE TABLE `{$tableName}`");
        DB::statement("SET FOREIGN_KEY_CHECKS=1");

        return "Table `{$tableName}` truncated successfully.";
    }

    /**
     * Drop a table completely from database.
     *
     * @param  string $tableName Table to drop
     * @return string Message confirmation
     */
    public function dropTable(string $tableName): string
    {
        $this->validateTableName($tableName);

        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
        DB::statement("SET FOREIGN_KEY_CHECKS=1");

        return "Table `{$tableName}` dropped successfully.";
    }

    /**
     * Bulk truncate multiple tables safely.
     */
    public function bulkTruncate(array $tables): string
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        $count = 0;
        foreach ($tables as $t) {
            if (preg_match('/^[a-zA-Z0-9_]+$/', $t) && Schema::hasTable($t)) {
                DB::statement("TRUNCATE TABLE `{$t}`");
                $count++;
            }
        }
        DB::statement("SET FOREIGN_KEY_CHECKS=1");

        return "{$count} database tables truncated successfully.";
    }

    /**
     * Bulk drop multiple tables safely.
     */
    public function bulkDrop(array $tables): string
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        $count = 0;
        foreach ($tables as $t) {
            if (preg_match('/^[a-zA-Z0-9_]+$/', $t) && Schema::hasTable($t)) {
                DB::statement("DROP TABLE IF EXISTS `{$t}`");
                $count++;
            }
        }
        DB::statement("SET FOREIGN_KEY_CHECKS=1");

        return "{$count} database tables dropped successfully.";
    }

    /**
     * Drop one or multiple columns from a specific table.
     */
    public function dropColumns(string $tableName, array $columns): string
    {
        $this->validateTableName($tableName);

        $drops = [];
        foreach ($columns as $c) {
            $colName = preg_replace('/[^a-zA-Z0-9_]/', '', $c);
            if (Schema::hasColumn($tableName, $colName)) {
                $drops[] = "DROP COLUMN `{$colName}`";
            }
        }

        if (empty($drops)) {
            throw new Exception("No valid columns specified to drop.");
        }

        $sql = "ALTER TABLE `{$tableName}` " . implode(', ', $drops);
        DB::statement($sql);

        $count = count($drops);
        return "{$count} column(s) successfully dropped from `{$tableName}`.";
    }

    /**
     * Execute arbitrary raw SQL query safely and return structured results with execution metrics.
     *
     * @param  string $sql SQL statement to execute
     * @return array  QueryResult containing rows, affected count, execution time, and query type
     */
    public function executeQuery(string $sql): array
    {
        $sql = trim($sql);
        if (empty($sql)) {
            throw new Exception("SQL query string cannot be empty.");
        }

        $startTime = microtime(true);
        $firstWord = strtoupper(strtok($sql, " \n\t\r"));

        $isReadQuery = in_array($firstWord, ['SELECT', 'SHOW', 'EXPLAIN', 'DESCRIBE', 'PRAGMA', 'WITH']);

        if ($isReadQuery) {
            $results = DB::select($sql);
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            $columns = [];
            $rows = [];

            if (!empty($results)) {
                $columns = array_keys((array) $results[0]);
                foreach ($results as $row) {
                    $rows[] = (array) $row;
                }
            }

            return [
                'type'           => 'READ',
                'query'          => $sql,
                'columns'        => $columns,
                'rows'           => $rows,
                'total_rows'     => count($rows),
                'execution_ms'   => $executionTimeMs,
                'message'        => "Query executed in {$executionTimeMs} ms. Returns " . count($rows) . " record(s).",
            ];
        } else {
            // Write / DDL query
            $affected = DB::affectingStatement($sql);
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'type'           => 'WRITE',
                'query'          => $sql,
                'affected_rows'  => $affected,
                'execution_ms'   => $executionTimeMs,
                'message'        => "Statement executed successfully in {$executionTimeMs} ms. {$affected} row(s) affected.",
            ];
        }
    }

    /**
     * Verify table exists and has sanitized name.
     */
    private function validateTableName(string $tableName): void
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName) || !Schema::hasTable($tableName)) {
            throw new Exception("Target table `{$tableName}` does not exist or has invalid name.");
        }
    }

    /**
     * Format raw byte size into human readable format.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
