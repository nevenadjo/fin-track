<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Log::with('user');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->search) {
            $query->where('message', 'like', "%{$request->search}%");
        }

        $logs = $query->latest()->paginate(10);

        return view('pages.admin.log', compact('logs'));
    }
}
