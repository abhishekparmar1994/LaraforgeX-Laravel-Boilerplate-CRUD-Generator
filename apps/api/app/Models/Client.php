<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Client
 * Represents an active client account in the agency CRM.
 */
class Client extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'crm_clients';

    protected $fillable = [
        'company_id',
        'primary_contact_id',
        'lead_id',
        'client_code',
        'name',
        'email',
        'phone',
        'whatsapp',
        'address',
        'industry',
        'client_since',
        'status',
        'notes',
    ];

    protected $casts = [
        'client_since' => 'date',
    ];

    protected $appends = ['total_revenue', 'pending_payment'];

    /**
     * Parent company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Primary contact person.
     */
    public function primaryContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'primary_contact_id');
    }

    /**
     * Original lead record.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    /**
     * Projects associated with this client.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /**
     * Websites built for this client.
     */
    public function websites(): HasMany
    {
        return $this->hasMany(Website::class, 'client_id');
    }

    /**
     * Payments / invoices for this client.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'client_id');
    }

    /**
     * Follow-ups for this client.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'client_id');
    }

    /**
     * Polymorphic activities.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Polymorphic notes.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /**
     * Total revenue collected.
     */
    public function getTotalRevenueAttribute(): float
    {
        return (float) $this->payments()->where('status', 'paid')->sum('amount');
    }

    /**
     * Pending payment balance.
     */
    public function getPendingPaymentAttribute(): float
    {
        return (float) $this->payments()->whereIn('status', ['pending', 'partial', 'overdue'])->sum('amount');
    }
}
