<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a persistent chat conversation between a CRM user and the AI Agent.
 */
class AgentConversation extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'agent_conversations';

    protected $fillable = [
        'user_id',
        'title',
        'status',
        'current_route',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AgentMessage::class, 'conversation_id')->orderBy('created_at', 'asc');
    }

    public function toolCalls(): HasMany
    {
        return $this->hasMany(AgentToolCall::class, 'conversation_id')->orderBy('created_at', 'asc');
    }
}
