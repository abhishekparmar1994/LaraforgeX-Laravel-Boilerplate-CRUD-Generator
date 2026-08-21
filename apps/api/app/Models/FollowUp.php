<?php

namespace App\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Class FollowUp
 * Represents scheduled communications (calls, meetings, demos, etc.).
 */
class FollowUp extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'crm_followups';

    protected $fillable = [
        'lead_id',
        'client_id',
        'contact_id',
        'assigned_to_user_id',
        'scheduled_at',
        'follow_up_type',
        'notes',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    protected $appends = ['is_today', 'is_overdue'];

    /**
     * Associated lead.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    /**
     * Associated client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Associated contact.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * Assigned developer.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Check if follow-up is scheduled for today.
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->scheduled_at && $this->scheduled_at->isToday();
    }

    /**
     * Check if follow-up is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at && $this->scheduled_at->isPast() && !$this->scheduled_at->isToday();
    }
}
