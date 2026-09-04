<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agent_conversations')) {
            Schema::create('agent_conversations', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
                $table->string('title')->default('New Conversation');
                $table->string('status')->default('active'); // active, archived, closed
                $table->string('current_route')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('agent_messages')) {
            Schema::create('agent_messages', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('conversation_id')->constrained('agent_conversations')->onDelete('cascade');
                $table->string('role'); // user, assistant, system, tool
                $table->longText('content')->nullable();
                $table->string('tool_name')->nullable();
                $table->json('tool_arguments')->nullable();
                $table->json('tool_result')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('agent_tool_calls')) {
            Schema::create('agent_tool_calls', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('conversation_id')->constrained('agent_conversations')->onDelete('cascade');
                $table->uuid('message_id')->nullable();
                $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
                $table->string('tool_name')->index();
                $table->json('arguments')->nullable();
                $table->json('result')->nullable();
                $table->string('status')->default('success'); // success, failed
                $table->integer('execution_time_ms')->default(0);
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_tool_calls');
        Schema::dropIfExists('agent_messages');
        Schema::dropIfExists('agent_conversations');
    }
};
