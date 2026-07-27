<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
use Throwable;

class InstallController extends Controller
{
    /**
     * Display the 4-step web installer page.
     */
    public function index(): View
    {
        return view('installer.index');
    }

    /**
     * Step 1: Check server PHP version and required extensions.
     */
    public function checkRequirements(): JsonResponse
    {
        $minPhpVersion = '8.2.0';
        $currentPhpVersion = PHP_VERSION;
        $phpSatisfied = version_compare($currentPhpVersion, $minPhpVersion, '>=');

        $requiredExtensions = [
            'pdo',
            'openssl',
            'mbstring',
            'tokenizer',
            'xml',
            'ctype',
            'json',
            'bcmath',
            'fileinfo',
            'cURL',
        ];

        $extensionResults = [];
        $allExtensionsSatisfied = true;

        foreach ($requiredExtensions as $ext) {
            $isLoaded = extension_loaded($ext);
            if (!$isLoaded) {
                $allExtensionsSatisfied = false;
            }
            $extensionResults[] = [
                'name' => $ext,
                'satisfied' => $isLoaded,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'php' => [
                    'current' => $currentPhpVersion,
                    'minimum' => $minPhpVersion,
                    'satisfied' => $phpSatisfied,
                ],
                'extensions' => $extensionResults,
                'is_ready' => $phpSatisfied && $allExtensionsSatisfied,
            ],
        ]);
    }

    /**
     * Step 2: Check folder write permissions.
     */
    public function checkPermissions(): JsonResponse
    {
        $directories = [
            'storage/app' => storage_path('app'),
            'storage/framework' => storage_path('framework'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $results = [];
        $allWritable = true;

        foreach ($directories as $name => $path) {
            $isWritable = is_dir($path) && is_writable($path);
            if (!$isWritable) {
                $allWritable = false;
            }
            $results[] = [
                'name' => $name,
                'path' => $path,
                'writable' => $isWritable,
                'permission' => is_dir($path) ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'directories' => $results,
                'is_ready' => $allWritable,
            ],
        ]);
    }

    /**
     * Step 3: Test Database Connection credentials with auto database creation.
     */
    public function testDatabase(Request $request): JsonResponse
    {
        $request->validate([
            'db_host' => ['required', 'string'],
            'db_port' => ['required'],
            'db_name' => ['required', 'string'],
            'db_user' => ['required', 'string'],
            'db_pass' => ['nullable', 'string'],
        ]);

        $host = $request->input('db_host');
        $port = (int) $request->input('db_port');
        $database = $request->input('db_name');
        $username = $request->input('db_user');
        $password = (string) $request->input('db_pass', '');

        try {
            $result = $this->ensureDatabaseExists($host, $port, $username, $password, $database);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'created' => $result['created'],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Step 4: Run installation migrations, update .env, create Admin Account, and complete installation.
     */
    public function install(Request $request): JsonResponse
    {
        $request->validate([
            'app_name' => ['required', 'string'],
            'app_url' => ['required', 'url'],
            'db_host' => ['required', 'string'],
            'db_port' => ['required'],
            'db_name' => ['required', 'string'],
            'db_user' => ['required', 'string'],
            'db_pass' => ['nullable', 'string'],
            'admin_name' => ['required', 'string'],
            'admin_email' => ['required', 'email'],
            'admin_password' => ['required', 'string', 'min:8'],
        ]);

        try {
            // 0. Ensure target database exists (auto-create if missing)
            $this->ensureDatabaseExists(
                $request->input('db_host'),
                (int) $request->input('db_port'),
                $request->input('db_user'),
                (string) $request->input('db_pass', ''),
                $request->input('db_name')
            );

            // 1. Update .env settings
            $this->updateEnvFile([
                'APP_NAME' => '"' . $request->input('app_name') . '"',
                'APP_URL' => $request->input('app_url'),
                'DB_HOST' => $request->input('db_host'),
                'DB_PORT' => $request->input('db_port'),
                'DB_DATABASE' => $request->input('db_name'),
                'DB_USERNAME' => $request->input('db_user'),
                'DB_PASSWORD' => '"' . $request->input('db_pass', '') . '"',
            ]);

            // 2. Set dynamic database config for current process
            Config::set('database.connections.mysql.host', $request->input('db_host'));
            Config::set('database.connections.mysql.port', $request->input('db_port'));
            Config::set('database.connections.mysql.database', $request->input('db_name'));
            Config::set('database.connections.mysql.username', $request->input('db_user'));
            Config::set('database.connections.mysql.password', $request->input('db_pass', ''));
            DB::purge('mysql');

            // 3. Run Key Generation, Migrations & Database Seeders
            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('migrate:fresh', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);

            // 4. Update Admin User Credentials
            $adminUser = User::where('email', 'admin@laraforgex.com')->first();
            if ($adminUser) {
                $adminUser->update([
                    'name' => $request->input('admin_name'),
                    'email' => $request->input('admin_email'),
                    'password' => Hash::make($request->input('admin_password')),
                    'email_verified_at' => now(),
                ]);
            } else {
                User::create([
                    'name' => $request->input('admin_name'),
                    'email' => $request->input('admin_email'),
                    'password' => Hash::make($request->input('admin_password')),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            // 5. Create installation completion lock file
            file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));

            return response()->json([
                'success' => true,
                'message' => 'LaraforgeX installed successfully!',
                'redirect_url' => '/admin/login',
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Installation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper to verify database connection and auto-create the database if it doesn't exist.
     *
     * @return array{created: bool, message: string}
     */
    private function ensureDatabaseExists(string $host, int $port, string $username, string $password, string $database): array
    {
        // 1. First attempt to connect to the database directly
        Config::set('database.connections.installer_check', [
            'driver' => 'mysql',
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ]);

        try {
            DB::connection('installer_check')->getPdo();
            return [
                'created' => false,
                'message' => "Database '{$database}' connection established successfully!",
            ];
        } catch (Throwable $e) {
            $errorMsg = $e->getMessage();
            $isUnknownDb = str_contains(strtolower($errorMsg), 'unknown database') || str_contains($errorMsg, '1049');

            if (!$isUnknownDb) {
                throw $e;
            }

            // 2. Connect to MySQL server without selecting a specific database to create it
            Config::set('database.connections.installer_root', [
                'driver' => 'mysql',
                'host' => $host,
                'port' => $port,
                'database' => null,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
            ]);

            DB::connection('installer_root')->statement("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            // 3. Re-verify connection to the newly created database
            DB::connection('installer_check')->getPdo();

            return [
                'created' => true,
                'message' => "Database '{$database}' did not exist and was created automatically! Connection successful.",
            ];
        }
    }

    /**
     * Helper to update key-value pairs inside .env file.
     */
    private function updateEnvFile(array $data): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_get_contents($envPath);
        file_put_contents($envPath, $envContent);
    }
}
