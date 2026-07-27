<?php

declare(strict_types=1);

namespace App\Shared\Traits;

use Illuminate\Support\Str;

trait HasUUID
{
    /**
     * Boot the trait to auto-generate UUID primary keys.
     */
    protected static function bootHasUUID(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
