<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getReports($user)
    {
        $monthStart = $user->month_start ?? 1;

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('date')
            ->get();

        if ($transactions->isEmpty()) {
            return [];
        }

        $firstDate = Carbon::parse($transactions->first()->date);
        $lastDate = Carbon::parse($transactions->last()->date);

        $reports = [];

        $currentStart = $firstDate->copy()->day($monthStart);

        if ($firstDate->day < $monthStart) {
            $currentStart->subMonth();
        }

        while ($currentStart <= $lastDate) {

            $currentEnd = $currentStart->copy()->addMonth()->subDay();

            $reports[] = [
                'start' => $currentStart->toDateString(),
                'end' => $currentEnd->toDateString(),
                'label' => $currentStart->format('d M') . ' - ' . $currentEnd->format('d M')
            ];

            $currentStart->addMonth();
        }

        return collect($reports)->reverse()->values();
    }

    public function getTransactionsForReport($user_id, $start, $end)
    {
        return Transaction::with('category')
            ->where('user_id', $user_id)
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end);
    }

    public function getSummaryForReport($user_id, $report)
    {
        $reports = $this->getReports(auth()->user());
        $currentReport = collect($reports)->firstWhere('start', $report);

        $transactions = Transaction::where('user_id', $user_id)
        ->whereDate('date', '>=', $currentReport['start'])
        ->whereDate('date', '<=', $currentReport['end']);

        $totalIncome = (clone $transactions)
        ->whereHas('category', function ($q) {
            $q->where('type', 'income');
        })
        ->sum('amount');

        $totalExpense = (clone $transactions)
        ->whereHas('category', function ($q) {
            $q->where('type', 'expense');
        })
        ->sum('amount');

        $topExpenseCategory = (clone $transactions)
        ->select('category_id', DB::raw('SUM(amount) as total'))
        ->whereHas('category', function ($q) {
            $q->where('type', 'expense');
        })
        ->groupBy('category_id')
        ->orderByDesc('total')
        ->with('category')
        ->first();

        $topIncomeCategory = (clone $transactions)
        ->select('category_id', DB::raw('SUM(amount) as total'))
        ->whereHas('category', function ($q) {
            $q->where('type', 'income');
        })
        ->groupBy('category_id')
        ->orderByDesc('total')
        ->with('category')
        ->first();

        $days = Carbon::parse($currentReport['start'])->diffInDays($currentReport['end']) + 1;
        $avgExpense = $days > 0 ? $totalExpense / $days : 0;

        $savingsRate = $totalIncome > 0
        ? (($totalIncome - $totalExpense) / $totalIncome) * 100
        : 0;

        $expenseByCategory = (clone $transactions)
        ->select('category_id', DB::raw('SUM(amount) as total'))
        ->where('type', 'expense')
        ->groupBy('category_id')
        ->orderByDesc('total')
        ->with('category')
        ->get();

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
            'top_expense_category' => $topExpenseCategory?->category?->name,
            'top_income_category' => $topIncomeCategory?->category?->name,
            'average_daily_expense' => round($avgExpense, 2),
            'savings_rate' => round($savingsRate, 2),
            'expense_by_category' => $expenseByCategory
        ];
    }

    public function getDataForCurrentReport($userId) {
        $now = now();

        $currentStart = $now->copy()->startOfMonth();
        $currentEnd = $now;

        $daysPassed = $now->day;

        $previousStart = $now->copy()->subMonth()->startOfMonth();
        $previousEnd = $previousStart->copy()->addDays($daysPassed - 1);

        $currentIncome = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$currentStart, $currentEnd])
            ->whereHas('category', fn($q) => $q->where('type', 'income'))
            ->sum('amount');

        $previousIncome = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$previousStart, $previousEnd])
            ->whereHas('category', fn($q) => $q->where('type', 'income'))
            ->sum('amount');

        $currentExpenses = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$currentStart, $currentEnd])
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->sum('amount');

        $previousExpenses = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$previousStart, $previousEnd])
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->sum('amount');

        $incomeDiff = $previousIncome > 0
            ? (($currentIncome - $previousIncome) / $previousIncome) * 100
            : null;

        $expensesDiff = $previousExpenses > 0
            ? (($currentExpenses - $previousExpenses) / $previousExpenses) * 100
            : null;

        return [
            'income' => $currentIncome,
            'expenses' => $currentExpenses,
            'incomeDiff' => round($incomeDiff, 2),
            'expensesDiff' => round($expensesDiff, 2)
        ];
    }

    public function getMonthlyIncomeExpenseChart($userId, $months = 6)
    {
        $startDate = now()->subMonths($months - 1)->startOfMonth();
        $endDate = now()->endOfMonth();

        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $grouped = $transactions->groupBy(function ($t) {
            return Carbon::parse($t->date)->format('Y-m');
        });

        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $label = $date->format('M Y');

            $labels[] = $label;

            $monthTransactions = $grouped->get($key, collect());

            $income = $monthTransactions
                ->filter(fn($t) => $t->category->type === 'income')
                ->sum('amount');

            $expense = $monthTransactions
                ->filter(fn($t) => $t->category->type === 'expense')
                ->sum('amount');

            $incomeData[] = $income;
            $expenseData[] = -$expense;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $incomeData,
                    'backgroundColor' => '#4723bd',
                    'borderRadius' => 5,
                ],
                [
                    'label' => 'Expense',
                    'data' => $expenseData,
                    'backgroundColor' => '#b8a2fc',
                    'borderRadius' => 5,
                ]
            ]
        ];
    }

    public function getCategoryBreakdown($userId, $range = '1m')
    {
        if ($range === '6m') {
            $start = now()->subMonths(5)->startOfMonth();
        } elseif ($range === '12m') {
            $start = now()->subMonths(11)->startOfMonth();
        } else {
            $start = now()->startOfMonth();
        }

        $end = now()->endOfMonth();

        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->whereBetween('date', [$start, $end])
            ->get();

        $totalSum = $transactions->sum('amount');

        if ($totalSum == 0) {
            return [
                'labels' => ['No expenses yet'],
                'datasets' => [
                    [
                        'data' => [100],
                        'backgroundColor' => ['#e0e0e0'],
                    ]
                ]
            ];
        }

        $grouped = $transactions->groupBy('category_id')->map(function ($items) {
            return [
                'name' => optional($items->first()->category)->name ?? 'Unknown',
                'total' => $items->sum('amount')
            ];
        });

        $sorted = $grouped->sortByDesc('total')->values();

        $top = $sorted->take(5);
        $otherTotal = $sorted->slice(5)->sum('total');

        $labels = [];
        $data = [];

        foreach ($top as $item) {
            $percentage = $totalSum > 0 ? ($item['total'] / $totalSum) * 100 : 0;

            $labels[] = $item['name'];
            $data[] = round($percentage, 1);
        }

        if ($otherTotal > 0) {
            $percentage = ($otherTotal / $totalSum) * 100;

            $labels[] = 'Other';
            $data[] = round($percentage, 1);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Amount by Category (%)',
                    'data' => $data,
                    'backgroundColor' => [
                        '#6f66a7',
                        '#E8A09A',
                        '#FBE29F',
                        '#9ccdb7',
                        '#ee989a',
                        '#aea0db'
                    ],
                        'borderColor' => [
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff'
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    public function getExpenseCategorySums($userId, $start = null, $end = null)
    {
        $start = $start ? Carbon::parse($start) : now()->startOfMonth();
        $end = $end ? Carbon::parse($end) : now()->endOfMonth();

        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->whereBetween('date', [$start, $end])
            ->get();

        $grouped = $transactions->groupBy(fn($t) => $t->category->name ?? 'Unknown');

        $labels = [];
        $data = [];

        foreach ($grouped as $categoryName => $items) {
            $labels[] = $categoryName;
            $data[] = $items->sum('amount');
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Expenses by Category',
                    'data' => $data,
                    'backgroundColor' => '#b8a2fc',
                    'borderRadius' => 5,
                ]
            ]
        ];
    }
}
