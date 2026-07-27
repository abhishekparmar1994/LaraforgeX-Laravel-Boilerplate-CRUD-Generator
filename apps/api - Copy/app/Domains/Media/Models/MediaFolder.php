<?php

declare(strict_types=1);

namespace App\Domains\Media\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFolder extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'media_folders';

    protected $fillable = [
        'name',
        'parent_id',
        'user_id',
    ];

    /**
     * Relationship: Parent folder.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Relationship: Child folders.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Relationship: Media files inside this folder.
     */
    public function mediaFiles(): HasMany
    {
        return $this->hasMany(Media::class, 'folder_id');
    }
}
