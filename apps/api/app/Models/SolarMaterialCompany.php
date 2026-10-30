<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SolarMaterialCompany
 * Represents solar brands and manufacturers (Waaree, Adani, Sungrow, etc.).
 */
class SolarMaterialCompany extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'crm_solar_material_companies';

    protected $fillable = [
        'name',
        'brand_tier',
        'website',
        'contact_person',
        'contact_phone',
        'contact_email',
        'notes',
    ];

    /**
     * Materials manufactured or distributed by this company.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(SolarMaterial::class, 'company_id');
    }
}
