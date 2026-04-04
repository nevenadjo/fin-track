<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LogService;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::where('user_id', auth()->id())->where('is_archived', null)->get();
    }

    public function store(Request $request, LogService $logService)
    {
        $request->validate([
            'name' => 'required|string|min:2',
            'type' => 'required|in:income,expense'
        ]);

        $logService->log('category_create', 'Created category: '.$request->name);

        return Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'user_id' => auth()->id()
        ]);
    }

    public function destroy($id, LogService $logService)
    {
        $category = Category::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_archived' => true]);

        $logService->log('category_delete', 'Deleted category: '.$category->name);

        return response()->json(['success' => true]);
    }
}
