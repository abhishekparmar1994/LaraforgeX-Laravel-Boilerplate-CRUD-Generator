<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTest extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];


}