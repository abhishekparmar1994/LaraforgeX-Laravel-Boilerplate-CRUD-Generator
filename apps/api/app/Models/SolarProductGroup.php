<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SolarProductGroup
 * Eloquent model representing solar system product classifications.
 */
class SolarProductGroup extends Model
{
    protected $table = 'crm_solar_product_groups';

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    /**
     * Purpose: Retrieve customer groups associated with this product group.
     * Return: HasMany
     */
    public function customerGroups(): HasMany
    {
        return $this->hasMany(SolarCustomerGroup::class, 'product_group_id');
    }

    /**
     * Purpose: Retrieve leads categorized under this product group.
     * Return: HasMany
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'product_group_id');
    }
}
