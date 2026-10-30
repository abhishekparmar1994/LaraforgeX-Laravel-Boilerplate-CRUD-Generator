<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SolarMaterial
 * Represents an individual equipment catalog item.
 */
class SolarMaterial extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'crm_solar_materials';

    protected $fillable = [
        'category_id',
        'company_id',
        'name',
        'model_number',
        'specifications',
        'unit',
        'purchase_price',
        'selling_price',
        'gst_percent',
        'warranty_years',
        'in_stock',
        'is_active',
        'description',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'gst_percent' => 'decimal:2',
        'warranty_years' => 'integer',
        'in_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Material category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SolarMaterialCategory::class, 'category_id');
    }

    /**
     * Brand / Manufacturer.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(SolarMaterialCompany::class, 'company_id');
    }

    /**
     * Project BOM allocations.
     */
    public function bomAllocations(): HasMany
    {
        return $this->hasMany(SolarProjectBom::class, 'material_id');
    }
}
