<?php

declare(strict_types=1);

namespace App\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents an individual message in an AI Agent conversation.
 */
class AgentMessage extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'agent_messages';

    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'tool_name',
        'tool_arguments',
        'tool_result',
    ];

    protected $casts = [
        'tool_arguments' => 'array',
        'tool_result'    => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AgentConversation::class, 'conversation_id');
    }
}
