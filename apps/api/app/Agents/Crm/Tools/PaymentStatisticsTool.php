<?php

declare(strict_types=1);

namespace App\Agents\Crm\Tools;

use App\Agents\Crm\AgentContext;
use App\Agents\Crm\Contracts\AgentToolInterface;
use App\Models\Payment;
use Carbon\Carbon;

/**
 * Computes agency financial metrics, collected revenue, and outstanding invoices.
 */
class PaymentStatisticsTool implements AgentToolInterface
{
    public function getName(): string
    {
        return 'payment_statistics';
    }

    public function getDescription(): string
    {
        return 'Get financial and invoice analytics: total revenue collected, outstanding pending balances, overdue payment amounts, and collection health.';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'client_id' => [
                    'type' => 'string',
                    'description' => 'Optional client UUID to filter invoice metrics.'
                ],
                'project_id' => [
                    'type' => 'string',
                    'description' => 'Optional project UUID to filter invoice metrics.'
                ],
            ],
            'required' => [],
        ];
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function getRequiredPermission(): ?string
    {
        return null;
    }

    public function execute(array $arguments, AgentContext $context): array
    {
        $query = Payment::query();

        if (!empty($arguments['client_id'])) {
            $query->where('client_id', $arguments['client_id']);
        }

        if (!empty($arguments['project_id'])) {
            $query->where('project_id', $arguments['project_id']);
        }

        $totalInvoices = (clone $query)->count();
        $paidRevenue = (float) (clone $query)->where('status', 'paid')->sum('amount');
        $paidCount = (clone $query)->where('status', 'paid')->count();

        $pendingAmount = (float) (clone $query)->whereIn('status', ['pending', 'partial'])->sum('amount');
        $pendingCount = (clone $query)->whereIn('status', ['pending', 'partial'])->count();

        $overdueQuery = (clone $query)->where(function ($q) {
            $q->where('status', 'overdue')
              ->orWhere(function ($sub) {
                  $sub->whereIn('status', ['pending', 'partial'])
                      ->whereNotNull('due_date')
                      ->where('due_date', '<', Carbon::today());
              });
        });

        $overdueAmount = (float) (clone $overdueQuery)->sum('amount');
        $overdueCount = (clone $overdueQuery)->count();

        return [
            'status'                 => 'success',
            'total_invoices'         => $totalInvoices,
            'paid_invoices_count'    => $paidCount,
            'collected_revenue'      => round($paidRevenue, 2),
            'pending_invoices_count' => $pendingCount,
            'pending_balance'        => round($pendingAmount, 2),
            'overdue_invoices_count' => $overdueCount,
            'overdue_amount'         => round($overdueAmount, 2),
        ];
    }
}
