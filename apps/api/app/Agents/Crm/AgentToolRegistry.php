<?php

declare(strict_types=1);

namespace App\Agents\Crm;

use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Agents\Crm\Tools\GetClientTool;
use App\Agents\Crm\Tools\GetCompanyTool;
use App\Agents\Crm\Tools\GetContactTool;
use App\Agents\Crm\Tools\GetLeadTool;
use App\Agents\Crm\Tools\LeadStatisticsTool;
use App\Agents\Crm\Tools\PaymentStatisticsTool;
use App\Agents\Crm\Tools\PipelineStatisticsTool;
use App\Agents\Crm\Tools\ProjectStatisticsTool;
use App\Agents\Crm\Tools\QueryDatabaseTool;
use App\Agents\Crm\Tools\RecentActivityTool;
use App\Agents\Crm\Tools\SearchClientsTool;
use App\Agents\Crm\Tools\SearchCompaniesTool;
use App\Agents\Crm\Tools\SearchContactsTool;
use App\Agents\Crm\Tools\SearchFollowupsTool;
use App\Agents\Crm\Tools\SearchLeadsTool;
use App\Agents\Crm\Tools\SearchProjectsTool;
use App\Agents\Crm\Tools\SearchTasksTool;
use App\Agents\Crm\Tools\TaskStatisticsTool;
use App\Agents\Crm\Tools\TopClientsTool;
use InvalidArgumentException;
use RuntimeException;

/**
 * Registry managing and securing all tools available to the CRM Agent.
 */
class AgentToolRegistry
{
    /** @var array<string, AgentToolInterface> */
    private array $tools = [];

    public function __construct()
    {
        $this->registerLeadPipelineTools();
        $this->registerContactCompanyTools();
        $this->registerClientFinanceTools();
        $this->registerOperationProjectTools();
    }

    private function registerLeadPipelineTools(): void
    {
        $this->register(new LeadStatisticsTool());
        $this->register(new SearchLeadsTool());
        $this->register(new GetLeadTool());
        $this->register(new PipelineStatisticsTool());
    }

    private function registerContactCompanyTools(): void
    {
        $this->register(new SearchContactsTool());
        $this->register(new GetContactTool());
        $this->register(new SearchCompaniesTool());
        $this->register(new GetCompanyTool());
    }

    private function registerClientFinanceTools(): void
    {
        $this->register(new SearchClientsTool());
        $this->register(new GetClientTool());
        $this->register(new TopClientsTool());
        $this->register(new PaymentStatisticsTool());
    }

    private function registerOperationProjectTools(): void
    {
        $this->register(new SearchTasksTool());
        $this->register(new TaskStatisticsTool());
        $this->register(new SearchFollowupsTool());
        $this->register(new SearchProjectsTool());
        $this->register(new ProjectStatisticsTool());
        $this->register(new RecentActivityTool());
        $this->register(new QueryDatabaseTool());
    }

    public function register(AgentToolInterface $tool): self
    {
        $this->tools[$tool->getName()] = $tool;
        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->tools[$name]);
    }

    public function get(string $name): ?AgentToolInterface
    {
        return $this->tools[$name] ?? null;
    }

    /**
     * Return formatted tool definitions for LLM providers, scoped to authorized tools.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getDefinitionsForContext(AgentContext $context): array
    {
        $definitions = [];

        foreach ($this->tools as $tool) {
            $requiredPermission = $tool->getRequiredPermission();
            if ($requiredPermission && !$context->can($requiredPermission)) {
                continue;
            }

            $definitions[] = [
                'name'        => $tool->getName(),
                'description' => $tool->getDescription(),
                'parameters'  => $tool->getParametersSchema(),
            ];
        }

        return $definitions;
    }

    /**
     * Authorize and execute a tool under the user's secure context.
     *
     * @param array<string, mixed> $arguments
     * @return array<string, mixed>
     */
    public function execute(string $name, array $arguments, AgentContext $context): array
    {
        $tool = $this->get($name);
        if (!$tool) {
            throw new InvalidArgumentException("Unknown tool [{$name}] requested by Agent.");
        }

        $requiredPermission = $tool->getRequiredPermission();
        if ($requiredPermission && !$context->can($requiredPermission)) {
            throw new RuntimeException("Unauthorized: You do not have permission to execute tool [{$name}].");
        }

        return $tool->execute($arguments, $context);
    }
}
