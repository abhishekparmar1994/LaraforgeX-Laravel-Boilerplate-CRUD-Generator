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
 * Class Contact
 * Represents individual stakeholders and contact persons.
 */
class Contact extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'crm_contacts';

    protected $fillable = [
        'company_id',
        'contact_type',
        'first_name',
        'last_name',
        'job_title',
        'email',
        'phone',
        'whatsapp',
        'linkedin',
        'birthday',
        'preferred_contact_method',
        'notes',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    protected $appends = ['name'];

    /**
     * Get full name.
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Parent company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Follow-ups for this contact.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'contact_id');
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
}
