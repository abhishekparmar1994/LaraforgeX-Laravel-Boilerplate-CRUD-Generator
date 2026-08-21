<?php

declare(strict_types=1);

namespace App\Domains\Settings\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'is_encrypted',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Get value attribute. Auto-decrypts if encrypted.
     */
    public function getValueAttribute($value): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($this->is_encrypted) {
            try {
                return decrypt($value);
            } catch (\Throwable) {
                return $value;
            }
        }

        // Check if value is JSON
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $value;
    }

    /**
     * Set value attribute. Auto-encrypts if marked as encrypted.
     */
    public function setValueAttribute(mixed $value): void
    {
        if ($value === null) {
            $this->attributes['value'] = null;
            return;
        }

        $serializedValue = is_array($value) || is_object($value) 
            ? json_encode($value) 
            : (string) $value;

        if ($this->is_encrypted) {
            $this->attributes['value'] = encrypt($serializedValue);
        } else {
            $this->attributes['value'] = $serializedValue;
        }
    }

    /**
     * Helper to retrieve a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }
        return $setting->value ?? $default;
    }
}