<?php

declare(strict_types=1);

namespace App\Domains\Media\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'media';

    protected $fillable = [
        'folder_id',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'path',
        'size',
        'user_id',
        'custom_properties',
    ];

    protected $casts = [
        'custom_properties' => 'array',
    ];

    protected $appends = [
        'url',
    ];

    /**
     * Relationship: The folder it belongs to.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    /**
     * Relationship: The user who uploaded this media.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Resolve the media file URL. Supports local and cloud disks.
     */
    public function getUrlAttribute(): string
    {
        try {
            if ($this->disk === 'local') {
                return asset(Storage::url($this->path));
            }
            
            // Generate temporary presigned URLs for cloud storage S3/R2
            return Storage::disk($this->disk)->temporaryUrl($this->path, now()->addMinutes(60));
        } catch (\Throwable) {
            // Fallback to simple path if temporaryUrl is unsupported by custom adapter
            return Storage::disk($this->disk)->url($this->path);
        }
    }
}