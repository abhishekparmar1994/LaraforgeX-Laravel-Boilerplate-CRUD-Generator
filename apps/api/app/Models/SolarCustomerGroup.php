<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SolarCustomerGroup
 * Eloquent model representing customer consumer segments within solar product groups.
 */
class SolarCustomerGroup extends Model
{
    protected $table = 'crm_solar_customer_groups';

    protected $fillable = [
        'product_group_id',
        'name',
        'code',
        'description',
        'status',
    ];

    /**
     * Purpose: Retrieve the parent product group for this customer segment.
     * Return: BelongsTo
     */
    public function productGroup(): BelongsTo
    {
        return $this->belongsTo(SolarProductGroup::class, 'product_group_id');
    }

    /**
     * Purpose: Retrieve leads categorized under this customer group.
     * Return: HasMany
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'customer_group_id');
    }
}
