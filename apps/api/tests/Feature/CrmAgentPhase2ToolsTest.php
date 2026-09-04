<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\CrmAgent;
use App\Agents\Crm\Tools\GetClientTool;
use App\Agents\Crm\Tools\PaymentStatisticsTool;
use App\Agents\Crm\Tools\PipelineStatisticsTool;
use App\Agents\Crm\Tools\ProjectStatisticsTool;
use App\Agents\Crm\Tools\SearchFollowupsTool;
use App\Agents\Crm\Tools\SearchProjectsTool;
use App\Agents\Crm\Tools\SearchTasksTool;
use App\Agents\Crm\Tools\TaskStatisticsTool;
use App\Agents\Crm\Tools\TopClientsTool;
use App\AI\Providers\MockProvider;
use App\Domains\User\Models\User;
use App\Models\Client;
use App\Models\Company;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmAgentPhase2ToolsTest extends TestCase
{
    use RefreshDatabase;

    public function test_pipeline_statistics_computes_win_loss_and_conversion_rates(): void
    {
        $user = User::factory()->create();
        $context = AgentContext::fromUser($user);

        Lead::create(['company_name' => 'L1', 'contact_person' => 'P1', 'status' => 'won', 'estimated_budget' => 100000]);
        Lead::create(['company_name' => 'L2', 'contact_person' => 'P2', 'status' => 'lost', 'estimated_budget' => 50000]);
        Lead::create(['company_name' => 'L3', 'contact_person' => 'P3', 'status' => 'qualified', 'estimated_budget' => 75000]);

        $tool = new PipelineStatisticsTool();
        $res = $tool->execute([], $context);

        $this->assertEquals(3, $res['total_leads']);
        $this->assertEquals(75000, $res['active_pipeline_value']);
        $this->assertEquals(100000, $res['won_deals_value']);
        $this->assertEquals(50.0, $res['win_rate_percentage']);
        $this->assertEquals(100000, $res['average_won_deal_size']);
    }

    public function test_top_clients_and_client_tools_calculate_revenue_accurately(): void
    {
        $user = User::factory()->create();
        $context = AgentContext::fromUser($user);

        $clientA = Client::create(['client_code' => 'CLI-001', 'name' => 'Acme Corp', 'status' => 'active']);
        $clientB = Client::create(['client_code' => 'CLI-002', 'name' => 'Beta Global', 'status' => 'active']);

        Payment::create(['client_id' => $clientA->id, 'invoice_number' => 'INV-01', 'amount' => 50000, 'status' => 'paid']);
        Payment::create(['client_id' => $clientA->id, 'invoice_number' => 'INV-02', 'amount' => 15000, 'status' => 'pending']);
        Payment::create(['client_id' => $clientB->id, 'invoice_number' => 'INV-03', 'amount' => 80000, 'status' => 'paid']);

        $topTool = new TopClientsTool();
        $res = $topTool->execute(['by' => 'revenue', 'limit' => 5], $context);

        $this->assertEquals(2, $res['count']);
        $this->assertEquals('Beta Global', $res['top_clients'][0]['name']);
        $this->assertEquals(80000.0, $res['top_clients'][0]['metric_amount']);

        $getTool = new GetClientTool();
        $clientRes = $getTool->execute(['client_code' => 'CLI-001'], $context);
        $this->assertEquals('success', $clientRes['status']);
        $this->assertEquals(50000.0, $clientRes['client']['total_revenue']);
        $this->assertEquals(15000.0, $clientRes['client']['pending_payment']);
    }

    public function test_task_search_and_statistics_tools_identify_overdue_tasks(): void
    {
        $user = User::factory()->create();
        $context = AgentContext::fromUser($user);

        Task::create([
            'title' => 'Fix SSL Cert',
            'status' => 'in_progress',
            'priority' => 'urgent',
            'due_date' => Carbon::yesterday(),
        ]);

        Task::create([
            'title' => 'Design Logo',
            'status' => 'completed',
            'priority' => 'low',
            'due_date' => Carbon::yesterday(),
        ]);

        $statsTool = new TaskStatisticsTool();
        $stats = $statsTool->execute([], $context);
        $this->assertEquals(2, $stats['total_tasks']);
        $this->assertEquals(1, $stats['completed']);
        $this->assertEquals(1, $stats['overdue_count']);
        $this->assertEquals(1, $stats['open_urgent_count']);

        $searchTool = new SearchTasksTool();
        $overdueTasks = $searchTool->execute(['is_overdue' => true], $context);
        $this->assertEquals(1, $overdueTasks['count']);
        $this->assertEquals('Fix SSL Cert', $overdueTasks['tasks'][0]['title']);
    }

    public function test_payment_and_project_statistics_metrics(): void
    {
        $user = User::factory()->create();
        $context = AgentContext::fromUser($user);

        $client = Client::create(['client_code' => 'CLI-003', 'name' => 'Stark Tech', 'status' => 'active']);

        Payment::create(['client_id' => $client->id, 'invoice_number' => 'INV-10', 'amount' => 120000, 'status' => 'paid']);
        Payment::create(['client_id' => $client->id, 'invoice_number' => 'INV-11', 'amount' => 30000, 'status' => 'pending']);

        Project::create([
            'client_id' => $client->id,
            'name' => 'Iron Suit Portal',
            'budget' => 200000,
            'status' => 'development',
            'progress' => 45,
            'deadline' => Carbon::tomorrow(),
        ]);

        $payTool = new PaymentStatisticsTool();
        $payStats = $payTool->execute([], $context);
        $this->assertEquals(120000.0, $payStats['collected_revenue']);
        $this->assertEquals(30000.0, $payStats['pending_balance']);

        $projTool = new ProjectStatisticsTool();
        $projStats = $projTool->execute([], $context);
        $this->assertEquals(1, $projStats['total_projects']);
        $this->assertEquals(1, $projStats['active_projects']);
        $this->assertEquals(200000.0, $projStats['total_portfolio_budget']);
        $this->assertEquals(45.0, $projStats['average_progress_percentage']);
    }

    public function test_crm_agent_end_to_end_answering_phase2_questions(): void
    {
        $user = User::factory()->create();
        $client = Client::create(['client_code' => 'CLI-004', 'name' => 'Wayne Tech', 'status' => 'active']);
        Payment::create(['client_id' => $client->id, 'invoice_number' => 'INV-99', 'amount' => 95000, 'status' => 'paid']);

        $agent = new CrmAgent(provider: new MockProvider());

        $res1 = $agent->ask($user, 'Who are our top clients?');
        $this->assertStringContainsString('Top Clients', $res1['message']);
        $this->assertStringContainsString('Wayne Tech', $res1['message']);

        $res2 = $agent->ask($user, 'What are our payment statistics and collected revenue?');
        $this->assertStringContainsString('Financial & Billing Overview', $res2['message']);
        $this->assertStringContainsString('95,000.00', $res2['message']);
    }
}
