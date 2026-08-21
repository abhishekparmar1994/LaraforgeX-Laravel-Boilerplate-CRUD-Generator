<?php

namespace App\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Activity
 * Polymorphic activity timeline logging across all CRM entities.
 */
class Activity extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'crm_activities';

    protected $fillable = [
        'user_id',
        'subject_type',
        'subject_id',
        'type',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * User who triggered this activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Target polymorphic entity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
