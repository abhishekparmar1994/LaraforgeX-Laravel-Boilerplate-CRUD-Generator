<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use App\Shared\Traits\HasUUID;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUUID;

    protected $fillable = [
        'name',
        'guard_name',
        'group',
        'description',
    ];
}
