<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class BackupController extends Controller
{
    private string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
        if (!File::exists($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }
    }

    /**
     * List all database backups.
     */
    public function index(): JsonResponse
    {
        $files = File::files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql' || $file->getExtension() === 'gz') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size_bytes' => $file->getSize(),
                    'size_human' => $this->formatBytes($file->getSize()),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    'download_url' => "/api/v1/backups/download/" . urlencode($file->getFilename()),
                ];
            }
        }

        // Sort latest first
        usort($backups, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));

        return response()->json([
            'success' => true,
            'data' => $backups,
        ]);
    }

    /**
     * Generate a complete MySQL database SQL dump backup file.
     */
    public function generate(): JsonResponse
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $filename = "backup_{$dbName}_" . date('Y_m_d_His') . ".sql";
            $filePath = $this->backupDir . '/' . $filename;

            $tables = DB::select('SHOW TABLES');
            $dbKey = "Tables_in_" . $dbName;

            $sqlDump = "-- --------------------------------------------------------\n";
            $sqlDump .= "-- LaraforgeX MySQL Database Backup Dump\n";
            $sqlDump .= "-- Database: {$dbName}\n";
            $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sqlDump .= "-- --------------------------------------------------------\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $tableObj) {
                $tableName = $tableObj->$dbKey ?? current((array)$tableObj);
                if (!$tableName) continue;

                // Structure
                $createTableRes = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableSql = $createTableRes[0]->{'Create Table'} ?? '';

                $sqlDump .= "-- Table structure for `{$tableName}`\n";
                $sqlDump .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sqlDump .= $createTableSql . ";\n\n";

                // Data
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sqlDump .= "-- Dumping data for table `{$tableName}`\n";
                    foreach ($rows as $row) {
                        $rowArr = (array)$row;
                        $keys = array_map(fn($k) => "`{$k}`", array_keys($rowArr));
                        $values = array_map(function ($val) {
                            if (is_null($val)) return "NULL";
                            if (is_bool($val)) return $val ? '1' : '0';
                            return DB::getPdo()->quote((string)$val);
                        }, array_values($rowArr));

                        $sqlDump .= "INSERT INTO `{$tableName}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sqlDump .= "\n";
                }
            }

            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

            File::put($filePath, $sqlDump);

            return response()->json([
                'success' => true,
                'message' => "Database backup '{$filename}' generated successfully!",
                'data' => [
                    'filename' => $filename,
                    'size_human' => $this->formatBytes(filesize($filePath)),
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate database backup: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download a specific backup file.
     */
    public function download(string $filename): BinaryFileResponse|JsonResponse
    {
        $filePath = $this->backupDir . '/' => basename($filename);
        $fullPath = $this->backupDir . '/' . basename($filename);

        if (!File::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Backup file not found.',
            ], 404);
        }

        return response()->download($fullPath);
    }

    /**
     * Delete a specific backup file.
     */
    public function destroy(string $filename): JsonResponse
    {
        $fullPath = $this->backupDir . '/' . basename($filename);

        if (!File::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Backup file not found.',
            ], 404);
        }

        File::delete($fullPath);

        return response()->json([
            'success' => true,
            'message' => "Backup file '{$filename}' deleted successfully.",
        ]);
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
