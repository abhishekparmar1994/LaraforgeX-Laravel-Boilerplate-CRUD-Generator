<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Company
 * Represents client organizations and business entities in the agency CRM.
 */
class Company extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'crm_companies';

    protected $fillable = [
        'name',
        'industry',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'gst_vat_number',
        'company_type',
        'status',
        'notes',
    ];

    /**
     * Contacts belonging to this company.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'company_id');
    }

    /**
     * Client accounts for this company.
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'company_id');
    }

    /**
     * Projects for this company.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'company_id');
    }

    /**
     * Websites owned by this company.
     */
    public function websites(): HasMany
    {
        return $this->hasMany(Website::class, 'company_id');
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
