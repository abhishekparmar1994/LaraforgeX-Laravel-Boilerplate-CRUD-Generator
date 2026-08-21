<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentSupplie extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'equipment_supplies';

    protected $fillable = [
        'title',
        'image'
    ];

    protected $casts = [
        
    ];

    public function relation(): \Illuminate\Database\Eloquent\Relations\morphTo
    {
        return $this->morphTo();
    }
}