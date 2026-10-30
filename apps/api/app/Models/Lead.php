<?php

namespace App\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

/**
 * Class Lead
 * Represents prospective clients in the agency sales pipeline.
 */
class Lead extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'crm_leads';

    protected $fillable = [
        'company_name',
        'contact_person',
        'first_name',
        'last_name',
        'salutation',
        'designation',
        'email',
        'phone',
        'alternate_phone',
        'whatsapp',
        'consumer_number',
        'discom',
        'sanctioned_load',
        'monthly_bill',
        'solar_capacity_kw',
        'subsidy_amount',
        'product_group',
        'product_group_id',
        'customer_group',
        'customer_group_id',
        'lead_potential',
        'lead_type',
        'roof_type',
        'society_name',
        'approval_status',
        'franchise',
        'lead_list',
        'website',
        'industry',
        'products_services',
        'lead_source',
        'scraped_by',
        'location',
        'area_locality',
        'address_line_1',
        'address_line_2',
        'full_address',
        'pincode',
        'city',
        'state',
        'country',
        'rating',
        'total_reviews',
        'requirement',
        'google_maps_link',
        'estimated_budget',
        'priority',
        'status',
        'stage',
        'tags',
        'assigned_to_user_id',
        'last_contact_date',
        'next_followup_date',
        'follow_up_type',
        'followup_notes',
        'notes',
    ];

    protected $casts = [
        'estimated_budget' => 'decimal:2',
        'sanctioned_load' => 'decimal:2',
        'monthly_bill' => 'decimal:2',
        'solar_capacity_kw' => 'decimal:2',
        'subsidy_amount' => 'decimal:2',
        'last_contact_date' => 'date',
        'next_followup_date' => 'date',
    ];

    protected $appends = ['days_in_pipeline'];

    /**
     * Assigned agency developer / account lead.
     */
    public function assignedDeveloper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Associated Solar Product Group.
     */
    public function solarProductGroup(): BelongsTo
    {
        return $this->belongsTo(SolarProductGroup::class, 'product_group_id');
    }

    /**
     * Associated Solar Customer Group.
     */
    public function solarCustomerGroup(): BelongsTo
    {
        return $this->belongsTo(SolarCustomerGroup::class, 'customer_group_id');
    }

    /**
     * Converted client record if won.
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'lead_id');
    }

    /**
     * Follow-ups scheduled for this lead.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'lead_id');
    }

    /**
     * Polymorphic activities.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Polymorphic notes (CRM Timeline notes).
     */
    public function leadNotes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /**
     * Alias for polymorphic notes.
     */
    public function notesList(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /**
     * Calculate days in pipeline.
     */
    public function getDaysInPipelineAttribute(): int
    {
        if (!$this->created_at) return 0;
        return (int) $this->created_at->diffInDays(Carbon::now());
    }
}
