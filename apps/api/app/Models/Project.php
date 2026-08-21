<?php

namespace App\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

/**
 * Class Project
 * Represents a website development or web engineering project.
 */
class Project extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'crm_projects';

    protected $fillable = [
        'client_id',
        'company_id',
        'name',
        'project_type',
        'description',
        'start_date',
        'deadline',
        'estimated_hours',
        'actual_hours',
        'budget',
        'status',
        'priority',
        'project_manager_id',
        'progress',
        'repository',
        'staging_url',
        'production_url',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'budget' => 'decimal:2',
        'estimated_hours' => 'decimal:1',
        'actual_hours' => 'decimal:1',
        'progress' => 'integer',
    ];

    protected $appends = ['is_overdue'];

    /**
     * Parent client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Parent company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Associated website.
     */
    public function website(): HasOne
    {
        return $this->hasOne(Website::class, 'project_id');
    }

    /**
     * Project Manager.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    /**
     * Assigned developers.
     */
    public function developers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'crm_project_members', 'project_id', 'user_id')
            ->withPivot('role_in_project')
            ->withTimestamps();
    }

    /**
     * Project milestones.
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class, 'project_id')->orderBy('due_date');
    }

    /**
     * Project tasks.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    /**
     * Project payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'project_id');
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
     * Check if project is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->deadline || in_array($this->status, ['completed', 'launch'])) {
            return false;
        }
        return Carbon::now()->isAfter($this->deadline);
    }
}
