<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())
            ->withCount('transactions')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        Category::firstOrCreate([
            'user_id' => auth()->id(),
            'category_name' => $request->category_name,
            'type' => $request->type,
        ]);

        return back();
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        if ($category->transactions()->exists()) {
            return back()->with('error', '使用中のカテゴリは削除できません');
        }

        $category->delete();

        return back();
    }
}
