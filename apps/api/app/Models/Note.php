<?php

namespace App\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Note
 * Polymorphic notes linked to Leads, Clients, Projects, Websites, and Contacts.
 */
class Note extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'crm_notes';

    protected $fillable = [
        'user_id',
        'notable_type',
        'notable_id',
        'title',
        'content',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    /**
     * User author.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Parent polymorphic entity.
     */
    public function notable(): MorphTo
    {
        return $this->morphTo();
    }
}
