<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasUUID;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
        'parent_id',
    ];

    /**
     * Relationship: The parent role.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Relationship: Immediate child roles.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Retrieve all child roles recursively (depth-first).
     */
    public function getAllChildren(): Collection
    {
        $children = collect();

        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }

        return $children;
    }
}
