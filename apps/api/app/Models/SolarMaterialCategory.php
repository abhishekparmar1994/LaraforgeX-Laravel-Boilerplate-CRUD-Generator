<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SolarMaterialCategory
 * Represents material categories like Panels, Inverters, Structures, Cables.
 */
class SolarMaterialCategory extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'crm_solar_material_categories';

    protected $fillable = [
        'title',
        'slug',
        'unit',
        'default_gst_percent',
        'include_in_solar_kit',
        'display_on_invoice',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'default_gst_percent' => 'decimal:2',
        'include_in_solar_kit' => 'boolean',
        'display_on_invoice' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Materials belonging to this category.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(SolarMaterial::class, 'category_id');
    }
}
