<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Class Payment
 * Financial tracking for website projects and client invoices.
 */
class Payment extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'crm_payments';

    protected $fillable = [
        'client_id',
        'project_id',
        'invoice_number',
        'amount',
        'payment_date',
        'due_date',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'due_date' => 'date',
    ];

    protected $appends = ['is_overdue'];

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
     * Check if payment is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'paid' || !$this->due_date) {
            return false;
        }
        return Carbon::now()->startOfDay()->isAfter($this->due_date);
    }
}
