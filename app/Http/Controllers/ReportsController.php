<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\ReportService;



class ReportsController extends Controller
{
    public function reports(Request $request, ReportService $reportService)
    {
        $userId = auth()->user()->id;
        $reports = $reportService->getReports(auth()->user());
        $reportStart = $request->query('report');
        $categories = Category::whereRaw('(user_id IS NULL OR user_id = ?)', [$userId])->get();

        $transactionsAll = Transaction::where('user_id', $userId)->count();
        if($transactionsAll === 0) {
            return view('pages.no-transactions', compact('categories'));
        }

        if($reportStart) {
            $currentReport = collect($reports)->firstWhere('start', $reportStart);
        }
        else {
            $currentReport = ['start' => $reports[0]['start'], 'end' => $reports[0]['end']];
        }

        $transactionsQuery = $reportService->getTransactionsForReport(
            $userId,
            $currentReport['start'],
            $currentReport['end']
        );

        if ($request->filled('category')) {
            $transactionsQuery->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $transactionsQuery->whereHas('category', function($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $transactions = $transactionsQuery->orderBy('date', 'desc')->paginate(8)->withQueryString();

        $summary = $reportService->getSummaryForReport($userId, $currentReport['start']);

        $expenseCategoryData = $reportService->getExpenseCategorySums(
            $userId,
            $currentReport['start'],
            $currentReport['end']
        );

        $view = $request->query('view', 'transactions');

        if ($request->ajax()) {
            if ($view === 'transactions') {
                return response()->json([
                    'main' => view('partials.transactions', compact('transactions', 'currentReport'))->render(),
                    'side' => view('partials.filters', compact('categories'))->render(),
                ]);
            } else {
                return response()->json([
                    'main' => view('partials.analytics', compact('expenseCategoryData'))->render(),
                    'side' => view('partials.report-summary', compact('summary'))->render(),
                ]);
            }
        }

        return view('pages.reports', [
            'categories' => $categories,
            'transactions' => $transactions,
            'reports' => $reports,
            'currentReport' => $currentReport,
            'summary' => $summary,
            'view' => $view,
            'expenseCategoryData' => $expenseCategoryData
        ]);
    }

    public function list(ReportService $service)
    {
        $user = auth()->user();

        $reports = $service->getReports($user);

        return view('partials.reports-list', compact('reports'));
    }

    public function summary(Request $request, ReportService $service)
    {
        $user_id = auth()->user()->id;

        $summary = $service->getSummaryForReport($user_id, $request->report);

        return view('partials.report-summary', compact('summary'));
    }

}
