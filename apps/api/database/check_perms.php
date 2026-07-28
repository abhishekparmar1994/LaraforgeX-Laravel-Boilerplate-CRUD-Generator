<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;
use App\Domains\User\Models\User;

echo "=== ALL PERMISSIONS IN DB ===\n";
foreach (Permission::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Guard: {$p->guard_name}\n";
}

echo "\n=== ROLES & PERMISSIONS MAPPING ===\n";
foreach (Role::with('permissions')->get() as $r) {
    echo "Role: {$r->name} ({$r->id})\n";
    foreach ($r->permissions as $p) {
        echo "  - {$p->name} ({$p->id})\n";
    }
}

echo "\n=== ADMIN USER PERMISSIONS (getAllPermissions) ===\n";
$admin = User::where('email', 'admin@laraforgex.com')->first();
if ($admin) {
    echo "User: {$admin->name} ({$admin->email})\n";
    echo "Roles: " . implode(', ', $admin->getRoleNames()->toArray()) . "\n";
    echo "All Permissions:\n";
    foreach ($admin->getAllPermissions() as $p) {
        echo "  - {$p->name}\n";
    }
}
