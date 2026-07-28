<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;

$adminRole = Role::where('name', 'administrator')->first();
$developerRole = Role::where('name', 'developer')->first();

// Fetch EVERY SINGLE permission ID in the permissions table
$allPermissionNames = Permission::pluck('name')->toArray();

if ($adminRole) {
    $adminRole->syncPermissions($allPermissionNames);
    echo "SUCCESS: Synced " . count($allPermissionNames) . " permissions to administrator role!\n";
}

if ($developerRole) {
    $developerRole->syncPermissions($allPermissionNames);
    echo "SUCCESS: Synced " . count($allPermissionNames) . " permissions to developer role!\n";
}
