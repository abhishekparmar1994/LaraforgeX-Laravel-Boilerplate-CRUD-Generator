<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SolarProjectBom
 * Represents an itemized Bill of Materials allocated to a solar project.
 */
class SolarProjectBom extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'crm_solar_project_bom';

    protected $fillable = [
        'project_id',
        'material_id',
        'quantity',
        'unit_rate',
        'gst_percent',
        'total_cost',
        'status',
        'serial_numbers',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_rate' => 'decimal:2',
        'gst_percent' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Parent solar project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Associated catalog material.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(SolarMaterial::class, 'material_id');
    }
}
