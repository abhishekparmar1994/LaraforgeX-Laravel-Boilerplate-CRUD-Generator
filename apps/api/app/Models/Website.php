<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

/**
 * Class Website
 * Represents a website or web application hosted and maintained by the agency.
 */
class Website extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'crm_websites';

    protected $fillable = [
        'company_id',
        'client_id',
        'project_id',
        'name',
        'title',
        'url',
        'domain',
        'domain_provider',
        'domain_expiry_date',
        'hosting_provider',
        'hosting_expiry_date',
        'technology',
        'framework',
        'cms',
        'repository_url',
        'github_url',
        'server_ip',
        'deployment_platform',
        'database_type',
        'ssl_status',
        'ssl_expiry_date',
        'status',
        'design_progress',
        'dev_progress',
        'testing_progress',
        'notes',
    ];

    protected $casts = [
        'domain_expiry_date' => 'date',
        'hosting_expiry_date' => 'date',
        'ssl_expiry_date' => 'date',
        'design_progress' => 'integer',
        'dev_progress' => 'integer',
        'testing_progress' => 'integer',
    ];

    protected $appends = ['overall_progress'];

    /**
     * Associated company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Associated client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Associated project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Secure credentials vault.
     */
    public function credentials(): HasMany
    {
        return $this->hasMany(WebsiteCredential::class, 'website_id');
    }

    /**
     * Tasks for this website.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'website_id');
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
     * Calculate overall progress percentage.
     */
    public function getOverallProgressAttribute(): int
    {
        return (int) round(($this->design_progress + $this->dev_progress + $this->testing_progress) / 3);
    }

    /**
     * Check if domain expires within given days.
     */
    public function isDomainExpiringSoon(int $days = 14): bool
    {
        if (!$this->domain_expiry_date) return false;
        $diff = Carbon::now()->diffInDays($this->domain_expiry_date, false);
        return $diff >= 0 && $diff <= $days;
    }

    /**
     * Check if hosting expires within given days.
     */
    public function isHostingExpiringSoon(int $days = 14): bool
    {
        if (!$this->hosting_expiry_date) return false;
        $diff = Carbon::now()->diffInDays($this->hosting_expiry_date, false);
        return $diff >= 0 && $diff <= $days;
    }
}
