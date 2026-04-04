<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\ReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(ReportService $service)
    {
        $user_id = auth()->user()->id;
        $currentReportData = $service->getDataForCurrentReport($user_id);
        $categories = Category::whereRaw('(user_id IS NULL OR user_id = ?)', [$user_id])->get();

        $transactions = Transaction::where('user_id', $user_id)->count();
        if($transactions === 0) {
            return view('pages.no-transactions', compact('categories'));
        }

        return view('pages.dashboard', ['currentReport' => $currentReportData]);
    }

    public function balanceChart(ReportService $service, Request $request) {
        $user_id = auth()->user()->id;
        $chartData = $service->getMonthlyIncomeExpenseChart($user_id, $request->range ?? 6);

        return response()->json($chartData);
    }

    public function categoryChart(ReportService $service, Request $request) {
        $user_id = auth()->user()->id;
        $chartData = $service->getCategoryBreakdown(
            $user_id,
            $request->range ?? '1m'
        );

        return response()->json($chartData);
    }

}
