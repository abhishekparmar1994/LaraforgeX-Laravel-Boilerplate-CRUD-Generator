<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\User\Models\User;
use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Audit and execution log for every tool call dispatched by the AI Agent.
 */
class AgentToolCall extends Model
{
    use HasFactory, HasUUID;

    public $timestamps = false;

    protected $table = 'agent_tool_calls';

    protected $fillable = [
        'conversation_id',
        'message_id',
        'user_id',
        'tool_name',
        'arguments',
        'result',
        'status',
        'execution_time_ms',
        'created_at',
    ];

    protected $casts = [
        'arguments'  => 'array',
        'result'     => 'array',
        'created_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AgentConversation::class, 'conversation_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
