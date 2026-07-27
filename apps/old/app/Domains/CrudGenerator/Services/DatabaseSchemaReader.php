<?php

declare(strict_types=1);

namespace App\Domains\CrudGenerator\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSchemaReader
{
    /**
     * Get a list of configured database connections.
     *
     * @return array<int, string>
     */
    public function getConnections(): array
    {
        $connections = config('database.connections', []);
        $available = [];

        foreach (array_keys($connections) as $name) {
            try {
                // Test connection
                DB::connection((string) $name)->getPdo();
                $available[] = (string) $name;
            } catch (\Throwable $e) {
                // Skip unreachable connections
                continue;
            }
        }

        return !empty($available) ? $available : [config('database.default', 'mysql')];
    }

    /**
     * Get a list of active database names and their table counts on the connection.
     *
     * @param string|null $connection
     * @return array<int, array{name: string, tables: int}>
     */
    public function getDatabases(?string $connection = null): array
    {
        $conn = $connection ?: config('database.default', 'mysql');
        try {
            $driver = config("database.connections.{$conn}.driver", 'mysql');
            if ($driver === 'mysql' || $driver === 'mariadb') {
                $counts = [];
                try {
                    $tableCounts = DB::connection($conn)->select("
                        SELECT TABLE_SCHEMA as name, COUNT(TABLE_NAME) as tables_count 
                        FROM information_schema.TABLES 
                        WHERE TABLE_SCHEMA NOT IN ('information_schema', 'performance_schema', 'mysql', 'sys')
                        GROUP BY TABLE_SCHEMA
                    ");
                    foreach ($tableCounts as $tc) {
                        $counts[(string) $tc->name] = (int) $tc->tables_count;
                    }
                } catch (\Throwable $e) {
                    // Ignore information_schema restriction if any
                }

                $rows = DB::connection($conn)->select('SHOW DATABASES');
                $list = [];
                foreach ($rows as $row) {
                    $rowArr = (array) $row;
                    $dbName = (string) reset($rowArr);
                    if ($dbName && !in_array($dbName, ['information_schema', 'performance_schema', 'mysql', 'sys'], true)) {
                        $list[] = [
                            'name' => $dbName,
                            'tables' => $counts[$dbName] ?? 0,
                        ];
                    }
                }
                if (!empty($list)) {
                    return $list;
                }
            }
        } catch (\Throwable $e) {
            // Fallback to configured database name
        }

        $defaultDb = (string) config("database.connections.{$conn}.database", 'laravel');
        return [
            [
                'name' => $defaultDb,
                'tables' => 0,
            ]
        ];
    }

    /**
     * Helper to dynamically set database on connection if provided.
     */
    protected function switchToDatabase(string $connection, ?string $database = null): string
    {
        $conn = $connection ?: config('database.default', 'mysql');

        if ($database && $database !== config("database.connections.{$conn}.database")) {
            config(["database.connections.{$conn}.database" => $database]);
            DB::purge($conn);
            DB::reconnect($conn);
            try {
                DB::connection($conn)->statement("USE `{$database}`");
            } catch (\Throwable $e) {
                // Ignore statement errors
            }
        }

        return $conn;
    }

    /**
     * Get a list of table names in the specified connection and database.
     *
     * @param string|null $connection
     * @param string|null $database
     * @return array<int, array{name: string, rows: int}>
     */
    public function getTables(?string $connection = null, ?string $database = null): array
    {
        $conn = $this->switchToDatabase($connection ?: config('database.default', 'mysql'), $database);

        try {
            $result = [];

            if ($database) {
                // Query information_schema specifically for $database to guarantee zero cross-db table bleeding
                $rows = DB::connection($conn)->select("
                    SELECT TABLE_NAME as name, TABLE_ROWS as rows
                    FROM information_schema.TABLES
                    WHERE TABLE_SCHEMA = ?
                      AND TABLE_TYPE = 'BASE TABLE'
                    ORDER BY TABLE_NAME ASC
                ", [$database]);

                foreach ($rows as $row) {
                    $tableName = (string) $row->name;

                    if (in_array($tableName, ['migrations', 'failed_jobs', 'personal_access_tokens', 'password_reset_tokens', 'sessions'], true)) {
                        continue;
                    }

                    $rowCount = 0;
                    try {
                        $rowCount = DB::connection($conn)->table($tableName)->count();
                    } catch (\Throwable $e) {
                        $rowCount = (int) ($row->rows ?? 0);
                    }

                    $result[] = [
                        'name' => $tableName,
                        'rows' => $rowCount,
                    ];
                }

                if (!empty($result)) {
                    return $result;
                }
            }

            // Fallback for connections without explicit database param
            $tables = Schema::connection($conn)->getTables();

            foreach ($tables as $table) {
                $tableName = is_array($table) ? $table['name'] : ($table->name ?? (string) $table);

                if (in_array($tableName, ['migrations', 'failed_jobs', 'personal_access_tokens', 'password_reset_tokens', 'sessions'], true)) {
                    continue;
                }

                $rowCount = 0;
                try {
                    $rowCount = DB::connection($conn)->table($tableName)->count();
                } catch (\Throwable $e) {
                    $rowCount = 0;
                }

                $result[] = [
                    'name' => $tableName,
                    'rows' => $rowCount,
                ];
            }

            return $result;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Inspect a table and return comprehensive schema information.
     *
     * @param string $tableName
     * @param string|null $connection
     * @param string|null $database
     * @return array<string, mixed>
     */
    public function inspectTable(string $tableName, ?string $connection = null, ?string $database = null): array
    {
        $conn = $this->switchToDatabase($connection ?: config('database.default', 'mysql'), $database);

        if (!Schema::connection($conn)->hasTable($tableName)) {
            throw new \InvalidArgumentException("Table '{$tableName}' does not exist on connection '{$conn}'.");
        }

        $rawColumns = Schema::connection($conn)->getColumns($tableName);
        $indexes = Schema::connection($conn)->getIndexes($tableName);
        $foreignKeys = Schema::connection($conn)->getForeignKeys($tableName);

        $primaryKeys = [];
        $uniqueKeys = [];
        $indexedKeys = [];

        foreach ($indexes as $index) {
            $indexColumns = is_array($index) ? ($index['columns'] ?? []) : ($index->columns ?? []);
            if (is_array($index) ? ($index['primary'] ?? false) : ($index->primary ?? false)) {
                $primaryKeys = array_merge($primaryKeys, $indexColumns);
            } elseif (is_array($index) ? ($index['unique'] ?? false) : ($index->unique ?? false)) {
                $uniqueKeys = array_merge($uniqueKeys, $indexColumns);
            } else {
                $indexedKeys = array_merge($indexedKeys, $indexColumns);
            }
        }

        $foreignKeyMap = [];
        foreach ($foreignKeys as $fk) {
            $localCols = is_array($fk) ? ($fk['columns'] ?? []) : ($fk->columns ?? []);
            $foreignTable = is_array($fk) ? ($fk['foreign_table'] ?? '') : ($fk->foreign_table ?? '');
            $foreignCols = is_array($fk) ? ($fk['foreign_columns'] ?? []) : ($fk->foreign_columns ?? []);

            foreach ($localCols as $idx => $col) {
                $foreignKeyMap[$col] = [
                    'foreign_table' => $foreignTable,
                    'foreign_column' => $foreignCols[$idx] ?? 'id',
                    'on_update' => is_array($fk) ? ($fk['on_update'] ?? 'CASCADE') : ($fk->on_update ?? 'CASCADE'),
                    'on_delete' => is_array($fk) ? ($fk['on_delete'] ?? 'CASCADE') : ($fk->on_delete ?? 'CASCADE'),
                ];
            }
        }

        $columns = [];
        $displayOrder = 1;

        foreach ($rawColumns as $rawCol) {
            $colArray = (array) $rawCol;
            $name = $colArray['name'];
            $typeName = strtolower((string) ($colArray['type_name'] ?? $colArray['type'] ?? 'string'));
            $nullable = (bool) ($colArray['nullable'] ?? false);
            $default = $colArray['default'] ?? null;
            $isPrimary = in_array($name, $primaryKeys, true);
            $isUnique = in_array($name, $uniqueKeys, true);
            $isIndex = in_array($name, $indexedKeys, true);
            $fkInfo = $foreignKeyMap[$name] ?? null;

            $controlType = $this->suggestControlType($name, $typeName, $fkInfo !== null);
            $validationRules = $this->suggestValidationRules($name, $typeName, $nullable, $isUnique);
            $label = Str::title(str_replace('_', ' ', $name));

            // Default visibilities
            $isSystemCol = in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true);
            $isPassword = Str::contains($name, ['password', 'secret', 'token']);

            $columns[] = [
                'name' => $name,
                'type_name' => $typeName,
                'db_type' => $colArray['type'] ?? $typeName,
                'length' => $colArray['length'] ?? null,
                'nullable' => $nullable,
                'default' => $default,
                'is_primary' => $isPrimary,
                'is_unique' => $isUnique,
                'is_index' => $isIndex,
                'foreign_key' => $fkInfo,

                // Customization defaults
                'label' => $label,
                'control_type' => $controlType,
                'validation_rules' => implode('|', $validationRules),
                'required' => !$nullable && !$isPrimary && $default === null && !$isSystemCol,
                'placeholder' => "Enter {$label}",
                'default_value' => $default !== null ? (string) $default : '',
                'help_text' => '',
                'display_order' => $displayOrder++,
                'show_in_list' => !$isPassword && $name !== 'deleted_at',
                'show_in_create' => !$isSystemCol,
                'show_in_edit' => !$isSystemCol,
                'show_in_detail' => true,
                'searchable' => in_array($typeName, ['string', 'text', 'varchar', 'char'], true) && !$isPassword,
                'sortable' => in_array($typeName, ['integer', 'bigint', 'decimal', 'float', 'date', 'datetime', 'timestamp', 'string', 'varchar'], true),
                'filterable' => $fkInfo !== null || $typeName === 'boolean' || Str::contains($name, ['status', 'type', 'category', 'role', 'state']),
                'exportable' => !$isPassword,
                'read_only' => $isPrimary || $isSystemCol,
                'hidden_field' => $name === 'remember_token',
            ];
        }

        // Suggested relations based on foreign keys
        $suggestedRelations = [];
        foreach ($foreignKeyMap as $localCol => $fk) {
            $foreignTable = $fk['foreign_table'];
            $relatedModel = Str::studly(Str::singular($foreignTable));
            $suggestedRelations[] = [
                'type' => 'belongsTo',
                'name' => Str::camel(Str::singular(str_replace('_id', '', $localCol))),
                'target_table' => $foreignTable,
                'related_model' => "App\\Domains\\{$relatedModel}\\Models\\{$relatedModel}",
                'foreign_key' => $localCol,
                'owner_key' => $fk['foreign_column'],
                'display_column' => 'name',
                'component' => 'single_select',
            ];
        }

        $moduleName = Str::studly(Str::singular($tableName));
        $modelName = $moduleName;

        return [
            'connection' => $conn,
            'table_name' => $tableName,
            'module_name' => $moduleName,
            'model_name' => $modelName,
            'primary_key' => !empty($primaryKeys) ? $primaryKeys[0] : 'id',
            'columns' => $columns,
            'suggested_relations' => $suggestedRelations,
        ];
    }

    /**
     * Suggest the best control type based on column metadata.
     */
    protected function suggestControlType(string $colName, string $typeName, bool $isForeignKey): string
    {
        if ($isForeignKey) {
            return 'single_select';
        }

        if (in_array($colName, ['id', 'uuid', 'guid'], true)) {
            return 'uuid';
        }

        if (Str::contains($colName, ['password', 'secret'])) {
            return 'password';
        }

        if (Str::contains($colName, ['email'])) {
            return 'email';
        }

        if (Str::contains($colName, ['phone', 'mobile', 'cell', 'fax'])) {
            return 'phone';
        }

        if (Str::contains($colName, ['url', 'website', 'link'])) {
            return 'url';
        }

        if (Str::contains($colName, ['color', 'hex_color', 'theme'])) {
            return 'color_picker';
        }

        if (Str::contains($colName, ['icon', 'fa_icon'])) {
            return 'icon_picker';
        }

        if (Str::contains($colName, ['slug'])) {
            return 'slug';
        }

        if (Str::contains($colName, ['price', 'amount', 'cost', 'fee', 'total', 'salary', 'currency'])) {
            return 'currency';
        }

        if (Str::contains($colName, ['rating', 'score', 'stars'])) {
            return 'rating';
        }

        if (Str::contains($colName, ['image', 'avatar', 'photo', 'logo', 'thumbnail'])) {
            return Str::contains($colName, ['images', 'photos']) ? 'multiple_image_upload' : 'image_upload';
        }

        if (Str::contains($colName, ['file', 'attachment', 'document', 'pdf'])) {
            return Str::contains($colName, ['files', 'attachments']) ? 'multiple_file_upload' : 'file_upload';
        }

        if (Str::contains($colName, ['tags', 'keywords'])) {
            return 'tags_input';
        }

        if (Str::contains($colName, ['code', 'script', 'css', 'html', 'sql'])) {
            return 'code_editor';
        }

        if ($typeName === 'json' || Str::contains($colName, ['options', 'metadata', 'payload', 'settings', 'config'])) {
            return 'json_editor';
        }

        if (in_array($typeName, ['boolean', 'tinyint'], true) || Str::startsWith($colName, ['is_', 'has_', 'can_', 'should_'])) {
            return 'switch_toggle';
        }

        if (in_array($typeName, ['text', 'mediumtext', 'longtext'], true)) {
            return Str::contains($colName, ['description', 'content', 'body', 'article', 'details']) ? 'rich_text' : 'textarea';
        }

        if (in_array($typeName, ['date'], true)) {
            return 'date';
        }

        if (in_array($typeName, ['time'], true)) {
            return 'time';
        }

        if (in_array($typeName, ['datetime', 'timestamp'], true)) {
            return 'datetime';
        }

        if (in_array($typeName, ['decimal', 'float', 'double'], true)) {
            return 'decimal';
        }

        if (in_array($typeName, ['integer', 'bigint', 'smallint', 'tinyint'], true)) {
            return 'number';
        }

        return 'text';
    }

    /**
     * Suggest default validation rules for a column.
     *
     * @return array<int, string>
     */
    protected function suggestValidationRules(string $colName, string $typeName, bool $nullable, bool $isUnique): array
    {
        $rules = [];

        if ($nullable) {
            $rules[] = 'nullable';
        } else {
            $rules[] = 'required';
        }

        if (Str::contains($colName, 'email')) {
            $rules[] = 'email';
        } elseif (Str::contains($colName, 'url')) {
            $rules[] = 'url';
        } elseif (in_array($typeName, ['integer', 'bigint', 'smallint'], true)) {
            $rules[] = 'integer';
        } elseif (in_array($typeName, ['decimal', 'float', 'double'], true)) {
            $rules[] = 'numeric';
        } elseif ($typeName === 'boolean') {
            $rules[] = 'boolean';
        } elseif (in_array($typeName, ['date', 'datetime', 'timestamp'], true)) {
            $rules[] = 'date';
        } else {
            $rules[] = 'string';
            $rules[] = 'max:255';
        }

        return $rules;
    }
}
