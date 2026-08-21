<?php

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WebsiteCredential
 * Encrypted credentials vault for websites (CMS, SSH, DB, API Keys).
 */
class WebsiteCredential extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'crm_website_credentials';

    protected $fillable = [
        'website_id',
        'credential_type',
        'title',
        'username',
        'password_encrypted',
        'host_or_url',
        'port',
        'notes',
    ];

    protected $appends = ['masked_password'];

    /**
     * Parent website.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Masked password presentation.
     */
    public function getMaskedPasswordAttribute(): string
    {
        return '••••••••••••';
    }
}
