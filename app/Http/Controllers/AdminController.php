<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $logsCount = Log::count();
        $transactionsCount = Transaction::count();

        $last7Days = now()->subDays(6)->startOfDay();

        $usersData = User::where('created_at', '>=', $last7Days)
            ->get()
            ->groupBy(fn($u) => $u->created_at->format('Y-m-d'))
            ->map->count();

        $usersChart = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            $usersChart->put($date, $usersData->get($date, 0));
        }

        $logsData = Log::where('created_at', '>=', $last7Days)
            ->get()
            ->groupBy(fn($l) => $l->created_at->format('Y-m-d'))
            ->map->count();

        $logsChart = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            $logsChart->put($date, $logsData->get($date, 0));
        }

        $transactionsData = Transaction::where('created_at', '>=', $last7Days)
            ->get()
            ->groupBy(fn($t) => $t->created_at->format('Y-m-d'))
            ->map->count();

        $transactionsChart = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            $transactionsChart->put($date, $transactionsData->get($date, 0));
        }

        return view('pages.admin.dashboard', compact(
            'usersCount',
            'logsCount',
            'transactionsCount',
            'usersChart',
            'logsChart',
            'transactionsChart'
        ));
    }
}
