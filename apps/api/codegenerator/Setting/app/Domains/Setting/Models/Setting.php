<?php

declare(strict_types=1);

namespace App\Domains\Setting\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'is_encrypted'
    ];

    protected $casts = [
        'is_encrypted' => 'boolean'
    ];

    public function id(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(App\Domains\Media\Models\Media::class, 'id', 'folder_id');
    }
}