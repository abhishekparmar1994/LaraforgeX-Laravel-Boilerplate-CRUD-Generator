<?php

namespace App\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

/**
 * Class Task
 * Represents actionable tasks and developer todos.
 */
class Task extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'crm_tasks';

    protected $fillable = [
        'project_id',
        'website_id',
        'client_id',
        'assigned_to_user_id',
        'created_by_user_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'estimated_hours',
        'actual_hours',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'estimated_hours' => 'decimal:1',
        'actual_hours' => 'decimal:1',
    ];

    protected $appends = ['is_overdue'];

    /**
     * Parent project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Associated website.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Associated client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Developer assigned to this task.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Task creator.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
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
     * Check if task is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'completed' || !$this->due_date) {
            return false;
        }
        return Carbon::now()->startOfDay()->isAfter($this->due_date);
    }
}
