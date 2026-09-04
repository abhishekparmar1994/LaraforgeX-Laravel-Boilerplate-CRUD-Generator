<?php

declare(strict_types=1);

namespace App\Agents\Crm\Contracts;

use App\Agents\Crm\AgentContext;

/**
 * Contract defining standard behavior and metadata for CRM Agent Tools.
 */
interface AgentToolInterface
{
    /**
     * Unique identifier for the tool (e.g. 'lead_statistics').
     */
    public function getName(): string;

    /**
     * Human and LLM readable explanation of what the tool accomplishes.
     */
    public function getDescription(): string;

    /**
     * JSON Schema object describing the input parameters expected by the tool.
     *
     * @return array<string, mixed>
     */
    public function getParametersSchema(): array;

    /**
     * True if this is a read-only query; false if it mutates data.
     */
    public function isReadOnly(): bool;

    /**
     * Optional Spatie permission required to execute this tool.
     */
    public function getRequiredPermission(): ?string;

    /**
     * Execute the tool with validated arguments under the caller's secure context.
     *
     * @param array<string, mixed> $arguments
     * @return array<string, mixed>
     */
    public function execute(array $arguments, AgentContext $context): array;
}
