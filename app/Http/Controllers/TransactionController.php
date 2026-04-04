<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\LogService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function index()
    {
        return Transaction::where('user_id', auth()->id())->get();
    }

    public function store(Request $request, LogService $logService)
    {
        $categories = Category::all()->pluck('id');
        $categoryString = implode(',', $categories->values()->toArray());

        $request->validate([
            'amount' => 'required|decimal:2|gt:0',
            'category' => 'required|integer|in:'.$categoryString,
            'date' => 'required|date|before_or_equal:now'
        ]);

        Transaction::create([
            'amount' => $request->amount,
            'category_id' => $request->category,
            'date' => $request->date,
            'user_id' => auth()->id()
        ]);

        $logService->log('transaction_create', 'Created transaction: '.$request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created'
        ]);
    }

    public function update(Request $request, $id, LogService $logService)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong..'
            ], 403);
        }

        $categories = Category::pluck('id')->toArray();
        $categoryString = implode(',', $categories);

        $request->validate([
            'amount' => 'required|decimal:2|gt:0',
            'category' => 'required|integer|in:' . $categoryString,
            'date' => 'required|date|before_or_equal:today'
        ]);

        $transaction->update([
            'amount' => $request->amount,
            'category_id' => $request->category,
            'date' => $request->date,
        ]);

        $logService->log('transaction_update', 'Updated transaction ID: '.$transaction->id);

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated'
        ]);
    }

    public function destroy($id, LogService $logService)
    {
        Transaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        $logService->log('transaction_delete', 'Deleted transaction ID: '.$id);

        return response()->json(['success' => true]);
    }
}
