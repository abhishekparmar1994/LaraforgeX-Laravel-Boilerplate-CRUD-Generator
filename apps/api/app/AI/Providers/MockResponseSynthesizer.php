<?php

declare(strict_types=1);

namespace App\AI\Providers;

/**
 * Synthesizes natural language responses for MockProvider tool execution results.
 */
class MockResponseSynthesizer
{
    /**
     * Synthesizes readable markdown response for a tool result.
     *
     * @param string $toolName
     * @param array<string, mixed> $data
     * @return string
     */
    public function format(string $toolName, array $data): string
    {
        return match ($toolName) {
            'lead_statistics'     => $this->formatLeadStats($data),
            'search_leads'        => $this->formatLeads($data),
            'get_lead'            => $this->formatLead($data),
            'top_clients'         => $this->formatTopClients($data),
            'pipeline_statistics' => $this->formatPipelineStats($data),
            'search_tasks'        => $this->formatTasks($data),
            'task_statistics'     => $this->formatTaskStats($data),
            'search_followups'    => $this->formatFollowups($data),
            'search_projects'     => $this->formatProjects($data),
            'project_statistics'  => $this->formatProjectStats($data),
            'payment_statistics'  => $this->formatPaymentStats($data),
            'recent_activity'     => $this->formatRecentActivity($data),
            'search_clients'      => $this->formatClients($data),
            default               => $this->formatGeneric($data),
        };
    }

    private function formatTopClients(array $data): string
    {
        $metric = ($data['ranked_by'] ?? 'revenue') === 'pending' ? 'Pending Balance' : 'Total Revenue';
        $out = "### 🏆 Top Clients by {$metric}\n\n";
        foreach ($data['top_clients'] ?? [] as $i => $cl) {
            $num = $i + 1;
            $amt = number_format((float) ($cl['metric_amount'] ?? 0), 2);
            $out .= "{$num}. **{$cl['name']}** ({$cl['client_code']}) — ₹{$amt} ({$cl['company']})\n";
        }
        return $out;
    }

    private function formatPipelineStats(array $data): string
    {
        $val = number_format((float) ($data['active_pipeline_value'] ?? 0), 2);
        $won = number_format((float) ($data['won_deals_value'] ?? 0), 2);
        $rate = $data['win_rate_percentage'] ?? 0;
        return "### 📈 Sales Pipeline Intelligence\n\n"
            . "- **Total Leads in Pipeline:** {$data['total_leads']}\n"
            . "- **Active Pipeline Value:** ₹{$val}\n"
            . "- **Won Deals Value:** ₹{$won}\n"
            . "- **Pipeline Win Rate:** {$rate}%\n"
            . "- **Average Won Deal Size:** ₹" . number_format((float) ($data['average_won_deal_size'] ?? 0), 2);
    }

    private function formatTasks(array $data): string
    {
        $count = $data['count'] ?? 0;
        $out = "Found **{$count}** task(s):\n\n";
        foreach (array_slice($data['tasks'] ?? [], 0, 5) as $t) {
            $overdue = !empty($t['is_overdue']) ? ' ⚠️ **OVERDUE**' : '';
            $out .= "• **{$t['title']}** [`{$t['status']}` / `{$t['priority']}`] - Project: {$t['project']} (Due: {$t['due_date']}){$overdue}\n";
        }
        return $out;
    }

    private function formatTaskStats(array $data): string
    {
        return "### 📋 Task Management Metrics\n\n"
            . "- **Total Tasks:** {$data['total_tasks']}\n"
            . "- **Completed:** {$data['completed']} ({$data['completion_rate_percentage']}%)\n"
            . "- **In Progress:** {$data['in_progress']}\n"
            . "- **Todo:** {$data['todo']}\n"
            . "- **Overdue Tasks:** ⚠️ {$data['overdue_count']}\n"
            . "- **Urgent Priority:** {$data['open_urgent_count']}";
    }

    private function formatFollowups(array $data): string
    {
        $count = $data['count'] ?? 0;
        $tf = ucfirst($data['timeframe'] ?? 'upcoming');
        $out = "### 📞 {$tf} Follow-ups ({$count})\n\n";
        foreach (array_slice($data['followups'] ?? [], 0, 5) as $f) {
            $out .= "• **{$f['type']}** with **{$f['target']}** ({$f['contact_name']}) at " . substr((string) ($f['scheduled_at'] ?? ''), 0, 16) . "\n";
        }
        return $out;
    }

    private function formatProjects(array $data): string
    {
        $count = $data['count'] ?? 0;
        $out = "Found **{$count}** project(s):\n\n";
        foreach (array_slice($data['projects'] ?? [], 0, 5) as $p) {
            $budget = number_format((float) ($p['budget'] ?? 0), 2);
            $out .= "• **{$p['name']}** [Client: {$p['client']}] — `{$p['status']}` ({$p['progress']}) | Budget: ₹{$budget}\n";
        }
        return $out;
    }

    private function formatProjectStats(array $data): string
    {
        $budget = number_format((float) ($data['total_portfolio_budget'] ?? 0), 2);
        return "### 🚀 Project Portfolio Overview\n\n"
            . "- **Total Projects:** {$data['total_projects']}\n"
            . "- **Active:** {$data['active_projects']}\n"
            . "- **Completed:** {$data['completed_projects']}\n"
            . "- **Overdue:** {$data['overdue_projects']}\n"
            . "- **Total Portfolio Budget:** ₹{$budget}\n"
            . "- **Average Progress:** {$data['average_progress_percentage']}%";
    }

    private function formatPaymentStats(array $data): string
    {
        $rev = number_format((float) ($data['collected_revenue'] ?? 0), 2);
        $pending = number_format((float) ($data['pending_balance'] ?? 0), 2);
        $overdue = number_format((float) ($data['overdue_amount'] ?? 0), 2);
        return "### 💰 Financial & Billing Overview\n\n"
            . "- **Total Invoices:** {$data['total_invoices']}\n"
            . "- **Collected Revenue:** ₹{$rev} ({$data['paid_invoices_count']} paid)\n"
            . "- **Outstanding Balance:** ₹{$pending} ({$data['pending_invoices_count']} pending)\n"
            . "- **Overdue Invoices:** ₹{$overdue} ({$data['overdue_invoices_count']} overdue)";
    }

    private function formatRecentActivity(array $data): string
    {
        $out = "### 🕒 Recent CRM Activity Stream\n\n";
        foreach (array_slice($data['activities'] ?? [], 0, 5) as $a) {
            $out .= "• **{$a['user']}** performed `{$a['type']}` on {$a['entity']}" . ($a['entity_name'] ? " ({$a['entity_name']})" : '') . ": {$a['description']} *({$a['time_ago']})*\n";
        }
        return $out;
    }

    private function formatClients(array $data): string
    {
        $count = $data['count'] ?? 0;
        $out = "Found **{$count}** client(s):\n\n";
        foreach (array_slice($data['clients'] ?? [], 0, 5) as $c) {
            $out .= "• **{$c['name']}** ({$c['client_code']}) — Status: `{$c['status']}` | Industry: {$c['industry']}\n";
        }
        return $out;
    }

    private function formatLeadStats(array $data): string
    {
        return "### 📊 CRM Lead Overview\n\n"
            . "- **Total Leads:** " . ($data['total_leads'] ?? 0) . "\n"
            . "- **Qualified Leads:** " . ($data['by_status']['qualified'] ?? 0) . "\n"
            . "- **Won Deals:** " . ($data['by_status']['won'] ?? 0) . "\n"
            . "- **Conversion Rate:** " . ($data['conversion_rate'] ?? '0%') . "\n"
            . "- **Total Estimated Pipeline Value:** ₹" . number_format((float) ($data['total_estimated_budget'] ?? 0), 2) . "\n"
            . "- **Leads Needing Follow-up (>7 days uncontacted):** " . ($data['uncontacted_over_7_days'] ?? 0);
    }

    private function formatLeads(array $data): string
    {
        $count = $data['count'] ?? 0;
        $out = "Found **{$count}** lead(s) matching your criteria:\n\n";
        foreach (array_slice($data['records'] ?? [], 0, 5) as $lead) {
            $comp = $lead['company_name'] ?? 'Unknown Company';
            $contact = $lead['contact_person'] ?? 'N/A';
            $status = strtoupper($lead['status'] ?? 'NEW');
            $budget = number_format((float) ($lead['estimated_budget'] ?? 0), 2);
            $out .= "• **{$comp}** ({$contact}) — `{$status}` | Budget: ₹{$budget}\n";
        }
        return $out;
    }

    private function formatLead(array $data): string
    {
        $lead = $data['lead'] ?? $data;
        $comp = $lead['company_name'] ?? 'Unknown';
        $contact = $lead['contact_person'] ?? 'Unknown';
        $status = strtoupper($lead['status'] ?? 'NEW');
        $budget = number_format((float) ($lead['estimated_budget'] ?? 0), 2);
        return "### 👤 Lead Profile: {$comp}\n\n"
            . "- **Contact Person:** {$contact}\n"
            . "- **Email:** " . ($lead['email'] ?? 'N/A') . "\n"
            . "- **Phone:** " . ($lead['phone'] ?? 'N/A') . "\n"
            . "- **Current Stage:** `{$status}`\n"
            . "- **Estimated Budget:** ₹{$budget}\n"
            . "- **Last Contacted:** " . ($lead['last_contact_date'] ?? 'Never') . "\n"
            . "- **Requirement:** " . ($lead['requirement'] ?? 'None specified');
    }

    private function formatGeneric(array $data): string
    {
        return "Here are the results retrieved from the CRM:\n\n```json\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n```";
    }
}
