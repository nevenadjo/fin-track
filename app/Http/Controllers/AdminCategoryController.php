<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\LogService;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        if($request->filled('type')){
            $categories = Category::where('user_id', null)->where('type', $request->type)->where(function ($query) {
                $query->where('is_archived', false)
                    ->orWhereNull('is_archived');
            })->latest()->get();
         }
        else {
            $categories = Category::where('user_id', null)->where(function ($query) {
                $query->where('is_archived', false)
                    ->orWhereNull('is_archived');
            })->latest()->get();
        }

        return view('pages.admin.categories', compact('categories'));
    }

    public function store(Request $request, LogService $logService)
    {
        Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'user_id' => null
        ]);

        $logService->log('category_create', 'Created category: '.$request->name);

        return back()->with('success', 'Successfully added a category');
    }

    public function update(Request $request, Category $category, LogService $logService)
    {
        $category->update($request->only('name', 'type'));

        $logService->log('category_update', 'Updated category: '.$category->name);

        return back()->with('success', 'Successfully updated a category');
    }

    public function destroy(Category $category, LogService $logService)
    {
        $categoryName = $category->name;
        Category::where('id', $category->id)
            ->update(['is_archived' => true]);

        $logService->log('category_delete', 'Deleted category: '.$categoryName);

        return back()->with('success', 'Successfully deleted a category');
    }
}
